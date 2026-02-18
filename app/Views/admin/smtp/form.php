<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?><?= ($server->id ? lang('Sparks.edit') : lang('Sparks.new')) . ' ' . lang('Sparks.smtp_servers') ?><?= $this->endSection() ?>
<?= $this->section('header') ?><?= ($server->id ? lang('Sparks.edit') : lang('Sparks.new')) . ' ' . lang('Sparks.smtp_servers') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <form action="<?= base_url('admin/smtp/' . ($server->id ? 'update/' . $server->id : 'create')) ?>" method="post">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row">
                        <!-- Basic Info -->
                        <div class="col-md-6 border-right">
                            <h5 class="text-muted mb-4 font-weight-bold"><?= lang('Sparks.basic_config') ?></h5>
                            <div class="form-group">
                                <label><?= lang('Sparks.server_label') ?></label>
                                <input type="text" name="name" class="form-control" value="<?= old('name', $server->name) ?>" placeholder="e.g. Gmail Main">
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label><?= lang('Sparks.host') ?></label>
                                        <input type="text" name="host" class="form-control" value="<?= old('host', $server->host) ?>" placeholder="smtp.example.com">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?= lang('Sparks.port') ?></label>
                                        <input type="number" name="port" class="form-control" value="<?= old('port', $server->port ?? 587) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= lang('Sparks.encryption') ?></label>
                                        <select name="encryption" class="form-control">
                                            <option value="none" <?= $server->encryption == 'none' ? 'selected' : '' ?>><?= lang('Sparks.none') ?></option>
                                            <option value="tls" <?= $server->encryption == 'tls' ? 'selected' : '' ?>>TLS</option>
                                            <option value="ssl" <?= $server->encryption == 'ssl' ? 'selected' : '' ?>>SSL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= lang('Sparks.priority') ?></label>
                                        <input type="number" name="priority" class="form-control" value="<?= old('priority', $server->priority ?? 0) ?>">
                                        <small class="text-muted"><?= lang('Sparks.priority_help') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Auth Info -->
                        <div class="col-md-6">
                            <h5 class="text-muted mb-4 font-weight-bold"><?= lang('Sparks.auth_limits') ?></h5>
                            <div class="form-group">
                                <label><?= lang('Sparks.auth_type') ?></label>
                                <select name="auth_type" id="auth_type" class="form-control">
                                    <option value="basic" <?= $server->auth_type == 'basic' ? 'selected' : '' ?>><?= lang('Sparks.basic_auth') ?></option>
                                    <option value="oauth2" <?= $server->auth_type == 'oauth2' ? 'selected' : '' ?>><?= lang('Sparks.oauth2_auth') ?></option>
                                </select>
                            </div>

                            <div id="basicAuthSection" <?= $server->auth_type == 'oauth2' ? 'style="display:none;"' : '' ?>>
                                <div class="form-group">
                                    <label><?= lang('Sparks.username_email') ?></label>
                                    <input type="text" name="username" class="form-control" value="<?= old('username', $server->username) ?>">
                                </div>
                                <div class="form-group">
                                    <label><?= lang('Sparks.password') ?> <?= $server->id ? '<small class="text-muted">' . lang('Sparks.password_help') . '</small>' : '' ?></label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>

                            <div id="oauth2AuthSection" <?= $server->auth_type != 'oauth2' ? 'style="display:none;"' : '' ?>>
                                <div class="form-group">
                                    <label><?= lang('Sparks.oauth_provider') ?></label>
                                    <select name="oauth_provider" class="form-control">
                                        <option value="microsoft" <?= $server->oauth_provider == 'microsoft' ? 'selected' : '' ?>>Microsoft (Outlook/365)</option>
                                        <option value="google" <?= $server->oauth_provider == 'google' ? 'selected' : '' ?>>Google (Gmail/Workspace)</option>
                                        <option value="dummy" <?= $server->oauth_provider == 'dummy' ? 'selected' : '' ?>>Template Provider (Dummy)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><?= lang('Sparks.client_id') ?></label>
                                    <input type="text" name="client_id" class="form-control" value="<?= old('client_id', $server->client_id) ?>">
                                </div>
                                <div class="form-group">
                                    <label><?= lang('Sparks.client_secret') ?></label>
                                    <input type="password" name="client_secret" class="form-control" placeholder="<?= $server->client_secret ? '********' : '' ?>">
                                </div>
                                
                                <div class="alert alert-info py-2 small">
                                    <strong><?= lang('Sparks.redirect_uri') ?>:</strong><br>
                                    <code><?= base_url('admin/smtp/oauth-callback') ?></code><br>
                                    <span class="text-muted"><?= lang('Sparks.oauth_help') ?></span>
                                </div>

                                <?php if ($server->id && $server->auth_type == 'oauth2'): ?>
                                    <div class="mb-3">
                                        <span class="badge badge-<?= $server->refresh_token ? 'success' : 'danger' ?> p-2">
                                            <?= $server->refresh_token ? lang('Sparks.status_authorized') : lang('Sparks.status_not_authorized') ?>
                                        </span>
                                        <a href="<?= base_url('admin/smtp/authorize/' . $server->id) ?>" class="btn btn-warning btn-sm ml-2">
                                            <i class="fas fa-key"></i> <?= lang('Sparks.authorize_now') ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= lang('Sparks.max_daily') ?></label>
                                        <input type="number" name="max_daily" class="form-control" value="<?= old('max_daily', $server->max_daily ?? 1000) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?= lang('Sparks.max_hourly') ?></label>
                                        <input type="number" name="max_hourly" class="form-control" value="<?= old('max_hourly', $server->max_hourly ?? 100) ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="isActive" value="1" <?= $server->is_active === null || $server->is_active ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="isActive"><?= lang('Sparks.server_is_active') ?></label>
                            </div>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" name="connection_pooling" class="custom-control-input" id="pooling" value="1" <?= $server->connection_pooling ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="pooling"><?= lang('Sparks.pooling_help') ?></label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="text-muted mb-4 font-weight-bold"><?= lang('Sparks.sender_identity') ?></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= lang('Sparks.from_email') ?></label>
                                <input type="email" name="from_email" class="form-control" value="<?= old('from_email', $server->from_email) ?>" placeholder="noreply@domain.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?= lang('Sparks.from_name') ?></label>
                                <input type="text" name="from_name" class="form-control" value="<?= old('from_name', $server->from_name) ?>" placeholder="Company Name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <a href="<?= base_url('admin/smtp') ?>" class="btn btn-link text-muted mr-3"><?= lang('Sparks.cancel') ?></a>
                    <button type="submit" class="btn btn-primary px-5"><?= lang('Sparks.save_config') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('auth_type').addEventListener('change', function() {
    const isOAuth = this.value === 'oauth2';
    document.getElementById('basicAuthSection').style.display = isOAuth ? 'none' : 'block';
    document.getElementById('oauth2AuthSection').style.display = isOAuth ? 'block' : 'none';
});
</script>
<?= $this->endSection() ?>
