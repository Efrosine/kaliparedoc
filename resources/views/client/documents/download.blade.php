<x-layouts.app>
    <x-slot name="header">
        Download Document
    </x-slot>

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('client.documents.show', $document) }}" class="btn btn-secondary">Back to Document Details</a>
    </div>

    @include('components.alert')

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Document Preview</h5>
                </div>
                <div class="card-body p-0">
                    <iframe src="{{ route('client.documents.download', $document) }}" width="100%" height="600px"
                        style="border: none;"></iframe>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Document Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Document Type</th>
                            <td>{{ $document->documentType->name }}</td>
                        </tr>
                        <tr>
                            <th>Document Number</th>
                            <td><strong>{{ $document->number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Approval Date</th>
                            <td>{{ $document->updated_at->format('d F Y') }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('client.documents.download', $document) }}" class="btn btn-primary btn-block"
                            download>
                            <i class="fas fa-download mr-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Document Usage Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>This document is officially issued by the village administration.</li>
                        <li>Each document has a unique number for verification.</li>
                        <li>Keep the document in a safe place.</li>
                        <li>Print on A4 paper for official use.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>