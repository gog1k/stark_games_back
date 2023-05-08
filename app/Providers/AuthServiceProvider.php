<?php

namespace App\Providers;

use App\Models\Project;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'superuser' => 'Can all actions',
            'project_admin' => 'Can do all actions for project',
            'project_manager' => 'Can do same actions for project',
        ]);

        Auth::viaRequest('api-key', function (Request $request) {
            return Project::where('api_key', $request->header('authorization'))->first();
        });
    }
}
