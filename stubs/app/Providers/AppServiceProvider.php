<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer('layouts.app', function($view) {
            $user = auth()->user();
            $route = current_route();

            if ($user) {
                $view->with('dropdown', [
                    [
                        'icon' => 'user',
                        'label' => 'My Account',
                        'href' => route('user.account'),
                    ],
                ]);

                $view->with('navs', [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'home-smile',
                        'href' => route('dashboard'),
                        'active' => $route === 'dashboard',
                    ],
                    [
                        'label' => 'Blogs',
                        'icon' => 'edit-alt',
                        'href' => route('blog.listing'),
                        'active' => Str::startsWith($route, 'blog.'),
                    ],
                    [
                        'label' => 'Enquiries',
                        'icon' => 'paper-plane',
                        'href' => route('enquiry.listing'),
                        'active' => Str::startsWith($route, 'enquiry.'),
                    ],
                    [
                        'label' => 'Pages',
                        'icon' => 'book-content',
                        'href' => route('page.listing'),
                        'active' => Str::startsWith($route, 'page.'),
                    ],
                    [
                        'label' => 'Settings',
                        'icon' => 'cog',
                        'dropdown' => [
                            [
                                'label' => 'My Account',
                                'href' => route('user.account'),
                                'active' => $route === 'user.account',
                            ],
                            [
                                'label' => 'Roles',
                                'href' => route('role.listing'),
                                'active' => Str::startsWith($route, 'role.'),
                                'enabled' => $user->can('role.manage'),
                            ],
                            [
                                'label' => 'Users',
                                'href' => route('user.listing'),
                                'active' => Str::startsWith($route, 'user.') && $route !== 'user.account',
                                'enabled' => $user->can('user.manage'),
                            ],
                            [
                                'label' => 'Teams',
                                'href' => route('team.listing'),
                                'active' => Str::startsWith($route, 'team.'),
                                'enabled' => $user->can('team.manage'),
                            ],
                            [
                                'label' => 'Labels',
                                'href' => route('label.listing'),
                                'active' => Str::startsWith($route, 'label.'),
                                'enabled' => $user->can('label.manage'),
                            ],
                            [
                                'label' => 'Files',
                                'href' => route('file.listing'),
                                'active' => Str::startsWith($route, 'file.'),
                            ],
                            [
                                'label' => 'Site Settings',
                                'href' => route('site-settings.update'),
                                'active' => $route === 'site-settings.update',
                            ],
                        ],
                    ],    
                ]);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
