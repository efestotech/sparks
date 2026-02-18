<?= $this->extend('layout/admin') ?>

<?= $this->section('title') ?><?= lang('Sparks.ignite_spark') ?><?= $this->endSection() ?>
<?= $this->section('header') ?><?= lang('Sparks.create_new_spark') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card card-outline">
            <div class="card-header bg-white">
                <h3 class="card-title"><i class="fas fa-magic mr-2 text-warning"></i><?= lang('Sparks.create_new_spark') ?></h3>
            </div>
            <form action="<?= base_url('admin/queue/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-0 border-0" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i><?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-0 border-0" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_email"><?= lang('Sparks.to') ?> (Email)*</label>
                                <input type="email" name="to_email" id="to_email" class="form-control" value="<?= old('to_email') ?>" placeholder="recipient@example.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_name"><?= lang('Sparks.to_name') ?> (<?= lang('Sparks.optional') ?>)</label>
                                <input type="text" name="to_name" id="to_name" class="form-control" value="<?= old('to_name') ?>" placeholder="Marco Spinelli">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject"><?= lang('Sparks.subject') ?>*</label>
                        <input type="text" name="subject" id="subject" class="form-control" value="<?= old('subject') ?>" placeholder="<?= lang('Sparks.subject_placeholder') ?? 'Enter subject' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="body_html"><?= lang('Sparks.body_html') ?? 'HTML Body' ?>*</label>
                        <textarea name="body_html" id="body_html" rows="10" class="form-control" required><?= old('body_html') ?></textarea>
                        <small class="text-muted"><?= lang('Sparks.html_hint') ?? 'Full HTML supported.' ?></small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority"><?= lang('Sparks.priority') ?></label>
                                <select name="priority" id="priority" class="form-control custom-select">
                                    <option value="1">1 - <?= lang('Sparks.high_priority') ?? 'High' ?></option>
                                    <option value="5" selected>5 - <?= lang('Sparks.medium_priority') ?? 'Medium' ?></option>
                                    <option value="9">9 - <?= lang('Sparks.low_priority') ?? 'Low' ?></option>
                                </select>
                                <small class="text-muted"><?= lang('Sparks.priority_help') ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduled_at"><?= lang('Sparks.scheduled_at') ?> (<?= lang('Sparks.optional') ?>)</label>
                                <input type="text" name="scheduled_at" id="scheduled_at" class="form-control" value="<?= old('scheduled_at') ?>" placeholder="YYYY-MM-DD HH:MM:SS">
                                <small class="text-muted"><?= lang('Sparks.scheduled_help') ?? 'Leave blank for immediate forging.' ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="<?= base_url('admin/queue') ?>" class="btn btn-default"><?= lang('Sparks.cancel') ?></a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-hammer mr-2"></i><?= lang('Sparks.ignite_spark') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- You could add a WYSIWYG editor here if needed, like Summernote or CKEditor -->
<?= $this->endSection() ?>
