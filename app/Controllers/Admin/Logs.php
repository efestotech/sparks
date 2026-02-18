<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailLogModel;

class Logs extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new EmailLogModel();
    }

    public function index()
    {
        $data = [
            'logs'  => $this->logModel->orderBy('created_at', 'DESC')->paginate(30),
            'pager' => $this->logModel->pager,
        ];

        return view('admin/logs/index', $data);
    }
}
