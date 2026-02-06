<?php

test('admin dashboard shows the financial overview section', function (): void {
    $admin = \App\Models\User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertSuccessful()
        ->assertSee('Financial Overview')
        ->assertSeeLivewire(\App\Livewire\Dashboard\FinancialSummary::class);
});
