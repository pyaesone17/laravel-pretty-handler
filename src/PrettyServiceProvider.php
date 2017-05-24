<?php 
namespace Pyaesone17\LaravelPrettyHandler;

use Illuminate\Support\ServiceProvider;

class PrettyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PrettyHandler::class, function ($app) {
            return new PrettyHandler($app->request, $app->view);
        });
    }
}