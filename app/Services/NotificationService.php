<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Create a notification for a user.
     *
     * @param int $userId The ID of the user to notify
     * @param string $message The notification message
     * @return \App\Models\Notification
     */
    public static function notify(int $userId, string $message)
    {
        return Notification::create([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => false
        ]);
    }

    /**
     * Notify a client that their document status has changed.
     *
     * @param \App\Models\Document $document The document that changed status
     * @param string $notes Additional notes about the status change (optional)
     * @return \App\Models\Notification
     */
    public static function notifyDocumentStatusChange(Document $document, ?string $notes = null)
    {
        $status = ucfirst($document->status);
        $message = "Your document request ({$document->documentType->name}) has been marked as {$status}.";

        if ($notes) {
            $message .= " Notes: {$notes}";
        }

        return self::notify($document->admin_id, $message);
    }

    /**
     * Notify admins about pending documents (safe for NIK/KK only submissions).
     *
     * @param Document $document The pending document
     * @return void
     */
    public static function notifyAdminAboutNewDocument(Document $document)
    {
        $admins = User::where('role', 'admin')->get();
        $typeName = $document->documentType ? $document->documentType->name : 'Unknown Type';
        $submitter = $document->admin ? $document->admin->name : 'Unknown';
        $message = "New document request ({$typeName}) submitted by {$submitter}.";

        foreach ($admins as $admin) {
            if ($admin && $admin->id) {
                self::notify($admin->id, $message);
            }
        }
    }

    /**
     * Create overdue reminders for admins about documents that have been pending for 3+ days.
     *
     * @return void
     */
    public static function createOverdueReminders()
    {
        $threeDaysAgo = Carbon::now()->subDays(3);

        $overdueDocuments = Document::where('status', 'pending')
            ->where('created_at', '<=', $threeDaysAgo)
            ->get();

        if ($overdueDocuments->isEmpty()) {
            return;
        }

        $admins = User::where('role', 'admin')->get();

        foreach ($overdueDocuments as $document) {
            $daysOverdue = Carbon::parse($document->created_at)->diffInDays(Carbon::now());
            $message = "REMINDER: Document request #{$document->id} ({$document->documentType->name}) has been pending for {$daysOverdue} days.";

            foreach ($admins as $admin) {
                // Only create reminder if we haven't already created one for this document in the past 24 hours
                $existingReminder = Notification::where('user_id', $admin->id)
                    ->where('message', 'like', "REMINDER: Document request #{$document->id}%")
                    ->where('created_at', '>=', Carbon::now()->subDay())
                    ->exists();

                if (!$existingReminder) {
                    self::notify($admin->id, $message);
                }
            }
        }
    }

    /**
     * Get count of unread notifications for a user.
     *
     * @param int $userId The user ID
     * @return int The count of unread notifications
     */
    public static function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Mark all notifications as read for a user.
     *
     * @param int $userId The user ID
     * @return int The number of notifications marked as read
     */
    public static function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
