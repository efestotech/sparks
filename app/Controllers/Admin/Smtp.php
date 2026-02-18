<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SmtpServerModel;
use App\Entities\SmtpServer;
use App\Services\SmtpManager;
use App\Services\OAuth\OAuthProviderFactory;
use Config\Services;

class Smtp extends BaseController
{
    protected $smtpModel;

    public function __construct()
    {
        $this->smtpModel = new SmtpServerModel();
    }

    public function index()
    {
        $data['servers'] = $this->smtpModel->orderBy('priority', 'ASC')->findAll();
        return view('admin/smtp/index', $data);
    }

    public function new()
    {
        return view('admin/smtp/form', ['server' => new SmtpServer()]);
    }

    public function edit($id)
    {
        $server = $this->smtpModel->find($id);
        if (!$server) {
            return redirect()->to('admin/smtp')->with('error', 'Server not found');
        }
        return view('admin/smtp/form', ['server' => $server]);
    }

    public function create()
    {
        return $this->save();
    }

    public function update($id)
    {
        return $this->save($id);
    }

    protected function save($id = null)
    {
        $rules = [
            'name'       => 'required|max_length[100]',
            'host'       => 'required|max_length[255]',
            'port'       => 'required|numeric',
            'from_email' => 'required|valid_email',
            'from_name'  => 'required|max_length[255]',
            'priority'   => 'required|numeric',
            'auth_type'  => 'required|in_list[basic,oauth2]',
        ];

        if ($this->request->getPost('auth_type') === 'basic') {
            $rules['username'] = 'required|max_length[255]';
        } else {
            $rules['oauth_provider'] = 'required|in_list[microsoft,google,dummy]';
            $rules['client_id']      = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Handle checkboxes (if not present, they are false/0)
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        $data['connection_pooling'] = isset($data['connection_pooling']) ? 1 : 0;
        
        // Handle password encryption if changed
        if (!empty($data['password'])) {
            try {
                $encrypter = Services::encrypter();
                $data['password'] = base64_encode($encrypter->encrypt($data['password']));
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Encryption error: ' . $e->getMessage());
            }
        } else if ($id) {
            unset($data['password']);
        }

        // Handle client_secret encryption if changed
        if (!empty($data['client_secret'])) {
            try {
                $encrypter = Services::encrypter();
                $data['client_secret'] = base64_encode($encrypter->encrypt($data['client_secret']));
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Encryption error (Secret): ' . $e->getMessage());
            }
        } else if ($id) {
            unset($data['client_secret']);
        }

        $server = new SmtpServer($data);
        if ($id) {
            $server->id = $id;
        }

        if ($this->smtpModel->save($server)) {
            return redirect()->to('admin/smtp')->with('success', 'SMTP Server saved successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to save SMTP Server');
    }

    public function delete($id)
    {
        if ($this->smtpModel->delete($id)) {
            return redirect()->to('admin/smtp')->with('success', 'SMTP Server deleted');
        }
        return redirect()->to('admin/smtp')->with('error', 'Failed to delete');
    }

    /**
     * Toggles the status of a server via AJAX
     */
    public function toggleStatus($id)
    {
        $server = $this->smtpModel->find($id);
        if (!$server) {
            return $this->response->setJSON(['success' => false, 'message' => 'Server not found']);
        }

        $server->is_active = $server->is_active ? 0 : 1;
        
        if ($this->smtpModel->save($server)) {
            return $this->response->setJSON(['success' => true, 'new_status' => $server->is_active]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to toggle status']);
    }

    public function test()
    {
        $id        = $this->request->getPost('id');
        $testEmail = $this->request->getPost('test_email');
        $server    = $this->smtpModel->find($id);

        if (!$server) {
            return $this->response->setJSON(['success' => false, 'message' => 'Server not found']);
        }

        $recipient = !empty($testEmail) ? $testEmail : $server->from_email;

        $smtpManager = new SmtpManager();
        $mailer = $smtpManager->getMailerInstance($server);

        try {
            $mailer->setFrom($server->from_email, $server->from_name);
            $mailer->addAddress($recipient); 
            $mailer->Subject = 'SPARKS SMTP Test';
            $mailer->Body    = 'This is a test email from SPARKS System to verify SMTP configuration.';
            
            if ($mailer->send()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Connection successful! Test email sent to ' . $recipient]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Connection failed: ' . $mailer->ErrorInfo . '. Check writable/logs/smtp_debug.log for details.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Unknown error']);
    }

    /**
     * Reindirizza l'utente al provider OAuth2 per l'autorizzazione.
     */
    public function authorize($id)
    {
        $server = $this->smtpModel->find($id);
        if (!$server || $server->auth_type !== 'oauth2') {
            return redirect()->to('admin/smtp')->with('error', 'Invalid server for OAuth2');
        }

        try {
            // Salviamo l'ID del server in sessione per recuperarlo al ritorno (callback)
            session()->set('oauth_server_id', $id);

            $factory = new OAuthProviderFactory();
            $provider = $factory->make(
                $server->oauth_provider,
                $server->client_id,
                $this->decryptSecret($server->client_secret),
                base_url('admin/smtp/oauth-callback')
            );

            return redirect()->to($provider->getAuthorizationUrl());
        } catch (\Exception $e) {
            return redirect()->to('admin/smtp')->with('error', 'OAuth2 Error: ' . $e->getMessage());
        }
    }

    /**
     * Decripta il segreto client (stessa logica delle password SMTP)
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
