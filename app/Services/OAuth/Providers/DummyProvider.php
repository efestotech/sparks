<?php

namespace App\Services\OAuth\Providers;

use App\Services\OAuth\BaseOAuthProvider;

/**
 * Provider OAuth2 "Dummy" (Template).
 * 
 * Questo file serve come base per aggiungere nuovi provider in futuro.
 * Copia questo file in (NomeProvider)Provider.php e segui le istruzioni.
 */
class DummyProvider extends BaseOAuthProvider
{
    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        parent::__construct($clientId, $clientSecret, $redirectUri);

        /**
         * TODO: Sostituisci questa logica con l'istanza del provider specifico.
         * Esempio: $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
         *     'clientId'                => $clientId,
         *     'clientSecret'            => $clientSecret,
         *     'redirectUri'             => $redirectUri,
         *     'urlAuthorize'            => 'https://example.com/oauth/authorize',
         *     'urlAccessToken'          => 'https://example.com/oauth/token',
         *     'urlResourceOwnerDetails' => 'https://example.com/api/user'
         * ]);
         */
    }

    public function getAuthorizationUrl(): string
    {
        // TODO: Ritorna l'URL generato dal provider
        return '#';
    }

    public function getTokensByCode(string $code): array
    {
        // TODO: Implementa lo scambio del codice con il token
        return [
            'access_token'  => '',
            'refresh_token' => '',
            'expires_at'    => date('Y-m-d H:i:s'),
        ];
    }

    public function refreshTokens(string $refreshToken): array
    {
        // TODO: Implementa il rinfresco del token
        return [];
    }

    public function getOwnerEmail($token): string
    {
        return 'dummy@example.com';
    }

    public function getScopes(): array
    {
        // TODO: Inserisci gli scope richiesti dal provider
        return [];
    }

    public function getName(): string
    {
        return 'Provider Template (Dummy)';
    }
}
