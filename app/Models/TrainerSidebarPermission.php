<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerSidebarPermission extends Model
{
    protected $fillable = ['trainer_id', 'sidebar_item', 'granted_at'];

    protected $casts = [
        'sidebar_item' => 'string',
        'granted_at' => 'datetime',
    ];

    // Sidebar item constants
    // Management
    public const ITEM_MAIDS = 'maids';
    public const ITEM_TRAINERS = 'trainers';
    public const ITEM_CLIENTS = 'clients';
    public const ITEM_BOOKINGS = 'bookings';
    // Training & Development
    public const ITEM_MY_PROGRAMS = 'my_programs';
    public const ITEM_MY_EVALUATIONS = 'my_evaluations';
    public const ITEM_DEPLOYMENTS = 'deployments';
    public const ITEM_TRAINER_PERMISSIONS = 'trainer_permissions';
    // Analytics & Reports
    public const ITEM_REPORTS = 'reports';
    public const ITEM_KPI_DASHBOARD = 'kpi_dashboard';
    public const ITEM_WEEKLY_BOARDS_REVIEW = 'weekly_boards_review';
    // Support & Tickets
    public const ITEM_CONTACT_INQUIRIES = 'contact_inquiries';
    public const ITEM_TICKETS = 'tickets';
    public const ITEM_TICKETS_INBOX = 'tickets_inbox';
    public const ITEM_TICKET_ANALYTICS = 'ticket_analytics';
    // CRM
    public const ITEM_CRM_PIPELINE = 'crm_pipeline';
    public const ITEM_CRM_LEADS = 'crm_leads';
    public const ITEM_CRM_OPPORTUNITIES = 'crm_opportunities';
    public const ITEM_CRM_ACTIVITIES = 'crm_activities';
    public const ITEM_CRM_SETTINGS = 'crm_settings';
    public const ITEM_CRM_REPORTS = 'crm_reports';
    // System
    public const ITEM_COMPANY_SETTINGS = 'company_settings';
    public const ITEM_PACKAGES = 'packages';
    public const ITEM_SCHEDULE = 'schedule';
    public const ITEM_WEEKLY_BOARD = 'weekly_board';

    /**
     * Get all available sidebar items with labels.
     *
     * @return array<array-key, array<string, string>>
     */
    public static function getAllItems(): array
    {
        return [
            // Management
            self::ITEM_MAIDS => [
                'label' => 'Maids',
                'description' => 'Manage maid profiles and records',
            ],
            self::ITEM_TRAINERS => [
                'label' => 'Trainers',
                'description' => 'Manage trainer accounts',
            ],
            self::ITEM_CLIENTS => [
                'label' => 'Clients',
                'description' => 'Manage client accounts',
            ],
            self::ITEM_BOOKINGS => [
                'label' => 'Bookings',
                'description' => 'Access to bookings management',
            ],
            // Training & Development
            self::ITEM_MY_PROGRAMS => [
                'label' => 'Training Programs',
                'description' => 'Manage training programs',
            ],
            self::ITEM_MY_EVALUATIONS => [
                'label' => 'Evaluations',
                'description' => 'Access maid evaluations',
            ],
            self::ITEM_DEPLOYMENTS => [
                'label' => 'Deployments',
                'description' => 'Manage maid deployments',
            ],
            self::ITEM_TRAINER_PERMISSIONS => [
                'label' => 'Trainer Permissions',
                'description' => 'Manage trainer sidebar access (admin)',
            ],
            // Analytics & Reports
            self::ITEM_REPORTS => [
                'label' => 'Reports',
                'description' => 'Access analytics and reports',
            ],
            self::ITEM_KPI_DASHBOARD => [
                'label' => 'KPI Dashboard',
                'description' => 'View key performance indicators',
            ],
            self::ITEM_WEEKLY_BOARDS_REVIEW => [
                'label' => 'Weekly Boards Review',
                'description' => 'Review trainer weekly boards (admin)',
            ],
            // Support & Tickets
            self::ITEM_CONTACT_INQUIRIES => [
                'label' => 'Contact Inquiries',
                'description' => 'View contact form submissions',
            ],
            self::ITEM_TICKETS => [
                'label' => 'All Tickets',
                'description' => 'View all support tickets',
            ],
            self::ITEM_TICKETS_INBOX => [
                'label' => 'My Inbox',
                'description' => 'View personal ticket inbox',
            ],
            self::ITEM_TICKET_ANALYTICS => [
                'label' => 'Ticket Analytics',
                'description' => 'View ticket analytics and metrics',
            ],
            // CRM
            self::ITEM_CRM_PIPELINE => [
                'label' => 'CRM Pipeline Board',
                'description' => 'Access CRM pipeline kanban board',
            ],
            self::ITEM_CRM_LEADS => [
                'label' => 'CRM Leads',
                'description' => 'Manage leads in CRM',
            ],
            self::ITEM_CRM_OPPORTUNITIES => [
                'label' => 'CRM Opportunities',
                'description' => 'Manage opportunities in CRM',
            ],
            self::ITEM_CRM_ACTIVITIES => [
                'label' => 'CRM Activities',
                'description' => 'Manage activities in CRM',
            ],
            self::ITEM_CRM_SETTINGS => [
                'label' => 'CRM Settings',
                'description' => 'Access CRM configuration (admin)',
            ],
            self::ITEM_CRM_REPORTS => [
                'label' => 'CRM Reports',
                'description' => 'View CRM analytics and reports',
            ],
            // System
            self::ITEM_COMPANY_SETTINGS => [
                'label' => 'Company Settings',
                'description' => 'Configure company settings (admin)',
            ],
            self::ITEM_PACKAGES => [
                'label' => 'Packages',
                'description' => 'Manage service packages',
            ],
            self::ITEM_SCHEDULE => [
                'label' => 'Schedule',
                'description' => 'Access trainer schedule',
            ],
            self::ITEM_WEEKLY_BOARD => [
                'label' => 'Weekly Task Board',
                'description' => 'Access personal weekly task board',
            ],
        ];
    }

    /**
     * Get the trainer that owns this permission.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }
}
