<?php

namespace App\Actions\Document;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateDocumentRequest
{
    public function handle(array $data)
    {
        // Validate NIK format (16 digits with specific structure)
        $validator = Validator::make($data, [
            'nik' => [
                'required',
                'digits:16',
                'regex:/^[0-9]{6}[0-1][0-9]{1}[0-3][0-9]{1}[0-9]{4}$/',
            ],
            'kk' => ['required', 'digits:16'],
            'document_type_id' => ['required', 'exists:document_types,id'],
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        // Check for duplicate active submissions
        $existingDocument = Document::where('nik', $data['nik'])
            ->whereIn('status', ['pending', 'processing'])
            ->first();

        if ($existingDocument) {
            throw new \InvalidArgumentException(
                'There is already an active document request with this NIK. ' .
                'Please wait for the current request to be processed.'
            );
        }

        // Extract document data (excluding system fields)
        $documentData = collect($data)
            ->except(['_token', 'document_type_id'])
            ->toArray();

        // Create document request
        $document = Document::create([
            'client_id' => Auth::id(),
            'type_id' => $data['document_type_id'],
            'nik' => $data['nik'],
            'kk' => $data['kk'],
            'data_json' => $documentData,
            'status' => 'pending'
        ]);

        // Create notification for admins about new submission
        \App\Services\NotificationService::notifyAdminAboutNewDocument($document);

        // Log document creation
        \App\Services\LoggingService::log(
            'Document Requested',
            'Document',
            $document->id
        );

        return $document;
    }
}
