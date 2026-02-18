<?php

namespace App\Controllers\Api\v1;

use App\Controllers\BaseController;
use App\Services\EmailService;
use App\Models\EmailQueueModel;
use CodeIgniter\API\ResponseTrait;

class QueueController extends BaseController
{
    use ResponseTrait;

    protected $emailService;
    protected $queueModel;

    public function __construct()
    {
        $this->emailService = new EmailService();
        $this->queueModel   = new EmailQueueModel();
    }

    /**
     * POST /api/v1/queue/send
     */
    public function send()
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
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true);
        $user  = $this->request->user;

        // Additional validation for custom headers and attachments
        if (isset($input['custom_headers']) && !is_array($input['custom_headers'])) {
            return $this->failValidationErrors(['custom_headers' => 'Must be an object/array']);
        }

        if (isset($input['attachments']) && !is_array($input['attachments'])) {
            return $this->failValidationErrors(['attachments' => 'Must be an array']);
        }

        // Check scheduled_at is in the future
        if (!empty($input['scheduled_at'])) {
            if (strtotime($input['scheduled_at']) <= time()) {
                return $this->failValidationErrors(['scheduled_at' => 'Scheduled time must be in the future']);
            }
        }

        $queueId = $this->emailService->queueEmail($input, $user->id);

        if ($queueId) {
            return $this->respondCreated([
                'success' => true,
                'data'    => [
                    'queue_id'     => $queueId,
                    'status'       => 'pending',
                    'scheduled_at' => $input['scheduled_at'] ?? null,
                    'message'      => 'Email queued successfully'
                ]
            ]);
        }

        return $this->fail('Failed to queue email', 500);
    }

    /**
     * GET /api/v1/queue/status/{id}
     */
    public function status($id = null)
    {
        $user = $this->request->user;
        $item = $this->queueModel->where('user_id', $user->id)->find($id);

        if (!$item) {
            return $this->failNotFound('Email not found in queue');
        }

        return $this->respond([
            'success' => true,
            'data'    => [
                'id'              => $item->id,
                'status'          => $item->status,
                'attempts'        => $item->attempts,
                'error_message'   => $item->error_message,
                'sent_at'         => $item->sent_at,
                'last_attempt_at' => $item->last_attempt_at,
                'scheduled_at'    => $item->scheduled_at,
            ]
        ]);
    }
}
