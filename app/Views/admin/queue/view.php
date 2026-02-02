<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Email View #<?= $email->id ?><?= $this->endSection() ?>
<?= $this->section('header') ?>Email Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header border-bottom-0 bg-white d-flex justify-content-between">
                <h5 class="font-weight-bold mb-0">Message Information</h5>
                <span class="badge badge-<?= $email->status == 'sent' ? 'success' : ($email->status == 'failed' ? 'danger' : 'warning') ?> p-2 px-3">
                    <?= strtoupper($email->status) ?>
                </span>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th width="120">Recipient:</th><td><?= esc($email->to_name) ?> &lt;<?= esc($email->to_email) ?>&gt;</td></tr>
                    <tr><th>Subject:</th><td class="font-weight-bold"><?= esc($email->subject) ?></td></tr>
                    <tr><th>Priority:</th><td><?= $email->priority ?></td></tr>
                    <tr><th>Created:</th><td><?= $email->created_at ?></td></tr>
                    <?php if ($email->sent_at): ?>
                        <tr><th>Sent At:</th><td class="text-success"><?= $email->sent_at ?></td></tr>
                    <?php endif; ?>
                    <?php if ($email->error_message): ?>
                        <tr><th>Last Error:</th><td class="text-danger"><?= esc($email->error_message) ?></td></tr>
                    <?php endif; ?>
                </table>
                <hr>
                <div class="p-3 bg-light rounded" style="max-height: 400px; overflow-y: auto; border: 1px solid #e2e8f0;">
                    <?= $email->body_html ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom-0 bg-white">
                <h5 class="font-weight-bold mb-0">Processing Logs</h5>
            </div>
            <div class="card-body p-0">
                <div class="timeline timeline-inverse p-3">
                    <?php foreach ($logs as $log): ?>
                    <div>
                        <i class="fas <?= $log->event_type == 'success' ? 'fa-check bg-success' : 'fa-times bg-danger' ?>"></i>
                        <div class="timeline-item shadow-none border-0 bg-light mb-3">
                            <span class="time"><i class="far fa-clock"></i> <?= $log->created_at ?></span>
                            <h3 class="timeline-header font-weight-bold" style="font-size: 0.9rem; color: #475569">
                                <?= $log->event_type == 'success' ? 'Delivery Successful' : 'Delivery Attempt Failed' ?>
                            </h3>
                            <div class="timeline-body p-2" style="font-size: 0.85rem">
                                <?= esc($log->message) ?>
                                <?php if ($log->smtp_code): ?>
                                    <div class="mt-1"><small class="badge badge-secondary">SMTP Code: <?= $log->smtp_code ?></small></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($logs)): ?>
                        <p class="text-center p-4 text-muted">No logs available for this email.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
