<?php

namespace App\Providers;

use App\Filament\Resources\UserResource;
use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Schema;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
            Filament::serving(function () {
                if(auth()->user()){
                    Filament::registerUserMenuItems([
                        'account' => UserMenuItem::make()
                        ->label('User Profile')
                        ->url(UserResource::getUrl('edit',['record' => auth()->user()])),
                        // ...
                    ]);
                }
                
            });
        
        
    }
}
