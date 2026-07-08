<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Opd;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_edit_ticket_page_with_categories_dropdown_data(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create(['name' => 'Infrastruktur dan Pekerjaan Umum']);
        $subCategory = SubCategory::create([
            'name' => 'Lampu Jalan',
            'category_id' => $category->id,
        ]);

        $notification = Notification::create([
            'title' => 'Facebook Mention',
            'sender' => 'Sender',
            'message' => 'Lampu jalan mati',
            'permalink' => 'https://facebook.com/123',
            'is_read' => true,
        ]);

        $ticket = Ticket::create([
            'notification_id' => $notification->id,
            'ticket_number' => '20260624-000000-X-001',
            'ticket_time' => now(),
            'platform' => 'Facebook',
            'reporter_name' => 'Reporter',
            'reporter_link' => 'https://facebook.com/rep',
            'category' => 'Infrastruktur dan Pekerjaan Umum',
            'sub_category' => 'Lampu Jalan',
            'opd_related' => 'Dinas Perhubungan',
            'complaint' => 'Lampu jalan mati',
            'priority' => 'sedang',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('tickets.edit', $ticket->id));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
        $response->assertSee('Infrastruktur dan Pekerjaan Umum');
    }

    public function test_admin_can_update_ticket_category_and_subcategory(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category1 = Category::create(['name' => 'Infrastruktur dan Pekerjaan Umum']);
        $category2 = Category::create(['name' => 'Kesehatan']);
        
        $subCategory1 = SubCategory::create([
            'name' => 'Lampu Jalan',
            'category_id' => $category1->id,
        ]);
        $subCategory2 = SubCategory::create([
            'name' => 'Rumah Sakit',
            'category_id' => $category2->id,
        ]);

        $notification = Notification::create([
            'title' => 'Facebook Mention',
            'sender' => 'Sender',
            'message' => 'Lampu jalan mati',
            'permalink' => 'https://facebook.com/123',
            'is_read' => true,
        ]);

        $ticket = Ticket::create([
            'notification_id' => $notification->id,
            'ticket_number' => '20260624-000000-X-001',
            'ticket_time' => now(),
            'platform' => 'Facebook',
            'reporter_name' => 'Reporter',
            'reporter_link' => 'https://facebook.com/rep',
            'category' => 'Infrastruktur dan Pekerjaan Umum',
            'sub_category' => 'Lampu Jalan',
            'opd_related' => 'Dinas Perhubungan',
            'complaint' => 'Lampu jalan mati',
            'priority' => 'sedang',
        ]);

        $opd = Opd::create(['name' => 'RSUD Agoesdjam']);

        $response = $this->actingAs($admin)
            ->put(route('tickets.update', $ticket->id), [
                'category' => 'Kesehatan',
                'sub_category' => 'Rumah Sakit',
                'opd_related' => 'RSUD Agoesdjam',
                'priority' => 'tinggi',
            ]);

        $response->assertStatus(302);
        
        $ticket->refresh();
        $this->assertEquals('Kesehatan', $ticket->category);
        $this->assertEquals('Rumah Sakit', $ticket->sub_category);
        $this->assertEquals('RSUD Agoesdjam', $ticket->opd_related);
        $this->assertEquals('tinggi', $ticket->priority);
        $this->assertEquals($opd->id, $ticket->assigned_opd_id);
    }

    public function test_admin_can_view_create_ticket_page_without_notification(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create(['name' => 'Infrastruktur dan Pekerjaan Umum']);
        $subCategory = SubCategory::create([
            'name' => 'Lampu Jalan',
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('tickets.create'));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
        $response->assertSee('Buat Tiket');
    }

    public function test_admin_can_store_manual_ticket(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create(['name' => 'Kesehatan']);
        $subCategory = SubCategory::create([
            'name' => 'Rumah Sakit',
            'category_id' => $category->id,
        ]);
        $opd = Opd::create(['name' => 'RSUD Agoesdjam']);

        $response = $this->actingAs($admin)
            ->post(route('tickets.store'), [
                'platform' => 'WhatsApp',
                'reporter_name' => 'Manual Reporter',
                'reporter_link' => '08123456789',
                'category' => 'Kesehatan',
                'sub_category' => 'Rumah Sakit',
                'opd_related' => 'RSUD Agoesdjam',
                'priority' => 'tinggi',
                'complaint' => 'Isi aduan manual kesehatan',
            ]);

        $response->assertStatus(302);
        
        $ticket = Ticket::where('reporter_name', 'Manual Reporter')->first();
        $this->assertNotNull($ticket);
        $this->assertNull($ticket->notification_id);
        $this->assertEquals('WhatsApp', $ticket->platform);
        $this->assertEquals('08123456789', $ticket->reporter_link);
        $this->assertEquals('Kesehatan', $ticket->category);
        $this->assertEquals('Rumah Sakit', $ticket->sub_category);
        $this->assertEquals('RSUD Agoesdjam', $ticket->opd_related);
        $this->assertEquals($opd->id, $ticket->assigned_opd_id);
        $this->assertEquals('tinggi', $ticket->priority);
        $this->assertEquals('Isi aduan manual kesehatan', $ticket->complaint);
    }
}
