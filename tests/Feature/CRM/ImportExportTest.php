<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('exports leads as xlsx', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    $this->actingAs($admin);

    $response = $this->get(route('crm.leads.export'));
    $response->assertOk();
    $response->assertHeader('content-disposition');
    expect($response->headers->get('content-disposition'))->toContain('.xlsx');
});

it('exports opportunities as xlsx', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    $this->actingAs($admin);

    $response = $this->get(route('crm.opportunities.export'));
    $response->assertOk();
    $response->assertHeader('content-disposition');
    expect($response->headers->get('content-disposition'))->toContain('.xlsx');
});

it('exports activities as xlsx', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    $this->actingAs($admin);

    $response = $this->get(route('crm.activities.export'));
    $response->assertOk();
    $response->assertHeader('content-disposition');
    expect($response->headers->get('content-disposition'))->toContain('.xlsx');
});

it('imports leads from a minimal csv', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    $this->actingAs($admin);

    Storage::fake('local');
    $csvContent = "first_name,last_name,email\nAlice,Wonder,alice@example.com\n";
    $file = UploadedFile::fake()->createWithContent('leads.csv', $csvContent);

    $response = $this->post(route('crm.leads.import'), [
        'file' => $file,
    ]);

    $response->assertStatus(302);
    $response->assertSessionHas('message');
});

it('imports opportunities from a minimal csv', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    $this->actingAs($admin);

    Storage::fake('local');
    $csvContent = "title,amount,status\nTest Opp,0,open\n";
    $file = UploadedFile::fake()->createWithContent('opportunities.csv', $csvContent);

    $response = $this->post(route('crm.opportunities.import'), [
        'file' => $file,
    ]);

    $response->assertStatus(302);
    $response->assertSessionHas('message');
});
