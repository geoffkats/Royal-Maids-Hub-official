<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'crm_sources';

    protected $fillable = [
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
