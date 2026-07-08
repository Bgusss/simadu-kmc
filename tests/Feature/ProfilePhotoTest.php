<?php

namespace Tests\Feature;

use App\Models\Opd;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can upload a PNG profile photo and old photo is deleted.
     */
    public function test_admin_can_upload_png_profile_photo_and_old_photo_is_deleted(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'profile_photo' => null,
        ]);

        $firstPhoto = UploadedFile::fake()->image('avatar1.png');

        $response = $this->actingAs($user)
            ->post(route('admin.profile.update'), [
                'username' => 'admin_test',
                'email' => 'admin@test.com',
                'profile_photo' => $firstPhoto,
            ]);

        $response->assertStatus(302);
        $user->refresh();

        $this->assertNotNull($user->profile_photo);
        Storage::disk('public')->assertExists($user->profile_photo);

        $oldPhotoPath = $user->profile_photo;

        // Upload second photo
        $secondPhoto = UploadedFile::fake()->image('avatar2.png');

        $response2 = $this->actingAs($user)
            ->post(route('admin.profile.update'), [
                'username' => 'admin_test',
                'email' => 'admin@test.com',
                'profile_photo' => $secondPhoto,
            ]);

        $response2->assertStatus(302);
        $user->refresh();

        $this->assertNotNull($user->profile_photo);
        $this->assertNotEquals($oldPhotoPath, $user->profile_photo);

        // Verify old photo was deleted
        Storage::disk('public')->assertMissing($oldPhotoPath);
        // Verify new photo exists
        Storage::disk('public')->assertExists($user->profile_photo);
    }

    /**
     * Test OPD can upload a PNG profile photo and old photo is deleted.
     */
    public function test_opd_can_upload_png_profile_photo_and_old_photo_is_deleted(): void
    {
        Storage::fake('public');

        $opd = Opd::create(['name' => 'Dinas Test']);

        $user = User::factory()->create([
            'name' => 'OPD Test User',
            'username' => 'opd_test',
            'email' => 'opd@test.com',
            'role' => 'opd',
            'opd_id' => $opd->id,
            'profile_photo' => null,
        ]);

        $firstPhoto = UploadedFile::fake()->image('avatar_opd1.png');

        $response = $this->actingAs($user)
            ->post(route('opd.profile.update'), [
                'name' => 'OPD Test User Updated',
                'email' => 'opd@test.com',
                'profile_photo' => $firstPhoto,
            ]);

        $response->assertStatus(302);
        $user->refresh();

        $this->assertNotNull($user->profile_photo);
        Storage::disk('public')->assertExists($user->profile_photo);

        $oldPhotoPath = $user->profile_photo;

        // Upload second photo
        $secondPhoto = UploadedFile::fake()->image('avatar_opd2.png');

        $response2 = $this->actingAs($user)
            ->post(route('opd.profile.update'), [
                'name' => 'OPD Test User Updated',
                'email' => 'opd@test.com',
                'profile_photo' => $secondPhoto,
            ]);

        $response2->assertStatus(302);
        $user->refresh();

        $this->assertNotNull($user->profile_photo);
        $this->assertNotEquals($oldPhotoPath, $user->profile_photo);

        // Verify old photo was deleted
        Storage::disk('public')->assertMissing($oldPhotoPath);
        // Verify new photo exists
        Storage::disk('public')->assertExists($user->profile_photo);
    }
}
