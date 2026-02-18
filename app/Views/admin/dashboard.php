<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?>Dashboard | SPARKS<?= $this->endSection() ?>
<?= $this->section('header') ?><?= lang('Sparks.forge_dashboard') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Queued (Ready to fire) -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="small-box shadow-sm h-100" style="border-radius: 4px; background-color: #fff; border-left: 5px solid var(--sparks-blue);">
            <div class="inner">
                <h3 class="font-weight-bold"><?= $stats['queued'] ?></h3>
                <p class="text-uppercase small font-weight-bold text-muted mb-1"><?= lang('Sparks.embers_queued') ?></p>
                <small class="text-muted d-block"><?= lang('Sparks.embers_description') ?></small>
            </div>
            <div class="icon">
                <i class="fas fa-list-ul text-primary opacity-25" style="font-size: 50px; position: absolute; right: 15px; top: 15px;"></i>
            </div>
        </div>
    </div>

    <!-- Scheduled (Future) -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="small-box shadow-sm h-100" style="border-radius: 0; background-color: #fff; border-left: 5px solid #6f42c1;">
            <div class="inner">
                <h3 class="font-weight-bold"><?= $stats['scheduled'] ?></h3>
                <p class="text-uppercase small font-weight-bold text-muted mb-1"><?= lang('Sparks.scheduled_embers') ?></p>
                <small class="text-muted d-block"><?= lang('Sparks.scheduled_description') ?></small>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt text-info opacity-25" style="font-size: 50px; position: absolute; right: 15px; top: 15px;"></i>
            </div>
        </div>
    </div>
    
    <!-- Sent -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="small-box shadow-sm h-100" style="border-radius: 0; background-color: #fff; border-left: 5px solid #28a745;">
            <div class="inner">
                <h3 class="font-weight-bold"><?= $stats['sent'] ?></h3>
                <p class="text-uppercase small font-weight-bold text-muted mb-1"><?= lang('Sparks.fires_lit') ?></p>
                <small class="text-muted d-block"><?= lang('Sparks.fires_description') ?></small>
            </div>
            <div class="icon">
                <i class="fas fa-check-double text-success opacity-25" style="font-size: 50px; position: absolute; right: 15px; top: 15px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Failed -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="small-box shadow-sm h-100" style="border-radius: 0; background-color: #fff; border-left: 5px solid #dc3545;">
            <div class="inner">
                <h3 class="font-weight-bold"><?= $stats['failed'] ?></h3>
                <p class="text-uppercase small font-weight-bold text-muted mb-1"><?= lang('Sparks.extinguished_sparks') ?></p>
                <small class="text-muted d-block"><?= lang('Sparks.extinguished_desc') ?></small>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle text-danger opacity-25" style="font-size: 50px; position: absolute; right: 15px; top: 15px;"></i>
            </div>
        </div>
    </div>

    <!-- Active SMTPs -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="small-box shadow-sm h-100" style="border-radius: 4px; background-color: #fff; border-left: 5px solid var(--sparks-blue);">
            <div class="inner">
                <h3 class="font-weight-bold"><?= $stats['smtp_active'] ?></h3>
                <p class="text-uppercase small font-weight-bold text-muted mb-1"><?= lang('Sparks.active_anvils') ?></p>
                <small class="text-muted d-block"><?= lang('Sparks.anvils_description') ?></small>
            </div>
            <div class="icon">
                <i class="fas fa-server text-info opacity-25" style="font-size: 50px; position: absolute; right: 15px; top: 15px;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0"><?= lang('Sparks.recent_sparks') ?></h5>
                <a href="<?= base_url('admin/queue') ?>" class="btn btn-xs btn-outline-info"><?= lang('Sparks.view_chronicle') ?></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light text-uppercase small text-muted">
                            <tr>
                                <th><?= lang('Sparks.to') ?></th>
                                <th><?= lang('Sparks.subject') ?></th>
                                <th><?= lang('Sparks.status') ?></th>
                                <th><?= lang('Sparks.time') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent as $email): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($email->to_name ?: lang('Sparks.no_name')) ?></strong><br>
                                    <small class="text-muted"><?= esc($email->to_email) ?></small>
                                </td>
                                <td><?= esc($email->subject) ?></td>
                                <td>
                                    <?php if ($email->status === 'sent'): ?>
                                        <span class="badge badge-success px-2"><?= lang('Sparks.ignited') ?></span>
                                    <?php elseif ($email->status === 'failed'): ?>
                                        <span class="badge badge-danger px-2"><?= lang('Sparks.extinguished') ?></span>
                                    <?php elseif ($email->scheduled_at && strtotime($email->scheduled_at) > time()): ?>
                                        <span class="badge badge-info px-2"><?= lang('Sparks.scheduled') ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-white px-2"><?= lang('Sparks.heating') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="d-block"><strong><?= lang('Sparks.created') ?>:</strong> <?= $email->created_at ?></small>
                                    <?php if ($email->scheduled_at): ?>
                                    <small class="d-block text-primary"><strong><?= lang('Sparks.scheduled') ?>:</strong> <?= $email->scheduled_at ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recent)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted"><?= lang('Sparks.no_queue_records') ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12 text-center">
        <form action="<?= base_url('worker.php') ?>" method="get" target="_blank">
            <input type="hidden" name="key" value="<?= env('worker.secret_key') ?>">
            <button type="submit" class="btn btn-primary px-5 py-3 shadow-lg">
                <i class="fas fa-play mr-2"></i> <?= lang('Sparks.ignite_queue') ?>
            </button>
        </form>
        <p class="text-muted small mt-3"><?= lang('Sparks.worker_help') ?></p>
    </div>
</div>
<?= $this->endSection() ?>
