<?php

namespace App\Http\Services;

use Google\Client as GoogleClient;

class GoogleAPIService
{
    protected $credentialsPath;

    public function __construct()
    {
        $this->credentialsPath = storage_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
    }

    public function getAccessToken()
    {
        $jsonKeyFilePath = $this->credentialsPath;

        $client = new GoogleClient();
        $client->setAuthConfig($jsonKeyFilePath);
        $client->addScope('https://www.googleapis.com/auth/cloud-platform');

        $token = $client->fetchAccessTokenWithAssertion();

        if (isset($token['error'])) {
            return response()->json(['error' => $token['error_description']], 400);
        }
        // Session store token
        session(['google_access_token' => $token]);

        return $token['access_token'];
    }

    public function getCredentialsPath()
    {
        return $this->credentialsPath;
    }
}
