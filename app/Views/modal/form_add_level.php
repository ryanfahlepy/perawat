<div class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Tambah Level</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('/admin/manlevel/simpan', ['id' => 'form_add']) ?>
            <div class="modal-body">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="nama_level">Nama Level</label>
                            <input type="text" name="nama_level" id="nama_level" class="form-control" placeholder="Enter Nama Level">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Deskripsi Level</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan Akses Level">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>