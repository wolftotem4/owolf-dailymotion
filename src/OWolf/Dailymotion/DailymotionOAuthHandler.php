<?php

namespace OWolf\Dailymotion;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use OWolf\Laravel\Contracts\OAuthHandler;
use OWolf\Laravel\ProviderHandler;
use OWolf\Laravel\Traits\OAuthProvider;

class DailymotionOAuthHandler extends ProviderHandler implements OAuthHandler
{
    use OAuthProvider;

    /**
     * @param  \League\OAuth2\Client\Token\AccessToken  $token
     * @return string|null
     */
    public function getName(AccessToken $token)
    {
        $resourceOwner = $this->getResourceOwner($token);
        return $resourceOwner->getScreenname();
    }

    /**
     * @param  \League\OAuth2\Client\Token\AccessToken  $token
     * @return string|null
     */
    public function getEmail(AccessToken $token)
    {
        $resourceOwner = $this->getResourceOwner($token);
        return $resourceOwner->getEmail();
    }

    /**
     * @param  \League\OAuth2\Client\Token\AccessToken  $token
     * @param  string  $ownerId
     * @return bool
     */
    public function revokeToken(AccessToken $token, $ownerId)
    {
        try {
            $method = 'GET';
            $url = 'https://api.dailymotion.com/logout';
            $request = $this->provider()->getAuthenticatedRequest($method, $url, $token);
            $this->provider()->getParsedResponse($request);

            return true;
        } catch (IdentityProviderException $e) {
            return false;
        }
    }
}