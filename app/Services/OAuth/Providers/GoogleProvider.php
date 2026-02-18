<?php

namespace App\Services\OAuth\Providers;

use App\Services\OAuth\BaseOAuthProvider;
use League\OAuth2\Client\Provider\Google;

/**
 * Provider OAuth2 per Google (Gmail, Google Workspace).
 */
class GoogleProvider extends BaseOAuthProvider
{
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        parent::__construct($clientId, $clientSecret, $redirectUri);

        $this->provider = new Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
            'accessType'   => 'offline', // Necessario per ricevere il refresh token
            'prompt'       => 'consent',
        ]);
    }

    public function getAuthorizationUrl(): string
    {
        return $this->provider->getAuthorizationUrl([
            'scope' => $this->getScopes()
        ]);
    }

    public function getTokensByCode(string $code): array
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        $response = $this->formatTokenResponse($token);
        $response['username'] = $this->getOwnerEmail($token);

        return $response;
    }

    public function refreshTokens(string $refreshToken): array
    {
        $token = $this->provider->getAccessToken('refresh_token', [
            'refresh_token' => $refreshToken
        ]);

        return $this->formatTokenResponse($token);
    }

    public function getOwnerEmail($token): string
    {
        $owner = $this->provider->getResourceOwner($token);
        return $owner->getEmail();
    }

    public function getScopes(): array
    {
        // Scope completo per IMAP/SMTP via XOAUTH2
        return [
            'https://mail.google.com/'
        ];
    }

    public function getName(): string
    {
        return 'Google (Gmail/Workspace)';
    }
}
