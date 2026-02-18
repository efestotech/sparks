<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SmtpServerModel;
use App\Services\OAuth\OAuthProviderFactory;
use Config\Services;

/**
 * Controller per gestire il ritorno (callback) dai provider OAuth2.
 */
class SmtpOAuth extends BaseController
{
    protected $smtpModel;

    public function __construct()
    {
        $this->smtpModel = new SmtpServerModel();
    }

    /**
     * Endpoint di callback richiamato dai provider (Google, Microsoft, etc.)
     */
    public function callback()
    {
        $serverId = session()->get('oauth_server_id');
        $code     = $this->request->getGet('code');
        $error    = $this->request->getGet('error');

        if (!$serverId || !$code) {
            return redirect()->to('admin/smtp')->with('error', lang('Sparks.oauth_error') . ($error ?? 'Invalid session or missing code'));
        }

        $server = $this->smtpModel->find($serverId);
        if (!$server) {
            return redirect()->to('admin/smtp')->with('error', 'Server SMTP non trovato.');
        }

        try {
            $factory = new OAuthProviderFactory();
            $provider = $factory->make(
                $server->oauth_provider,
                $server->client_id,
                $this->decryptSecret($server->client_secret),
                base_url('admin/smtp/oauth-callback')
            );

            // Scambio il codice con i token (ora include anche 'username' ovvero l'email)
            $tokens = $provider->getTokensByCode($code);

            // Salvo i token e l'email nel database
            $server->fill($tokens);
            // Il fill($tokens) caricherà anche 'username' se presente nell'array
            
            $this->smtpModel->save($server);

            return redirect()->to('admin/smtp')->with('success', lang('Sparks.oauth_success'));

        } catch (\Exception $e) {
            return redirect()->to('admin/smtp')->with('error', lang('Sparks.oauth_error') . $e->getMessage());
        } finally {
            session()->remove('oauth_server_id');
        }
    }

    /**
     * Decripta il segreto client (stessa logica del controller Smtp)
     */
    protected function decryptSecret($encryptedSecret)
    {
        if (empty($encryptedSecret)) return '';
        $encrypter = Services::encrypter();
        try {
            return $encrypter->decrypt(base64_decode($encryptedSecret));
        } catch (\Exception $e) {
            return $encryptedSecret;
        }
    }
}
