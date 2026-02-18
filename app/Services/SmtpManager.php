<?php

namespace App\Services;

use App\Models\SmtpServerModel;
use App\Models\SettingsModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use App\Services\OAuth\OAuthProviderFactory;
use Config\Services;

class SmtpManager
{
    protected $smtpModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->smtpModel     = new SmtpServerModel();
        $this->settingsModel = new SettingsModel();
    }

    /**
     * Selects the best available SMTP server based on priority and limits
     */
    public function selectSmtpServer()
    {
        $servers = $this->smtpModel->getActiveServersOrdered();

        foreach ($servers as $server) {
            if ($server->hourly_sent < $server->max_hourly && $server->daily_sent < $server->max_daily) {
                return $server;
            }
        }

        return null; // No available server found
    }

    /**
     * Returns a configured PHPMailer instance
     */
    public function getMailerInstance($server)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $server->host;
        $mail->SMTPAuth   = true;
        
        if ($server->auth_type === 'oauth2') {
            $this->handleOAuth2Authentication($mail, $server);
        } else {
            $mail->Username   = $server->username;
            $mail->Password   = $this->decryptSecret($server->password);
        }

        $mail->SMTPSecure = $server->encryption === 'none' ? '' : $server->encryption;
        $mail->Port       = $server->port;
        $mail->CharSet    = 'UTF-8';

        // Abilitiamo il debug per catturare tutto il dialogo SMTP
        $this->enableMailerDebug($mail);
        $this->logDebug("--- Avvio nuova sessione SMTP Test ---");
        $this->logDebug("Host: {$server->host}:{$server->port} ({$server->encryption})");
        $this->logDebug("HELO Hostname: " . ($mail->Hostname ?: 'localhost'));
        $this->logDebug("Auth Type: {$server->auth_type}");

        if ($server->connection_pooling) {
            $mail->SMTPKeepAlive = true;
        }

        return $mail;
    }

    /**
     * Configura PHPMailer per l'autenticazione XOAUTH2.
     * Gestisce il rinfresco automatico dei token se scaduti.
     */
    protected function handleOAuth2Authentication(PHPMailer $mail, $server)
    {
        // 1. Controllo se il token è scaduto (o scade tra meno di 5 minuti)
        $expiresTimestamp = strtotime($server->expires_at);
        $this->logDebug("OAuth2: Token expires at {$server->expires_at} (Timestamp: {$expiresTimestamp})");
        
        if ($expiresTimestamp < (time() + 300)) {
            $this->logDebug("OAuth2: Token scaduto o in scadenza. Avvio refresh...");
            $this->refreshServerTokens($server);
        } else {
            $this->logDebug("OAuth2: Token ancora valido.");
        }

        // 2. Configurazione PHPMailer OAuth
        $factory = new OAuthProviderFactory();
        $providerInstance = $factory->make(
            $server->oauth_provider,
            $server->client_id,
            $this->decryptSecret($server->client_secret),
            base_url('admin/smtp/oauth-callback')
        )->getLeagueProvider(); // Prendiamo l'istanza del provider league/oauth2-client

        $this->logDebug("PHPMailer OAuth init: Provider: {$server->oauth_provider}, User: " . ($server->username ?: $server->from_email));

        $mail->AuthType = 'XOAUTH2';
        $mail->setOAuth(new OAuth([
            'provider'     => $providerInstance,
            'clientId'     => $server->client_id,
            'clientSecret' => $this->decryptSecret($server->client_secret),
            'refreshToken' => $server->refresh_token,
            'userName'     => $server->username ?: $server->from_email,
        ]));
    }

    /**
     * Esegue il rinfresco dei token e li salva nel database.
     */
    protected function refreshServerTokens($server)
    {
        $factory = new OAuthProviderFactory();
        $provider = $factory->make(
            $server->oauth_provider,
            $server->client_id,
            $this->decryptSecret($server->client_secret),
            base_url('admin/smtp/oauth-callback')
        );

        $newTokens = $provider->refreshTokens($server->refresh_token);
        
        if (empty($newTokens['access_token'])) {
            $this->logDebug("OAuth2 REFRESH FALLITO: Nessun access token ricevuto.");
        } else {
            $this->logDebug("OAuth2 REFRESH RIUSCITO. Salvataggio nuovi token...");
        }

        // Aggiorniamo l'entità e il database
        $server->fill($newTokens);
        $this->smtpModel->save($server);
    }

    /**
     * Decripta password o segreti client
     */
    protected function decryptSecret($encrypted)
    {
        if (empty($encrypted)) return '';
        $encrypter = Services::encrypter();
        try {
            return $encrypter->decrypt(base64_decode($encrypted));
        } catch (\Exception $e) {
            return $encrypted;
        }
    }

    /**
     * Scrive un messaggio nel file di debug SMTP dedicato.
     */
    protected function logDebug($message)
    {
        $logFile = WRITEPATH . 'logs/smtp_debug.log';
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }

    /**
     * Abilita il debug di PHPMailer e lo reindirizza al nostro file di log.
     */
    protected function enableMailerDebug(PHPMailer $mail)
    {
        $mail->SMTPDebug = 2; // Output dettagliato del protocollo
        $mail->Debugoutput = function($str, $level) {
            $this->logDebug("PHPMailer [{$level}]: " . trim($str));
        };
    }
}
