<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Entities\User;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('admin/users/index', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $data = [
            'username'  => $this->request->getPost('username'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        }

        if (!$id) {
            $data['api_key'] = bin2hex(random_bytes(32));
        }

        $user = new User($data);
        if ($id) $user->id = $id;

        if ($this->userModel->save($user)) {
            return redirect()->to('admin/users')->with('success', 'User saved');
        }
        return redirect()->back()->with('error', 'Failed to save user');
    }

    public function generateKey($id)
    {
        $apiKey = bin2hex(random_bytes(32));
        if ($this->userModel->update($id, ['api_key' => $apiKey])) {
            return redirect()->to('admin/users')->with('success', 'New API Key generated');
        }
        return redirect()->to('admin/users')->with('error', 'Failed to generate key');
    }

    public function delete($id)
    {
        if ($this->userModel->delete($id)) {
            return redirect()->to('admin/users')->with('success', 'User deleted');
        }
        return redirect()->to('admin/users')->with('error', 'Failed to delete');
    }
}
