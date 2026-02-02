<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?><?= lang('Sparks.system_logs') ?><?= $this->endSection() ?>
<?= $this->section('header') ?><?= lang('Sparks.delivery_history') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="border-top-0"><?= lang('Sparks.time') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.queue_id') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.event') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.details') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.smtp_code') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.elapsed') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><small><?= $log->created_at ?></small></td>
                        <td><a href="<?= base_url('admin/queue/view/' . $log->email_queue_id) ?>">#<?= $log->email_queue_id ?></a></td>
                        <td>
                            <?php if ($log->event_type == 'success'): ?>
                                <span class="badge badge-success px-2"><?= lang('Sparks.success') ?></span>
                            <?php else: ?>
                                <span class="badge badge-danger px-2"><?= lang('Sparks.failed') ?></span>
                            <?php endif; ?>
                        </td>
                        <td><small><?= esc($log->message) ?></small></td>
                        <td><code><?= $log->smtp_code ?: '-' ?></code></td>
                        <td><small><?= $log->processing_time_ms ?>ms</small></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted"><?= lang('Sparks.no_logs_found') ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top-0">
        <div class="float-right">
            <?= $pager->links() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
