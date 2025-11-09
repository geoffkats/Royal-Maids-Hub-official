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
        
        // Register currency Blade directives
        Blade::directive('currency', function ($expression) {
            return "<?php echo App\Helpers\CurrencyHelper::format($expression); ?>";
        });
        
        Blade::directive('currencySymbol', function () {
            return "<?php echo App\Helpers\CurrencyHelper::symbol(); ?>";
        });
    }
}
