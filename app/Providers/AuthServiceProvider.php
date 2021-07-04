<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Lender' => 'App\Policies\LenderPolicy',
        'App\Models\Provider' => 'App\Policies\ProviderPolicy',
        'App\Models\AcquisitionType' => 'App\Policies\AcquisitionTypePolicy',
        'App\Models\Acquisition' => 'App\Policies\AcquisitionPolicy',
        'App\Models\CollectionType' => 'App\Policies\CollectionTypePolicy',
        'App\Models\CollectionCategory' => 'App\Policies\CollectionCategoryPolicy',
        'App\Models\Collection' => 'App\Policies\CollectionPolicy',
        'App\Models\CollectionCopy' => 'App\Policies\CollectionCopyPolicy',
        'App\Models\Loan' => 'App\Policies\LoanPolicy',
        'App\Models\LoanContainsCollectionCopy' => 'App\Policies\LoanContainsCollectionCopyPolicy',
        'App\Models\Profile' => 'App\Policies\ProfilePolicy',
        'App\Models\Permission' => 'App\Policies\PermissionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::personalAccessClientId(1);

        Passport::tokensExpireIn(now()->addDays(15));
    }
}
