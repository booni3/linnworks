<?php

namespace Booni3\Linnworks;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;

class LinnworksServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/linnworks.php' => config_path('linnworks.php'),
        ], 'linnworks');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [Linnworks::class, 'linnworks'];
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/../config/linnworks.php', 'linnworks');

        $this->app->singleton(Linnworks::class, function ($app) {
            $config = $app->make('config');

            $applicationId = $config->get('linnworks.applicationId');
            $applicationSecret = $config->get('linnworks.applicationSecret');
            $token = $config->get('linnworks.token');
            $client = $app->bound(GuzzleClient::class)
                ? $app->make(GuzzleClient::class)
                : null;

            return new Linnworks(compact('applicationId', 'applicationSecret', 'token'), $client);
        });

        $this->app->alias(Linnworks::class, 'linnworks');
    }

}
