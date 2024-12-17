<div class="card-body">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Reset Password</h3>
        </div>
        <?= form_open('/admin/manuser/reset_password', ['id' => 'rpassword']) ?>
        <div class="card-body">
            <div class="chart">
                <?= csrf_field(); ?>
                <input type="hidden" name="id" value="<?= $data[0]->id ?>">
                <div class="form-group">
                    <label for="pss1">Masukan Password</label>
                    <input type="password" name="pss1" class="form-control" autofocus>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="pss2">Konfirmasi Password</label>
                    <input type="password" name="pss2" class="form-control">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="row form-group">
                    <button type="submit" class="btn btn-primary ml-3">Update</button>
                </div>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>