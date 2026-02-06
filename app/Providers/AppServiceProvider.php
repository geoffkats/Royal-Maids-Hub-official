<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use App\Helpers\CurrencyHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Maid::class => \App\Policies\MaidPolicy::class,
        \App\Models\Client::class => \App\Policies\ClientPolicy::class,
        \App\Models\Booking::class => \App\Policies\BookingPolicy::class,
        \App\Models\Deployment::class => \App\Policies\DeploymentPolicy::class,
        \App\Models\Trainer::class => \App\Policies\TrainerPolicy::class,
        \App\Models\Package::class => \App\Policies\PackagePolicy::class,
        \App\Models\TrainingProgram::class => \App\Policies\TrainingProgramPolicy::class,
        \App\Models\Evaluation::class => \App\Policies\EvaluationPolicy::class,
        \App\Models\ClientEvaluation::class => \App\Policies\ClientEvaluationPolicy::class,
        \App\Models\ClientEvaluationQuestion::class => \App\Policies\ClientEvaluationQuestionPolicy::class,
        \App\Models\ClientEvaluationResponse::class => \App\Policies\ClientEvaluationResponsePolicy::class,
        \App\Models\EvaluationTask::class => \App\Policies\EvaluationTaskPolicy::class,
        \App\Models\CRM\Lead::class => \App\Policies\CRM\LeadPolicy::class,
        \App\Models\CRM\Opportunity::class => \App\Policies\CRM\OpportunityPolicy::class,
        \App\Models\CRM\Activity::class => \App\Policies\CRM\ActivityPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix older MySQL key length limitations (e.g., 5.7) for utf8mb4 indexes
        Schema::defaultStringLength(191);
        
        // Configure morph map for polymorphic relationships
        Relation::morphMap([
            'user' => \App\Models\User::class,
            'admin' => \App\Models\User::class,
            'trainer' => \App\Models\User::class,
            'client' => \App\Models\Client::class,
            'maid' => \App\Models\Maid::class,
            'lead' => \App\Models\CRM\Lead::class,
            'opportunity' => \App\Models\CRM\Opportunity::class,
        ]);

        // Register CRM policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Sensitive field gates for identity and financial data.
        Gate::define('viewSensitiveIdentity', function ($user, $model = null) {
            return $user instanceof \App\Models\User && $user->isSuperAdmin();
        });

        Gate::define('updateSensitiveIdentity', function ($user, $model = null) {
            return $user instanceof \App\Models\User && $user->isSuperAdmin();
        });

        Gate::define('viewSensitiveFinancials', function ($user, $model = null) {
            return $user instanceof \App\Models\User && ($user->isSuperAdmin() || $user->isFinanceOfficer());
        });

        Gate::define('updateSensitiveFinancials', function ($user, $model = null) {
            return $user instanceof \App\Models\User && ($user->isSuperAdmin() || $user->isFinanceOfficer());
        });

        // User management is restricted to super admins (legacy admins included).
        Gate::define('manageUsers', function ($user) {
            return $user instanceof \App\Models\User && $user->isSuperAdmin();
        });
        
        // Register currency Blade directives
        Blade::directive('currency', function ($expression) {
            return "<?php echo App\Helpers\CurrencyHelper::format($expression); ?>";
        });
        
        Blade::directive('currencySymbol', function () {
            return "<?php echo App\Helpers\CurrencyHelper::symbol(); ?>";
        });
    }
}
