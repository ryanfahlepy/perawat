<div class="card-body">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Edit Menu</h3>
        </div>
        <?= form_open('/admin/manmenu/update', ['id' => 'editmenu']) ?>
        <div class="card-body">
            <div class="chart">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_menu" id="id_menu" value="<?= $data->id ?>">
                <div class="form-group">
                    <label for="nama_menu">Nama Menu</label>
                    <input type="text" name="nama_menu" id="nama_menu" class="form-control" value="<?= $data->nama ?>">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="url">Url Menu</label>
                    <input type="text" name="url" id="url" class="form-control" value="<?= $data->url ?>">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="icon">Icon Menu</label>
                    <input type="text" name="icon" id="icon" class="form-control" value="<?= $data->icon ?>">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="level_id">Level User</label>
                    <select name="level_id" id="level_id" class="form-control">
                        <option value="">--- Pilih Level User ---</option>
                        <?php foreach ($level as $lvl) : ?>
                            <option value="<?= $lvl->id ?>"> <?= $lvl->nama_level ?> </option>
                        <?php endforeach ?>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="urutan">Nomer Urutan Menu</label>
                    <input type="text" name="urutan" id="urutan" class="form-control" value="<?= $data->urutan ?>">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="row form-group">
                    <button type="submit" class="btn btn-primary ml-3 tombol">Simpan</button>
                </div>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>