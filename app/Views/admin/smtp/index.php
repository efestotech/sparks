<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?><?= lang('Sparks.smtp_servers') ?><?= $this->endSection() ?>
<?= $this->section('header') ?><?= lang('Sparks.smtp_servers') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 text-right mb-3">
        <a href="<?= base_url('admin/smtp/new') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus mr-1"></i> <?= lang('Sparks.add_new_anvil') ?>
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="row">
    <?php foreach ($servers as $server): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-server mr-2 text-primary opacity-50"></i>
                    <?= esc($server->name) ?>
                </h5>
                <div class="card-tools">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="toggle-<?= $server->id ?>" <?= $server->is_active ? 'checked' : '' ?> onclick="toggleStatus(<?= $server->id ?>)">
                        <label class="custom-control-label text-xs" for="toggle-<?= $server->id ?>">
                            <span id="status-text-<?= $server->id ?>" class="badge <?= $server->is_active ? 'badge-success' : 'badge-secondary' ?>">
                                <?= $server->is_active ? lang('Sparks.active') : lang('Sparks.inactive') ?>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body py-2">
                <p class="text-sm text-muted mb-2">
                    <i class="fas fa-link mr-1"></i> <?= esc($server->host) ?>:<?= $server->port ?> 
                    <span class="text-uppercase ml-1"><?= $server->encryption ?></span>
                </p>
                
                <div class="progress-group mb-3">
                    <small><?= lang('Sparks.daily_limit') ?> (<?= $server->daily_sent ?> / <?= $server->max_daily ?>)</small>
                    <div class="progress progress-sm" style="height: 6px; border-radius: 3px;">
                        <?php $perDaily = ($server->daily_sent / max(1, $server->max_daily)) * 100; ?>
                        <div class="progress-bar bg-primary" style="width: <?= $perDaily ?>%"></div>
                    </div>
                </div>

                <div class="progress-group mb-0">
                    <small><?= lang('Sparks.hourly_limit') ?> (<?= $server->hourly_sent ?> / <?= $server->max_hourly ?>)</small>
                    <div class="progress progress-sm" style="height: 6px; border-radius: 3px;">
                        <?php $perHourly = ($server->hourly_sent / max(1, $server->max_hourly)) * 100; ?>
                        <div class="progress-bar bg-info" style="width: <?= $perHourly ?>%"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 pt-0">
                <hr class="mt-2 mb-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="<?= base_url('admin/smtp/edit/' . $server->id) ?>" class="btn btn-sm btn-outline-primary"><?= lang('Sparks.edit') ?></a>
                        <button onclick="testConnection(<?= $server->id ?>)" class="btn btn-sm btn-outline-info ml-1"><?= lang('Sparks.test') ?></button>
                    </div>
                    <a href="<?= base_url('admin/smtp/delete/' . $server->id) ?>" 
                       class="btn btn-sm btn-outline-danger" 
                       onclick="return confirm('<?= lang('Sparks.are_you_sure') ?>')"><?= lang('Sparks.delete') ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php if (empty($servers)): ?>
    <div class="col-12 text-center py-5">
        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076402.png" alt="Empty" style="width: 120px; opacity: 0.3">
        <p class="text-muted mt-3"><?= lang('Sparks.no_anvils_found') ?></p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleStatus(id) {
    const statusText = document.getElementById(`status-text-${id}`);
    const checkbox = document.getElementById(`toggle-${id}`);
    
    fetch(`<?= base_url('admin/smtp/toggle') ?>/${id}`, {
        method: 'POST'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (data.new_status == 1) {
                statusText.className = 'badge badge-success';
                statusText.innerText = '<?= lang('Sparks.active') ?>';
                checkbox.checked = true;
            } else {
                statusText.className = 'badge badge-secondary';
                statusText.innerText = '<?= lang('Sparks.inactive') ?>';
                checkbox.checked = false;
            }
        } else {
            alert('Failed: ' + data.message);
            checkbox.checked = !checkbox.checked; // Revert
        }
    })
    .catch(() => {
        alert('Request failed');
        checkbox.checked = !checkbox.checked; // Revert
    });
}

function testConnection(id) {
    const testEmail = prompt("<?= lang('Sparks.enter_test_email') ?>", "");
    if (testEmail === null) return; // Cancelled

    // Find the button that triggered the event
    const btn = document.querySelector(`button[onclick="testConnection(${id})"]`);
    const oldHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch('<?= base_url('admin/smtp/test') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&test_email=${encodeURIComponent(testEmail)}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('<?= lang('Sparks.success') ?>: ' + data.message);
        } else {
            alert('<?= lang('Sparks.failed') ?>: ' + data.message);
        }
    })
    .catch(() => alert('<?= lang('Sparks.request_failed') ?>'))
    .finally(() => {
        btn.innerHTML = oldHtml;
        btn.disabled = false;
    });
}
</script>
<?= $this->endSection() ?>
