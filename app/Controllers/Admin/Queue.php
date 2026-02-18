<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailQueueModel;
use App\Models\EmailLogModel;

class Queue extends BaseController
{
    protected $queueModel;

    public function __construct()
    {
        $this->queueModel = new EmailQueueModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $query  = $this->queueModel->orderBy('created_at', 'DESC');

        if ($status) {
            $query->where('status', $status);
        }

        $data = [
            'emails' => $query->paginate(20),
            'pager'  => $this->queueModel->pager,
            'status' => $status,
        ];

        return view('admin/queue/index', $data);
    }

    public function view($id)
    {
        $email = $this->queueModel->find($id);
        if (!$email) {
            return redirect()->to('admin/queue')->with('error', 'Email not found');
        }

        $logModel = new EmailLogModel();
        $data = [
            'email' => $email,
            'logs'  => $logModel->where('email_queue_id', $id)->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('admin/queue/view', $data);
    }

    public function retry($id)
    {
        $email = $this->queueModel->find($id);
        if (!$email) {
            return redirect()->to('admin/queue')->with('error', 'Email not found');
        }

        $this->queueModel->update($id, [
            'status'        => 'pending',
            'attempts'      => 0,
            'error_message' => null
        ]);

        return redirect()->back()->with('success', 'Email scheduled for retry');
    }

    public function create()
    {
        return view('admin/queue/create');
    }

    public function store()
    {
        $rules = [
            'to_email'       => 'required|valid_email|max_length[255]',
            'to_name'        => 'permit_empty|string|max_length[255]',
            'subject'        => 'required|string|max_length[255]',
            'body_html'      => 'required|string',
            'priority'       => 'permit_empty|is_natural_no_zero|less_than_equal_to[10]',
            'scheduled_at'   => 'permit_empty|valid_date[Y-m-d H:i:s]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Manual validation for date in the future
        if (!empty($data['scheduled_at'])) {
            if (strtotime($data['scheduled_at']) <= time()) {
                return redirect()->back()->withInput()->with('error', lang('Sparks.scheduled_at_future_only') ?? 'Scheduled time must be in the future');
            }
        }

        $emailService = new \App\Services\EmailService();
        $userId = session()->get('user_id'); // Assuming user_id is in session

        if ($emailService->queueEmail($data, $userId)) {
            return redirect()->to('admin/queue')->with('success', lang('Sparks.spark_ignited_success'));
        }

        return redirect()->back()->withInput()->with('error', lang('Sparks.spark_ignited_fail'));
    }

    public function delete($id)
    {
        if ($this->queueModel->delete($id)) {
            return redirect()->to('admin/queue')->with('success', 'Email deleted from queue');
        }
        return redirect()->to('admin/queue')->with('error', 'Failed to delete');
    }
}
