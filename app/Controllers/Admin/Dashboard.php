<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailQueueModel;
use App\Models\SmtpServerModel;
use App\Models\EmailLogModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $queueModel = new EmailQueueModel();
        $smtpModel  = new SmtpServerModel();
        $logModel   = new EmailLogModel();

        $now = date('Y-m-d H:i:s');
        $data = [
            'stats' => [
                'queued'      => $queueModel->where('status', 'pending')
                                            ->groupStart()
                                                ->where('scheduled_at', null)
                                                ->orWhere('scheduled_at <=', $now)
                                            ->groupEnd()
                                            ->countAllResults(),
                'scheduled'   => $queueModel->where('status', 'pending')
                                            ->where('scheduled_at >', $now)
                                            ->countAllResults(),
                'sent'        => $queueModel->where('status', 'sent')->countAllResults(),
                'failed'      => $queueModel->where('status', 'failed')->countAllResults(),
                'smtp_active' => $smtpModel->where('is_active', 1)->countAllResults(),
            ],
            'recent' => $queueModel->orderBy('created_at', 'DESC')->findAll(10),
        ];

        return view('admin/dashboard', $data);
    }
}
