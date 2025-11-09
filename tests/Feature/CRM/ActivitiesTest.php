<?php

use App\Models\User;
use App\Models\CRM\Lead;
use App\Models\CRM\Activity;
use Livewire\Livewire;
use App\Livewire\CRM\Activities\Index;
use App\Livewire\CRM\Activities\Create;
use App\Livewire\CRM\Activities\Show;
use App\Livewire\CRM\Activities\Edit;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->user);
});

test('admin can view activities index', function () {
    $response = $this->get('/crm/activities');
    $response->assertStatus(200);
    $response->assertSee('CRM Activities');
});

test('admin can create a new activity', function () {
    $lead = Lead::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'job_title' => 'Manager',
        'notes' => 'Test lead notes',
        'status' => 'new',
        'estimated_value' => 1000,
        'source' => 'website',
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    Livewire::test(Create::class)
        ->set('type', 'call')
        ->set('subject', 'Follow up call')
        ->set('description', 'Call to discuss requirements')
        ->set('due_date', '2024-12-31 10:00:00')
        ->set('status', 'pending')
        ->set('priority', 'high')
        ->set('related_type', 'App\Models\CRM\Lead')
        ->set('related_id', $lead->id)
        ->set('assigned_to', $this->user->id)
        ->call('save')
        ->assertRedirect('/crm/activities');

    $this->assertDatabaseHas('crm_activities', [
        'type' => 'call',
        'subject' => 'Follow up call',
        'status' => 'pending',
        'priority' => 'high',
    ]);
});

test('admin can view activity details', function () {
    $lead = Lead::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'job_title' => 'Manager',
        'notes' => 'Test lead notes',
        'status' => 'new',
        'estimated_value' => 1000,
        'source' => 'website',
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    $activity = Activity::create([
        'type' => 'call',
        'subject' => 'Follow up call',
        'description' => 'Call to discuss requirements',
        'due_date' => '2024-12-31 10:00:00',
        'status' => 'pending',
        'priority' => 'high',
        'related_type' => 'App\Models\CRM\Lead',
        'related_id' => $lead->id,
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    $response = $this->get("/crm/activities/{$activity->id}");
    $response->assertStatus(200);
    $response->assertSee('Follow up call');
});

test('admin can edit an activity', function () {
    $lead = Lead::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'job_title' => 'Manager',
        'notes' => 'Test lead notes',
        'status' => 'new',
        'estimated_value' => 1000,
        'source' => 'website',
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    $activity = Activity::create([
        'type' => 'call',
        'subject' => 'Follow up call',
        'description' => 'Call to discuss requirements',
        'due_date' => '2024-12-31 10:00:00',
        'status' => 'pending',
        'priority' => 'high',
        'related_type' => 'App\Models\CRM\Lead',
        'related_id' => $lead->id,
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    Livewire::test(Edit::class, ['activity' => $activity])
        ->set('subject', 'Updated call')
        ->set('status', 'completed')
        ->set('completed_at', now())
        ->call('save')
        ->assertRedirect("/crm/activities/{$activity->id}");

    $this->assertDatabaseHas('crm_activities', [
        'id' => $activity->id,
        'subject' => 'Updated call',
        'status' => 'completed',
    ]);
});

test('activity completion status works', function () {
    $lead = Lead::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'job_title' => 'Manager',
        'notes' => 'Test lead notes',
        'status' => 'new',
        'estimated_value' => 1000,
        'source' => 'website',
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    $activity = Activity::create([
        'type' => 'call',
        'subject' => 'Follow up call',
        'description' => 'Call to discuss requirements',
        'due_date' => '2024-12-31 10:00:00',
        'status' => 'completed',
        'priority' => 'high',
        'related_type' => 'App\Models\CRM\Lead',
        'related_id' => $lead->id,
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    expect($activity->isCompleted())->toBeTrue();
});

test('activity overdue detection works', function () {
    $lead = Lead::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'job_title' => 'Manager',
        'notes' => 'Test lead notes',
        'status' => 'new',
        'estimated_value' => 1000,
        'source' => 'website',
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    $activity = Activity::create([
        'type' => 'call',
        'subject' => 'Follow up call',
        'description' => 'Call to discuss requirements',
        'due_date' => now()->subDay(), // Past due
        'status' => 'pending',
        'priority' => 'high',
        'related_type' => 'App\Models\CRM\Lead',
        'related_id' => $lead->id,
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    expect($activity->isOverdue())->toBeTrue();
});

test('admin can search activities', function () {
    $lead = Lead::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'job_title' => 'Manager',
        'notes' => 'Test lead notes',
        'status' => 'new',
        'estimated_value' => 1000,
        'source' => 'website',
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    Activity::create([
        'type' => 'call',
        'subject' => 'Important call',
        'description' => 'Call to discuss requirements',
        'due_date' => '2024-12-31 10:00:00',
        'status' => 'pending',
        'priority' => 'high',
        'related_type' => 'App\Models\CRM\Lead',
        'related_id' => $lead->id,
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    Activity::create([
        'type' => 'email',
        'subject' => 'Regular email',
        'description' => 'Send follow up email',
        'due_date' => '2024-12-31 10:00:00',
        'status' => 'pending',
        'priority' => 'medium',
        'related_type' => 'App\Models\CRM\Lead',
        'related_id' => $lead->id,
        'assigned_to' => $this->user->id,
        'created_by' => $this->user->id,
    ]);

    Livewire::test(Index::class)
        ->set('search', 'Important')
        ->assertSee('Important call')
        ->assertDontSee('Regular email');
});

test('activity validation works correctly', function () {
    Livewire::test(Create::class)
        ->set('type', '')
        ->set('subject', '')
        ->set('priority', 'invalid')
        ->call('save')
        ->assertHasErrors(['type', 'subject', 'priority']);
});
