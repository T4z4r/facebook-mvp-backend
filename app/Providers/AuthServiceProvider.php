<?php
namespace App\Providers;

use App\Models\Post;
use App\Models\Friend;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update', function ($user, Post $post) {
            return $user->id === $post->user_id;
        });

        Gate::define('delete', function ($user, Post $post) {
            return $user->id === $post->user_id;
        });

        Gate::define('update', function ($user, Friend $friend) {
            return $user->id === $friend->friend_id;
        });
    }
}
