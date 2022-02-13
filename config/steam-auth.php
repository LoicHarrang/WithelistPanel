<?php

return [
    /*
     * Redirect URL after login
     */
    'redirect_url' => '/login',
    /*
     * API Key (http://steamcommunity.com/dev/apikey)
     */
    'api_key' => env('STEAM_KEY', '4E50B73796D71191CD026BDFBB1FCD23'),
    /*
     * Is using https ?
     */
    'https' => true,
];
