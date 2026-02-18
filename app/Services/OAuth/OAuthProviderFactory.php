<?php

namespace App\Services\OAuth;

use App\Services\OAuth\Providers\MicrosoftProvider;
use App\Services\OAuth\Providers\GoogleProvider;
use App\Services\OAuth\Providers\DummyProvider;
use Exception;

/**
 * Factory per la creazione dei Provider OAuth2.
 * 
 * Centralizza la logica di istanziazione per evitare duplicazioni 
 * nel controller e nel manager SMTP.
 */
class OAuthProviderFactory
{
    /**
     * Crea un'istanza del provider basata sul tipo e sulle credenziali.
     * 
     * @param string $type Il tipo di provider (microsoft, google, dummy)
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @return SmtpOAuthProviderInterface
     * @throws Exception se il provider non è supportato
     */
    public static function make(string $type, string $clientId, string $clientSecret, string $redirectUri): SmtpOAuthProviderInterface
    {
        switch (strtolower($type)) {
            case 'microsoft':
                return new MicrosoftProvider($clientId, $clientSecret, $redirectUri);
            case 'google':
                return new GoogleProvider($clientId, $clientSecret, $redirectUri);
            case 'dummy':
                return new DummyProvider($clientId, $clientSecret, $redirectUri);
            default:
                throw new Exception("Provider OAuth2 [{$type}] non supportato.");
        }
    }
}
