<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?><?= lang('Sparks.user_mgmt') ?><?= $this->endSection() ?>
<?= $this->section('header') ?><?= lang('Sparks.users_api_keys') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom-0 bg-white">
                <h5 class="font-weight-bold mb-0"><?= lang('Sparks.create_edit_user') ?></h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/users/save') ?>" method="post">
                    <input type="hidden" name="id" id="userId">
                    <div class="form-group">
                        <label><?= lang('Sparks.guardian_username') ?></label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label><?= lang('Sparks.password') ?> <small class="text-muted"><?= lang('Sparks.password_help') ?></small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="custom-control custom-switch mb-4">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="userActive" value="1" checked>
                        <label class="custom-control-label" for="userActive"><?= lang('Sparks.active_account') ?></label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block shadow-sm"><?= lang('Sparks.save_user') ?></button>
                    <button type="button" onclick="resetForm()" class="btn btn-link btn-block text-muted btn-sm"><?= lang('Sparks.clear_form') ?></button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-top-0"><?= lang('Sparks.guardian_username') ?></th>
                                <th class="border-top-0"><?= lang('Sparks.api_key') ?></th>
                                <th class="border-top-0"><?= lang('Sparks.status') ?></th>
                                <th class="border-top-0"><?= lang('Sparks.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <span class="font-weight-bold"><?= esc($user->username) ?></span><br>
                                    <small class="text-muted">ID: #<?= $user->id ?></small>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm" style="max-width: 300px">
                                        <input type="text" class="form-control bg-light" value="<?= $user->api_key ?>" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('<?= $user->api_key ?>')" title="<?= lang('Sparks.copy') ?>"><i class="fas fa-copy"></i></button>
                                            <a href="<?= base_url('admin/users/generateKey/' . $user->id) ?>" class="btn btn-outline-warning" title="<?= lang('Sparks.regenerate') ?>" onclick="return confirm('<?= lang('Sparks.regenerate_confirm') ?>')"><i class="fas fa-sync"></i></a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $user->is_active ? 'success' : 'secondary' ?>">
                                        <?= $user->is_active ? lang('Sparks.active') : lang('Sparks.inactive') ?>
                                    </span>
                                </td>
                                <td>
                                    <button onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i></button>
                                    <a href="<?= base_url('admin/users/delete/' . $user->id) ?>" class="btn btn-xs btn-outline-danger ml-1" onclick="return confirm('<?= lang('Sparks.delete_user_confirm') ?>')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function editUser(user) {
    document.getElementById('userId').value = user.id;
    document.getElementById('username').value = user.username;
    document.getElementById('userActive').checked = user.is_active;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function resetForm() {
    document.getElementById('userId').value = '';
    document.getElementById('username').value = '';
    document.getElementById('userActive').checked = true;
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('<?= lang('Sparks.api_key_copied') ?>');
    });
}
</script>
<?= $this->endSection() ?>
