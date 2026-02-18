<?php

namespace App\Services\OAuth\Providers;

use App\Services\OAuth\BaseOAuthProvider;
use TheNetworg\OAuth2\Client\Provider\Azure;

/**
 * Provider OAuth2 per Microsoft (Outlook, Office 365, Hotmail).
 */
class MicrosoftProvider extends BaseOAuthProvider
{
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        parent::__construct($clientId, $clientSecret, $redirectUri);

        $this->provider = new Azure([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
            // Per account personali e business usiamo l'endpoint 'common'
            'tenant'       => 'common',
            'defaultEndPointVersion' => '2.0',
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
        // Azure può ritornare l'email in vari campi (upn o email)
        return $owner->getUpn() ?: $owner->claim('email');
    }

    public function getScopes(): array
    {
        // Scopes necessari per inviare email via SMTP e recuperare l'email dell'utente
        return [
            'https://outlook.office.com/SMTP.Send',
            'https://outlook.office.com/Mail.Send',
            'offline_access',
            'openid',
            'profile',
            'email'
        ];
    }

    public function getName(): string
    {
        return 'Microsoft (Outlook/Office 365)';
    }
}
