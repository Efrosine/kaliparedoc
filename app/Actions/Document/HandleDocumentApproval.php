<?php

namespace App\Actions\Document;

use App\Models\Document;
use App\Models\Log;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HandleDocumentApproval
{
    public function approve(Document $document)
    {
        try {
            DB::beginTransaction();

            // Update document status
            $document->status = 'completed';
            $document->admin_id = Auth::id();

            // Nonaktifkan generate document number otomatis
            // $generateNumber = new GenerateDocumentNumber();
            // $document->number = $generateNumber->handle($document);
            $numberFormat = $document->documentType->numberFormat->currentVersion->format_string ?? null;
            if ($numberFormat && strpos($numberFormat, '{{number}}') !== false) {
                $document->number = str_replace('{{number}}', '', $numberFormat);
            } else {
                $document->number = $numberFormat;
            }

            $document->save();

            // Create notification for the client using the service
            \App\Services\NotificationService::notifyDocumentStatusChange(
                $document,
                "Your request has been approved. Document number: {$document->number}"
            );

            // Log this action using the service
            \App\Services\LoggingService::logDocumentStatusChange(
                $document->id,
                'completed',
                "Document approved with number: {$document->number}"
            );

            DB::commit();
            return $document;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reject(Document $document, string $reason)
    {
        try {
            DB::beginTransaction();

            // Update document status
            $document->status = 'rejected';
            $document->admin_id = Auth::id();
            $document->save();

            // Create notification for the client with rejection reason
            \App\Services\NotificationService::notifyDocumentStatusChange(
                $document,
                "Reason: {$reason}"
            );

            // Log this action using the service
            \App\Services\LoggingService::logDocumentStatusChange(
                $document->id,
                'rejected',
                "Document rejected. Reason: {$reason}"
            );

            DB::commit();
            return $document;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
