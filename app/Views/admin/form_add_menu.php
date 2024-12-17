<?= form_open('/admin/manmenu/simpan', ['id' => 'menu']) ?>
<div class="card-body">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Menu</h3>
        </div>
        <div class="card-body">
            <div class="chart">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label for="nama_menu">Nama Menu</label>
                    <input type="text" name="nama_menu" id="nama_menu" class="form-control" placeholder="Enter Nama Menu">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="url">Url Menu</label>
                    <input type="text" name="url" id="url" class="form-control" placeholder="Enter url">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="icon">Icon Menu</label>
                    <input type="text" name="icon" id="icon" class="form-control" placeholder="Enter Icon">
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
                    <input type="text" name="urutan" id="urutan" class="form-control" placeholder="isi dengan angka bulat">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="row form-group">
                    <button type="submit" class="btn btn-primary ml-3 tombol">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>