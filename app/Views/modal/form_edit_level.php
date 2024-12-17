<div class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Edit Level</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('/admin/manlevel/update', ['id' => 'form_edit']) ?>
            <div class="modal-body">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="id_level" id="id_level" value="<?= $data->id ?>">
                        <div class="form-group">
                            <label for="nama_level">Nama Level</label>
                            <input type="text" name="nama_level" id="nama_level" class="form-control" value="<?= $data->nama_level ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Deskripsi Level</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" value="<?= $data->keterangan ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>