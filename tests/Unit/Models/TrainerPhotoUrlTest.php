<?php

use App\Models\Trainer;
use App\Models\User;

it('returns ui-avatars url when no photo_path', function () {
    $user = new User(['name' => 'Jane Doe']);
    $trainer = new Trainer(['photo_path' => null]);
    $trainer->setRelation('user', $user);

    expect($trainer->photo_url)
        ->toContain('ui-avatars.com')
        ->toContain('Jane+Doe');
});

// Note: Storage facade requires application bootstrapping; a more
// comprehensive feature test can cover stored file URL resolution.
