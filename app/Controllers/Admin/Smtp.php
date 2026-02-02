<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SmtpServerModel;
use App\Entities\SmtpServer;
use App\Services\SmtpManager;
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
            'username'   => 'required|max_length[255]',
            'from_email' => 'required|valid_email',
            'from_name'  => 'required|max_length[255]',
            'priority'   => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Handle password encryption if changed
        if (!empty($data['password'])) {
            try {
                $encrypter = Services::encrypter();
                $data['password'] = base64_encode($encrypter->encrypt($data['password']));
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Encryption error: ' . $e->getMessage() . '. Please ensure encryption.key is set in .env');
            }
        } else if ($id) {
            unset($data['password']); // Keep existing password
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
            return $this->response->setJSON(['success' => false, 'message' => 'Connection failed: ' . $mailer->ErrorInfo]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Unknown error']);
    }
}
