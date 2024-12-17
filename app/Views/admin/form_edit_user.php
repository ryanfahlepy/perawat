<div class="card-body">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Edit User</h3>
        </div>
        <?= form_open_multipart('/admin/manuser/update', ['id' => 'edituser']); ?>
        <div class="card-body">
            <div class="chart">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_user" value="<?= $data->id ?>">
                <div class="form-group">
                    <label for="nama">Nama User</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="<?= $data->nama ?>" autofocus>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="username">Username </label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= $data->username ?>" placeholder="Enter username">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="level_id">Level User</label>
                    <select name="level_id" id="level_id" class="form-control">
                        <option value="">--- Pilih Level User ---</option>
                        <?php foreach ($level as $lv) : ?>
                            <option value="<?= $lv->id ?>"> <?= $lv->nama_level ?> </option>
                        <?php endforeach ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input" id="photo">
                            <label class="custom-file-label" for="photo">Pilih photo</label>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text" id="">Upload</span>
                        </div>
                    </div>
                    <small class="form-text text-muted text-danger">*) Upload file photo user (.jpeg/.jpg/.png).</small>
                </div>
                <div class="row form-group">
                    <button type="submit" class="btn btn-primary ml-3 tombol">Simpan</button>
                </div>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>
<!-- bs-custom-file-input -->
<script src="/assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>