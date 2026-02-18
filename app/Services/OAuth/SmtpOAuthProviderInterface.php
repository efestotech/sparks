<?php

namespace App\Services\OAuth;

/**
 * Interfaccia per i Provider OAuth2 di Smtp.
 * 
 * Ogni nuovo provider (es: Yahoo, Zoho) deve implementare questa interfaccia
 * per essere utilizzato dal sistema Sparks tramite la Factory.
 */
interface SmtpOAuthProviderInterface
{
    /**
     * Ritorna l'URL di autorizzazione a cui reindirizzare l'utente.
     */
    public function getAuthorizationUrl(): string;

    /**
     * Scambia il codice temporaneo ricevuto dal callback con i token reali.
     * 
     * @param string $code
     * @return array ['access_token', 'refresh_token', 'expires_at', 'username']
     */
    public function getTokensByCode(string $code): array;

    /**
     * Usa il Refresh Token per ottenere un nuovo Access Token.
     * 
     * @param string $refreshToken
     * @return array ['access_token', 'refresh_token', 'expires_at']
     */
    public function refreshTokens(string $refreshToken): array;

    /**
     * Ritorna gli scopes richiesti per l'invio via SMTP.
     */
    public function getScopes(): array;
    
    /**
     * Ritorna l'email dell'account che ha concesso l'autorizzazione.
     */
    public function getOwnerEmail($token): string;

    /**
     * Ritorna l'istanza interna del provider (league/oauth2-client).
     */
    public function getLeagueProvider();

    /**
     * Ritorna il nome leggibile del provider.
     */
    public function getName(): string;
}
