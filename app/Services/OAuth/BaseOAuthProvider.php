<?php

namespace App\Services\OAuth;

/**
 * Classe base astratta per i Provider OAuth2.
 * 
 * Implementa la logica comune a tutti i provider (gestione credenziali e rinfresco).
 */
abstract class BaseOAuthProvider implements SmtpOAuthProviderInterface
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;
    protected $provider; // L'istanza specifica della libreria league/oauth2-client

    public function __construct(string $clientId, string $clientSecret, string $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Metodo helper per formattare i token ritornati dalle librerie esterne.
     */
    protected function formatTokenResponse($token): array
    {
        return [
            'access_token'  => $token->getToken(),
            'refresh_token' => $token->getRefreshToken(),
            'expires_at'    => date('Y-m-d H:i:s', $token->getExpires()),
        ];
    }

    /**
     * Ritorna l'istanza interna del provider.
     */
    public function getLeagueProvider()
    {
        return $this->provider;
    }
}
