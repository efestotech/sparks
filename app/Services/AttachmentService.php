<?php

namespace App\Services;

use Config\Services;

class AttachmentService
{
    protected $tempDir;
    protected $uploadDir;

    public function __construct()
    {
        $this->tempDir   = WRITEPATH . 'temp/';
        $this->uploadDir = WRITEPATH . 'uploads/';

        if (!is_dir($this->tempDir)) mkdir($this->tempDir, 0777, true);
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0777, true);
    }

    /**
     * Resolves attachments to local paths
     */
    public function resolveAttachments(array $attachments)
    {
        $resolved = [];

        foreach ($attachments as $attachment) {
            $type = $attachment['type'] ?? 'local';
            $path = $attachment['path'] ?? ($attachment['url'] ?? null);
            $name = $attachment['filename'] ?? basename($path);

            if ($type === 'local') {
                $fullPath = realpath($this->uploadDir . basename($path));
                if ($fullPath && strpos($fullPath, realpath($this->uploadDir)) === 0 && file_exists($fullPath)) {
                    $resolved[] = ['path' => $fullPath, 'name' => $name];
                }
            } elseif ($type === 'remote') {
                $tempFile = $this->downloadRemoteFile($path);
                if ($tempFile) {
                    $resolved[] = ['path' => $tempFile, 'name' => $name, 'is_temp' => true];
                }
            }
        }

        return $resolved;
    }

    protected function downloadRemoteFile($url)
    {
        $client = Services::curlrequest();
        $filename = $this->tempDir . bin2hex(random_bytes(8)) . '_' . basename(parse_url($url, PHP_URL_PATH));
        
        try {
            $client->get($url, ['saveTo' => $filename, 'timeout' => 10]);
            return file_exists($filename) ? $filename : null;
        } catch (\Exception $e) {
            log_message('error', 'EQS: Failed to download remote attachment: ' . $url . ' - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cleans up temporary files
     */
    public function cleanup(array $resolvedAttachments)
    {
        foreach ($resolvedAttachments as $attachment) {
            if (!empty($attachment['is_temp']) && file_exists($attachment['path'])) {
                unlink($attachment['path']);
            }
        }
    }
}
