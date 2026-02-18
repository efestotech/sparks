<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Services\EmailService;
use App\Models\EmailQueueModel;
use CodeIgniter\API\ResponseTrait;

class BridgeController extends BaseController
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
     * POST /api/v1/bridge/inject
     * Accepts legacy payload and converts it to Sparks format
     */
    public function inject()
    {
        $input = $this->request->getJSON(true);
        $user  = $this->request->user;

        if (!$input || !isset($input['to_email'])) {
            return $this->fail('Invalid data received', 400);
        }

        // Map legacy fields if present
        $data = [
            'to_email'       => $input['to_email'],
            'to_name'        => $input['to_name'] ?? null,
            'subject'        => $input['subject'] ?? '(No Subject)',
            'body_html'      => $input['body_html'] ?? '',
            'priority'       => $input['priority'] ?? 5,
            'legacy_id_a'    => $input['legacy_id_a'] ?? null,
            'legacy_id_b'    => $input['legacy_id_b'] ?? null,
            'custom_headers' => $input['custom_headers'] ?? null,
            'scheduled_at'   => $input['scheduled_at'] ?? null,
        ];

        $queueId = $this->emailService->queueEmail($data, $user->id);

        if ($queueId) {
            return $this->respondCreated([
                'success' => true,
                'data'    => ['queue_id' => $queueId]
            ]);
        }

        return $this->fail('Failed to queue email via bridge', 500);
    }

    /**
     * POST /api/v1/bridge/clear
     * Deletes pending emails matching legacy IDs
     */
    public function clear()
    {
        $input = $this->request->getJSON(true);
        $user  = $this->request->user;

        $idA = $input['legacy_id_a'] ?? null;
        $idB = $input['legacy_id_b'] ?? null;

        if ($idA === null || $idB === null) {
            return $this->fail('Missing legacy IDs for clearing', 400);
        }

        $this->queueModel->where('user_id', $user->id)
                         ->where('status', 'pending')
                         ->where('legacy_id_a', $idA)
                         ->where('legacy_id_b', $idB)
                         ->delete();

        return $this->respond([
            'success' => true,
            'message' => 'Pending duplicates cleared'
        ]);
    }
}
