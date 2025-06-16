<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LoggingService
{
    /**
     * Log an action performed by a user.
     *
     * @param string $action The action that was performed
     * @param string|null $modelType The type of model that was affected (optional)
     * @param int|null $modelId The ID of the model that was affected (optional)
     * @param int|null $userId The ID of the user who performed the action (defaults to authenticated user)
     * @return \App\Models\Log The created log entry
     */
    public static function log(string $action, ?string $modelType = null, ?int $modelId = null, ?int $userId = null)
    {
        $userId = $userId ?? Auth::id() ?? null;

        return Log::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId
        ]);
    }

    /**
     * Log a template change action.
     *
     * @param int $templateId The ID of the template
     * @param int $versionId The ID of the new version
     * @param string $action The action that was performed (e.g. 'updated', 'created', 'rolled back')
     * @return \App\Models\Log
     */
    public static function logTemplateChange(int $templateId, int $versionId, string $action = 'updated')
    {
        return self::log(
            "Template $action (Version #$versionId)",
            'Template',
            $templateId
        );
    }

    /**
     * Log a document type change action.
     *
     * @param int $documentTypeId The ID of the document type
     * @param int $versionId The ID of the new version
     * @param string $action The action that was performed (e.g. 'updated', 'created', 'rolled back')
     * @return \App\Models\Log
     */
    public static function logDocumentTypeChange(int $documentTypeId, int $versionId, string $action = 'updated')
    {
        return self::log(
            "Document Type $action (Version #$versionId)",
            'DocumentType',
            $documentTypeId
        );
    }

    /**
     * Log a number format change action.
     *
     * @param int $numberFormatId The ID of the number format
     * @param int $versionId The ID of the new version
     * @param string $action The action that was performed (e.g. 'updated', 'created', 'rolled back')
     * @return \App\Models\Log
     */
    public static function logNumberFormatChange(int $numberFormatId, int $versionId, string $action = 'updated')
    {
        return self::log(
            "Number Format $action (Version #$versionId)",
            'NumberFormat',
            $numberFormatId
        );
    }

    /**
     * Log a document status change.
     *
     * @param int $documentId The ID of the document
     * @param string $status The new status of the document
     * @param string|null $notes Additional notes about the status change
     * @return \App\Models\Log
     */
    public static function logDocumentStatusChange(int $documentId, string $status, ?string $notes = null)
    {
        $action = "Document status changed to '$status'";
        if ($notes) {
            $action .= " - Notes: $notes";
        }

        return self::log($action, 'Document', $documentId);
    }

    /**
     * Log a user login.
     *
     * @param int $userId The ID of the user who logged in
     * @return \App\Models\Log
     */
    public static function logLogin(int $userId)
    {
        return self::log('User logged in', 'User', $userId, $userId);
    }

    /**
     * Log a user logout.
     *
     * @return \App\Models\Log|null
     */
    public static function logLogout()
    {
        $userId = Auth::id();
        if ($userId) {
            return self::log('User logged out', 'User', $userId, $userId);
        }

        return null;
    }
}