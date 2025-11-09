<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ticket Categories
    |--------------------------------------------------------------------------
    |
    | Define the available ticket categories. Pre-sales categories are used
    | to identify tickets from leads before they become clients.
    |
    */

    'categories' => [
        // Pre-Sales Categories
        'Inquiry' => 'Inquiry',
        'Quote Request' => 'Quote Request',
        'Pre-Sales Support' => 'Pre-Sales Support',
        
        // Service Categories
        'Service Quality' => 'Service Quality',
        'Maid Absence' => 'Maid Absence',
        'Maid Performance' => 'Maid Performance',
        'Maid Request' => 'Maid Request',
        'Rescheduling' => 'Rescheduling',
        'Cancellation' => 'Cancellation',
        
        // Billing Categories
        'Payment Issue' => 'Payment Issue',
        'Billing Error' => 'Billing Error',
        'Refund Request' => 'Refund Request',
        'Invoice Request' => 'Invoice Request',
        
        // Technical Categories
        'Technical Issue' => 'Technical Issue',
        'Account Access' => 'Account Access',
        'App Problem' => 'App Problem',
        
        // Critical Categories
        'Safety Concern' => 'Safety Concern',
        'Legal Issue' => 'Legal Issue',
        'Emergency' => 'Emergency',
        'Harassment' => 'Harassment',
        
        // General
        'Feedback' => 'Feedback',
        'Complaint' => 'Complaint',
        'Other' => 'Other',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pre-Sales Categories
    |--------------------------------------------------------------------------
    |
    | Categories that indicate pre-sales tickets (from leads)
    |
    */

    'pre_sales_categories' => [
        'Inquiry',
        'Quote Request',
        'Pre-Sales Support',
    ],

    /*
    |--------------------------------------------------------------------------
    | Critical Categories
    |--------------------------------------------------------------------------
    |
    | Categories that are automatically marked as critical/urgent
    |
    */

    'critical_categories' => [
        'Safety Concern',
        'Legal Issue',
        'Emergency',
        'Harassment',
    ],
];
