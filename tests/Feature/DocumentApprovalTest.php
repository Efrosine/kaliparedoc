<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Notification;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $admin;
    protected $client;
    protected $documentType;
    protected $template;

    public function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->client = User::factory()->create(['role' => 'client']);

        // Create document type
        $this->documentType = DocumentType::create([
            'name' => 'Test Document',
            'is_active' => true,
        ]);

        // Create template
        $this->template = Template::create([
            'document_type_id' => $this->documentType->id,
        ]);
    }

    /** @test */
    public function admin_can_approve_document_and_notification_is_sent()
    {
        // Create a document submission
        $document = Document::create([
            'client_id' => $this->client->id,
            'type_id' => $this->documentType->id,
            'status' => 'pending',
            'nik' => '1234567890123456',
            'kk' => '1234567890123456',
            'data_json' => json_encode(['name' => 'Test User', 'address' => 'Test Address']),
        ]);

        // Act as admin and approve the document
        $this->actingAs($this->admin)
            ->post(route('admin.documents.approve', $document))
            ->assertRedirect(route('admin.documents.index'))
            ->assertSessionHas('success');

        // Assert document was updated
        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'status' => 'completed',
            'admin_id' => $this->admin->id,
        ]);

        // Assert that a notification was created for the client
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->client->id,
            'is_read' => false,
        ]);

        // Assert that an activity log was created
        $this->assertDatabaseHas('logs', [
            'user_id' => $this->admin->id,
            'model_type' => 'Document',
            'model_id' => $document->id,
        ]);
    }

    /** @test */
    public function admin_can_reject_document_and_notification_is_sent()
    {
        // Create a document submission
        $document = Document::create([
            'client_id' => $this->client->id,
            'type_id' => $this->documentType->id,
            'status' => 'pending',
            'nik' => '1234567890123456',
            'kk' => '1234567890123456',
            'data_json' => json_encode(['name' => 'Test User', 'address' => 'Test Address']),
        ]);

        // Act as admin and reject the document
        $this->actingAs($this->admin)
            ->post(route('admin.documents.reject', $document), [
                'reason' => 'Test rejection reason'
            ])
            ->assertRedirect(route('admin.documents.index'))
            ->assertSessionHas('success');

        // Assert document was updated
        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'status' => 'rejected',
            'admin_id' => $this->admin->id,
        ]);

        // Assert that a notification was created for the client
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->client->id,
            'is_read' => false,
        ]);

        // Get notification and check if it contains the rejection reason
        $notification = Notification::where('user_id', $this->client->id)->latest()->first();
        $this->assertStringContainsString('rejected', $notification->message);
        $this->assertStringContainsString('Test rejection reason', $notification->message);

        // Assert that an activity log was created
        $this->assertDatabaseHas('logs', [
            'user_id' => $this->admin->id,
            'model_type' => 'Document',
            'model_id' => $document->id,
        ]);
    }
}
