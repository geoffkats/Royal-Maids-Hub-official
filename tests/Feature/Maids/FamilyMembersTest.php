<?php

use App\Models\Maid;

test('stores additional family members on maids', function () {
    $maid = Maid::factory()->create([
        'family_members' => [
            [
                'name' => 'Jane Relative',
                'relationship' => 'Sister',
                'phone' => '0700000000',
            ],
        ],
    ]);

    $maid->refresh();

    expect($maid->family_members[0]['name'])->toBe('Jane Relative');
});
