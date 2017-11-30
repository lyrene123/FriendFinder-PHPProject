<?php

namespace App\Providers;

use App\User;
use App\Course;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\FriendPolicy;
use App\Policies\CourseManagerPolicy;
use App\Friend;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Friend::class => FriendPolicy::class,
        User::class => FriendPolicy::class,
        Course::class => CourseManagerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
