<?php

namespace Tests\Unit;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $client;
    protected $admin;
    protected $document;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = User::factory()->create(['role' => 'client']);
        $this->admin = User::factory()->create(['role' => 'admin']);

        $documentType = DocumentType::create([
            'name' => 'Test Type',
            'is_active' => true,
        ]);

        $this->document = Document::create([
            'client_id' => $this->client->id,
            'type_id' => $documentType->id,
            'status' => 'pending',
            'nik' => '1234567890123456',
            'kk' => '1234567890123456',
            'data_json' => json_encode(['name' => 'Test User']),
        ]);
    }

    /** @test */
    public function it_creates_a_notification_for_a_user()
    {
        // Arrange
        $message = 'Test notification message';

        // Act
        $notification = NotificationService::notify($this->client->id, $message);

        // Assert
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals($this->client->id, $notification->user_id);
        $this->assertEquals($message, $notification->message);
        $this->assertFalse($notification->is_read);
    }

    /** @test */
    public function it_creates_a_document_status_change_notification()
    {
        // Act
        $notification = NotificationService::notifyDocumentStatusChange($this->document);

        // Assert
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals($this->client->id, $notification->user_id);
        $this->assertStringContainsString('has been marked as Pending', $notification->message);
    }

    /** @test */
    public function it_creates_document_status_change_notification_with_notes()
    {
        // Arrange
        $notes = 'Test rejection reason';

        // Act
        $notification = NotificationService::notifyDocumentStatusChange($this->document, $notes);

        // Assert
        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertEquals($this->client->id, $notification->user_id);
        $this->assertStringContainsString($notes, $notification->message);
    }

    /** @test */
    public function it_notifies_all_admins_about_new_document()
    {
        // Arrange
        $admin2 = User::factory()->create(['role' => 'admin']);
        $this->assertEquals(2, User::where('role', 'admin')->count());

        // Act
        NotificationService::notifyAdminAboutNewDocument($this->document);

        // Assert
        $this->assertEquals(2, Notification::count());
        $this->assertDatabaseHas('notifications', ['user_id' => $this->admin->id]);
        $this->assertDatabaseHas('notifications', ['user_id' => $admin2->id]);
    }

    /** @test */
    public function it_marks_all_notifications_as_read()
    {
        // Arrange
        NotificationService::notify($this->client->id, 'Test 1');
        NotificationService::notify($this->client->id, 'Test 2');
        NotificationService::notify($this->client->id, 'Test 3');
        $this->assertEquals(3, Notification::where('user_id', $this->client->id)->count());
        $this->assertEquals(3, Notification::where('user_id', $this->client->id)->where('is_read', false)->count());

        // Act
        $updatedCount = NotificationService::markAllAsRead($this->client->id);

        // Assert
        $this->assertEquals(3, $updatedCount);
        $this->assertEquals(0, Notification::where('user_id', $this->client->id)->where('is_read', false)->count());
        $this->assertEquals(3, Notification::where('user_id', $this->client->id)->where('is_read', true)->count());
    }
}
