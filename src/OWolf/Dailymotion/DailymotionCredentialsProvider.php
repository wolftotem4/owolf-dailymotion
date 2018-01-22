<?php

namespace OWolf\Dailymotion;

use Illuminate\Support\ServiceProvider;
use OWolf\Credentials\AccessTokenCredentials;
use OWolf\Credentials\AnonymousCredentials;
use OWolf\Laravel\UserOAuthManager;
use OWolf\Laravel\Util;

class DailymotionCredentialsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->resolving('owolf.provider', function ($manager, $app) {
            $manager->addDriver('dailymotion.oauth', function ($name, $config) {
                $oauth = array_get($config, 'oauth', []);

                $oauth['redirectUri'] = Util::redirectUri(array_get($oauth, 'redirectUri'), $name);

                $provider = new Dailymotion($oauth);
                return new DailymotionOAuthHandler($provider, $name, $config);
            });
        });

        $this->app->resolving('owolf.credentials', function ($manager, $app) {
            $manager->addDriver('dailymotion.oauth', function ($name, $config) use ($app) {
                $manager = $this->app->make(UserOAuthManager::class);
                $session = $manager->session($name);
                return new AccessTokenCredentials($session->provider(), $session->getAccessToken());
            });

            $manager->addDriver('dailymotion.app', function ($name, $config) {
                return new AnonymousCredentials(new Dailymotion());
            });
        });
    }
}