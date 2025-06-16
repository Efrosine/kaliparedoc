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

            // Generate document number using the GenerateDocumentNumber action
            $generateNumber = new GenerateDocumentNumber();
            $document->number = $generateNumber->handle($document);

            $document->save();

            // Create notification for the client
            Notification::create([
                'user_id' => $document->client_id,
                'message' => "Your document request ({$document->documentType->name}) has been approved. Document number: {$document->number}",
                'is_read' => false
            ]);

            // Log this action
            Log::create([
                'user_id' => Auth::id(),
                'action' => 'Document Approved',
                'model_type' => 'Document',
                'model_id' => $document->id
            ]);

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
            Notification::create([
                'user_id' => $document->client_id,
                'message' => "Your document request ({$document->documentType->name}) has been rejected. Reason: {$reason}",
                'is_read' => false
            ]);

            // Log this action
            Log::create([
                'user_id' => Auth::id(),
                'action' => 'Document Rejected',
                'model_type' => 'Document',
                'model_id' => $document->id,
                'metadata' => json_encode(['reason' => $reason])
            ]);

            DB::commit();
            return $document;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
