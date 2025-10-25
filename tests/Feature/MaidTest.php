<?php

namespace Tests\Feature;

use App\Models\Maid;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MaidTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->admin = User::factory()->create(['email' => 'admin@test.com']);
        $this->admin->assignRole('admin');
        
        $this->trainer = User::factory()->create(['email' => 'trainer@test.com']);
        $this->trainer->assignRole('trainer');
        
        $this->client = User::factory()->create(['email' => 'client@test.com']);
        $this->client->assignRole('client');
    }

    /** @test */
    public function admin_can_view_maids_index()
    {
        $response = $this->actingAs($this->admin)->get('/maids');
        $response->assertStatus(200);
        $response->assertSee('Maids Management');
    }

    /** @test */
    public function trainer_can_view_maids_index()
    {
        $response = $this->actingAs($this->trainer)->get('/maids');
        $response->assertStatus(200);
    }

    /** @test */
    public function client_can_view_maids_index()
    {
        $response = $this->actingAs($this->client)->get('/maids');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_maid()
    {
        $maidData = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '0771234567',
            'date_of_birth' => '1990-01-01',
            'nin' => '1234567890123456',
            'nationality' => 'Ugandan',
            'marital_status' => 'single',
            'number_of_children' => 0,
            'tribe' => 'Baganda',
            'village' => 'Kampala',
            'district' => 'Kampala',
            'lc1_chairperson' => 'John Smith',
            'mother_name_phone' => 'Mary Doe - 0771111111',
            'father_name_phone' => 'John Doe - 0772222222',
            'education_level' => 'secondary',
            'experience_years' => 2,
            'mother_tongue' => 'Luganda',
            'english_proficiency' => 'good',
            'previous_work' => 'Housekeeper at Hotel',
            'role' => 'housekeeper',
            'status' => 'available',
            'secondary_status' => 'ready',
            'work_status' => 'full-time',
            'hepatitis_b_result' => 'negative',
            'hiv_result' => 'negative',
            'urine_hcg_result' => 'negative',
            'medical_notes' => 'Healthy',
            'additional_notes' => 'Good worker',
        ];

        $response = $this->actingAs($this->admin)->post('/maids', $maidData);
        
        $this->assertDatabaseHas('maids', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '0771234567',
        ]);
    }

    /** @test */
    public function admin_can_view_maid_details()
    {
        $maid = Maid::factory()->create();
        
        $response = $this->actingAs($this->admin)->get("/maids/{$maid->id}");
        $response->assertStatus(200);
        $response->assertSee($maid->full_name);
    }

    /** @test */
    public function admin_can_edit_maid()
    {
        $maid = Maid::factory()->create();
        
        $response = $this->actingAs($this->admin)->get("/maids/{$maid->id}/edit");
        $response->assertStatus(200);
        $response->assertSee($maid->full_name);
    }

    /** @test */
    public function admin_can_delete_maid()
    {
        $maid = Maid::factory()->create();
        
        $response = $this->actingAs($this->admin)->delete("/maids/{$maid->id}");
        
        $this->assertDatabaseMissing('maids', ['id' => $maid->id]);
    }

    /** @test */
    public function trainer_cannot_create_maid()
    {
        $response = $this->actingAs($this->trainer)->get('/maids/create');
        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_create_maid()
    {
        $response = $this->actingAs($this->client)->get('/maids/create');
        $response->assertStatus(403);
    }

    /** @test */
    public function trainer_cannot_edit_maid()
    {
        $maid = Maid::factory()->create();
        
        $response = $this->actingAs($this->trainer)->get("/maids/{$maid->id}/edit");
        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_edit_maid()
    {
        $maid = Maid::factory()->create();
        
        $response = $this->actingAs($this->client)->get("/maids/{$maid->id}/edit");
        $response->assertStatus(403);
    }

    /** @test */
    public function trainer_can_view_maid_in_training()
    {
        $maid = Maid::factory()->create(['status' => 'in-training']);
        
        $response = $this->actingAs($this->trainer)->get("/maids/{$maid->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function trainer_cannot_view_available_maid()
    {
        $maid = Maid::factory()->create(['status' => 'available']);
        
        $response = $this->actingAs($this->trainer)->get("/maids/{$maid->id}");
        $response->assertStatus(403);
    }

    /** @test */
    public function client_can_view_available_maid()
    {
        $maid = Maid::factory()->create(['status' => 'available']);
        
        $response = $this->actingAs($this->client)->get("/maids/{$maid->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function client_cannot_view_maid_in_training()
    {
        $maid = Maid::factory()->create(['status' => 'in-training']);
        
        $response = $this->actingAs($this->client)->get("/maids/{$maid->id}");
        $response->assertStatus(403);
    }

    /** @test */
    public function maid_creation_requires_validation()
    {
        $response = $this->actingAs($this->admin)->post('/maids', []);
        
        $response->assertSessionHasErrors(['first_name', 'last_name', 'phone']);
    }

    /** @test */
    public function maid_can_have_profile_image()
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('profile.jpg');
        
        $maidData = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '0771234567',
            'profile_image' => $file,
        ];

        $response = $this->actingAs($this->admin)->post('/maids', $maidData);
        
        $maid = Maid::where('first_name', 'Jane')->first();
        $this->assertNotNull($maid->profile_image);
        Storage::disk('public')->assertExists($maid->profile_image);
    }

    /** @test */
    public function maid_can_have_additional_documents()
    {
        Storage::fake('public');
        
        $files = [
            UploadedFile::fake()->create('document1.pdf'),
            UploadedFile::fake()->create('document2.pdf'),
        ];

        $maidData = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'phone' => '0771234567',
            'additional_documents' => $files,
        ];

        $response = $this->actingAs($this->admin)->post('/maids', $maidData);
        
        $maid = Maid::where('first_name', 'Jane')->first();
        $this->assertCount(2, $maid->additional_documents);
    }

    /** @test */
    public function maid_search_works()
    {
        Maid::factory()->create(['first_name' => 'Jane', 'last_name' => 'Doe']);
        Maid::factory()->create(['first_name' => 'John', 'last_name' => 'Smith']);

        $response = $this->actingAs($this->admin)->get('/maids?search=Jane');
        
        $response->assertSee('Jane Doe');
        $response->assertDontSee('John Smith');
    }

    /** @test */
    public function maid_status_filter_works()
    {
        Maid::factory()->create(['status' => 'available']);
        Maid::factory()->create(['status' => 'in-training']);

        $response = $this->actingAs($this->admin)->get('/maids?status=available');
        
        $response->assertSee('available');
        $response->assertDontSee('in-training');
    }

    /** @test */
    public function maid_role_filter_works()
    {
        Maid::factory()->create(['role' => 'housekeeper']);
        Maid::factory()->create(['role' => 'nanny']);

        $response = $this->actingAs($this->admin)->get('/maids?role=housekeeper');
        
        $response->assertSee('housekeeper');
        $response->assertDontSee('nanny');
    }
}