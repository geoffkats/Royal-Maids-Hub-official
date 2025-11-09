<?php

return [
    'weights' => [
        // Profile completeness
        'has_email' => 10,
        'has_phone' => 10,
        'has_company' => 5,
        'interested_package' => 15,

        // Engagement
        'activity_recent' => 5, // per recent activity (<= 7 days), max applied via caps
        'activity_recent_cap' => 25,

        // Recency
        'last_contact_3_days' => 20,
        'last_contact_7_days' => 10,

        // Source multipliers/bonuses
        'source_bonus' => [
            'Referral' => 20,
            'Website' => 10,
            'Ad' => 5,
            'Event' => 8,
        ],

        // Status adjustments
        'status_penalty' => [
            'disqualified' => -100,
        ],

        // Caps
        'max_score' => 100,
        'min_score' => 0,
    ],
];
