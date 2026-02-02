<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?><?= lang('Sparks.email_queue') ?><?= $this->endSection() ?>
<?= $this->section('header') ?><?= lang('Sparks.queue_mgmt') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0">
    <div class="card-header border-bottom-0 bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group shadow-sm">
                <a href="<?= base_url('admin/queue') ?>" class="btn btn-sm btn-outline-secondary <?= !$status ? 'active' : '' ?>"><?= lang('Sparks.all') ?></a>
                <a href="<?= base_url('admin/queue?status=pending') ?>" class="btn btn-sm btn-outline-secondary <?= $status == 'pending' ? 'active' : '' ?>"><?= lang('Sparks.pending') ?></a>
                <a href="<?= base_url('admin/queue?status=sent') ?>" class="btn btn-sm btn-outline-secondary <?= $status == 'sent' ? 'active' : '' ?>"><?= lang('Sparks.sent') ?></a>
                <a href="<?= base_url('admin/queue?status=failed') ?>" class="btn btn-sm btn-outline-secondary <?= $status == 'failed' ? 'active' : '' ?>"><?= lang('Sparks.failed') ?></a>
            </div>
            <small class="text-muted"><?= lang('Sparks.showing_items', [count($emails)]) ?></small>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-top-0"><?= lang('Sparks.id') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.to') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.subject') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.status') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.priority') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.attempts') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.created') ?></th>
                        <th class="border-top-0"><?= lang('Sparks.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emails as $email): ?>
                    <tr>
                        <td><small class="text-muted">#<?= $email->id ?></small></td>
                        <td>
                            <div class="font-weight-semibold"><?= esc($email->to_name ?: lang('Sparks.no_name')) ?></div>
                            <small class="text-muted"><?= esc($email->to_email) ?></small>
                        </td>
                        <td><?= mb_strimwidth(esc($email->subject), 0, 40, '...') ?></td>
                        <td>
                            <?php if ($email->status === 'sent'): ?>
                                <span class="badge badge-success"><?= lang('Sparks.sent') ?></span>
                            <?php elseif ($email->status === 'pending'): ?>
                                <span class="badge badge-warning text-white"><?= lang('Sparks.pending') ?></span>
                            <?php elseif ($email->status === 'failed'): ?>
                                <span class="badge badge-danger"><?= lang('Sparks.failed') ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?= ucfirst($email->status) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><span class="text-sm"><?= $email->priority ?></span></td>
                        <td><?= $email->attempts ?> / <?= $email->max_attempts ?></td>
                        <td><small><?= $email->created_at ?></small></td>
                        <td>
                            <div class="btn-group">
                                <a href="<?= base_url('admin/queue/view/' . $email->id) ?>" class="btn btn-xs btn-outline-primary"><i class="fas fa-eye"></i></a>
                                <?php if ($email->status === 'failed'): ?>
                                    <a href="<?= base_url('admin/queue/retry/' . $email->id) ?>" class="btn btn-xs btn-outline-info ml-1" title="<?= lang('Sparks.retry') ?>"><i class="fas fa-sync"></i></a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/queue/delete/' . $email->id) ?>" class="btn btn-xs btn-outline-danger ml-1" onclick="return confirm('<?= lang('Sparks.delete_email_confirm') ?>')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($emails)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted"><?= lang('Sparks.no_queue_records') ?></td>
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
