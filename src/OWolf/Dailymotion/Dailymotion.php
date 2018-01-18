<?php

namespace OWolf\Dailymotion;

use WTotem4\OAuth2\Client\Provider\Dailymotion as BaseProvider;

class Dailymotion extends BaseProvider
{
    /**
     * @var array
     */
    protected $userFields = [
        'id', 'username', 'screenname', 'email',
        'description', 'url', 'avatar_120_url'
    ];
}
