<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $table = 'crm_tags';

    protected $fillable = [
        'name',
        'color',
        'description',
    ];

    // Relationships
    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'crm_lead_tag', 'tag_id', 'lead_id');
    }

    public function opportunities(): BelongsToMany
    {
        return $this->belongsToMany(Opportunity::class, 'crm_opportunity_tag', 'tag_id', 'opportunity_id');
    }
}
