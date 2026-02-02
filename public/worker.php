<?php

/**
 * EQS Worker Standalone Script
 * Accessible via: http://your-domain.com/worker.php?key=YOUR_SECRET_KEY
 */

use Config\Paths;
use App\Services\EmailService;
use App\Services\WorkerLockService;
use App\Models\SmtpServerModel;
use App\Models\SettingsModel;

// 1. Minimum PHP Version Check
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    exit("PHP {$minPhpVersion} or higher required.");
}

// 2. Constants and Paths
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

$pathsConfig = FCPATH . '../app/Config/Paths.php';
require $pathsConfig;
$paths = new Paths();

// 3. Bootstrap CI4
require $paths->systemDirectory . '/Boot.php';
$app = \CodeIgniter\Boot::bootWorker($paths);

// 4. Security Check
$settingsModel = new SettingsModel();
$expectedKey   = getenv('worker.secret_key') ?: 'REQUIRED_IN_ENV';

if (empty($_GET['key']) || $_GET['key'] !== $expectedKey) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['success' => false, 'error' => 'Invalid or missing secret key']);
    exit;
}

// 5. Lock Management
$lockService = new WorkerLockService();
$lockTimeout = (int)($settingsModel->getSetting('worker_lock_timeout') ?? 300);

if (!$lockService->acquireLock($lockTimeout)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Worker already running or lock not expired']);
    exit;
}

try {
    $startTime = microtime(true);
    
    // 6. Maintenance Tasks
    $smtpModel = new SmtpServerModel();
    $currentHour = (int)date('H');
    $currentMin  = (int)date('i');

    // Reset daily counters at midnight (00:00 - 00:05 window)
    if ($currentHour === 0 && $currentMin < 10) {
        $smtpModel->resetDailyCounters();
    }

    // Reset hourly counters every hour (XX:00 - XX:05 window)
    if ($currentMin < 10) {
        $smtpModel->resetHourlyCounters();
    }

    // 7. Process Queue
    $emailService = new EmailService();
    $chunkSize    = (int)($settingsModel->getSetting('chunk_size') ?? 50);
    $stats        = $emailService->processQueue($chunkSize);

    $duration = (int)((microtime(true) - $startTime) * 1000);

    // 8. Output Result
    header('Content-Type: application/json');
    echo json_encode([
        'success'   => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'stats'     => $stats,
        'duration'  => "{$duration}ms"
    ]);

} catch (\Exception $e) {
    log_message('error', 'EQS Worker Error: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    $lockService->releaseLock();
}
