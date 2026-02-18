<?php

namespace App\Services;

class WorkerLockService
{
    protected $lockFile;
    protected $timeout;

    public function __construct()
    {
        $this->lockFile = WRITEPATH . 'worker.lock';
        $this->timeout  = 300; // Default timeout in seconds
    }

    public function acquireLock(int $customTimeout = null)
    {
        $timeout = $customTimeout ?? $this->timeout;

        if ($this->isLocked($timeout)) {
            return false;
        }

        return file_put_contents($this->lockFile, time()) !== false;
    }

    public function releaseLock()
    {
        if (file_exists($this->lockFile)) {
            return unlink($this->lockFile);
        }
        return true;
    }

    public function isLocked(int $timeout)
    {
        if (!file_exists($this->lockFile)) {
            return false;
        }

        $lockTime = (int)file_get_contents($this->lockFile);
        if ((time() - $lockTime) > $timeout) {
            // Lock expired
            return false;
        }

        return true;
    }
}
