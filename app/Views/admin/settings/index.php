<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?><?= lang('Sparks.system_settings') ?><?= $this->endSection() ?>
<?= $this->section('header') ?><?= lang('Sparks.system_settings') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="<?= base_url('admin/settings/save') ?>" method="post">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php foreach ($settings as $s): ?>
                    <div class="form-group mb-4">
                        <label class="text-dark font-weight-bold">
                            <?php 
                                $translation = lang('Sparks.' . $s->key);
                                echo ($translation !== 'Sparks.' . $s->key) ? $translation : ucwords(str_replace('_', ' ', $s->key));
                            ?>
                        </label>
                        <?php if ($s->type === 'int'): ?>
                            <input type="number" name="settings[<?= $s->key ?>]" class="form-control" value="<?= esc($s->value) ?>">
                        <?php elseif ($s->type === 'bool'): ?>
                            <select name="settings[<?= $s->key ?>]" class="form-control">
                                <option value="1" <?= $s->value == '1' ? 'selected' : '' ?>><?= lang('Sparks.enabled') ?></option>
                                <option value="0" <?= $s->value == '0' ? 'selected' : '' ?>><?= lang('Sparks.disabled') ?></option>
                            </select>
                        <?php else: ?>
                            <input type="text" name="settings[<?= $s->key ?>]" class="form-control" value="<?= esc($s->value) ?>">
                        <?php endif; ?>
                        <small class="text-muted"><?= lang('Sparks.id') ?>: <code><?= $s->key ?></code></small>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer bg-light text-right">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm"><?= lang('Sparks.save_all_changes') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
