<?php $this->extend('shared_page/template'); ?>

<?php $this->section('content'); ?>
<div class="card-body">
    <form id="detailForm" method="POST" action="<?= base_url('pokja/pengadaan/update_pengadaan/' . $pengadaan['id']); ?>">
        <table class="table table-bordered">
            <tr>
                <th>Nama Pengadaan</th>
                <td>
                    <input type="text" name="nama_pengadaan" value="<?= $pengadaan['nama_pengadaan']; ?>" id="nama_pengadaan" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Metode Pemilihan</th>
                <td>
                    <select name="ref_tabel" id="ref_tabel" class="form-control" disabled>
                        <option value="1" <?= ($pengadaan['ref_tabel'] == '1') ? 'selected' : '' ?>>Pengadaan/Penunjukkan Langsung</option>
                        <option value="2" <?= ($pengadaan['ref_tabel'] == '2') ? 'selected' : '' ?>>Tender</option>
                        <option value="3" <?= ($pengadaan['ref_tabel'] == '3') ? 'selected' : '' ?>>E-Purchasing</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>PPK</th>
                <td>
                    <input type="text" name="ppk" value="<?= $pengadaan['ppk']; ?>" id="ppk" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Pokja/PP</th>
                <td>
                    <input type="text" name="pokja_pp" value="<?= $pengadaan['pokja_pp']; ?>" id="pokja_pp" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Nama Penyedia</th>
                <td>
                    <input type="text" name="nama_penyedia" value="<?= $pengadaan['nama_penyedia']; ?>" id="nama_penyedia" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Tanggal Mulai</th>
                <td>
                    <input type="date" name="tanggal_mulai" value="<?= date('Y-m-d', strtotime($pengadaan['tanggal_mulai'])); ?>" id="tanggal_mulai" class="form-control" readonly>
                </td>
            </tr>
            <tr>
                <th>Tanggal Berakhir</th>
                <td>
                    <input type="date" name="tanggal_berakhir" value="<?= date('Y-m-d', strtotime($pengadaan['tanggal_berakhir'])); ?>" id="tanggal_berakhir" class="form-control" readonly>
                </td>
            </tr>
        </table>

        <div class="d-flex">
            <a href="<?= base_url('pokja/home'); ?>" class="btn btn-secondary mr-2">Kembali</a>
            <button type="button" id="editBtn" class="btn btn-warning" onclick="editData()">Edit</button>
            <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Simpan</button>
        </div>
    </form>
</div>

<!-- Container Baru: Tabel Dokumen -->
<div class="card mt-4">
    <div class="card-header">
        <h5>Daftar Dokumen</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th>Nama Dokumen</th>
                    <th>Dokumen</th>
                    <th>Waktu Terakhir Unggah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            
        </table>
    </div>
</div>

<script>
    function editData() {
        document.getElementById('saveBtn').style.display = 'inline-block';
        document.getElementById('editBtn').style.display = 'none';
        document.getElementById('nama_pengadaan').removeAttribute('readonly');
        document.getElementById('ref_tabel').removeAttribute('disabled');
        document.getElementById('ppk').removeAttribute('readonly');
        document.getElementById('pokja_pp').removeAttribute('readonly');
        document.getElementById('nama_penyedia').removeAttribute('readonly');
        document.getElementById('tanggal_mulai').removeAttribute('readonly');
        document.getElementById('tanggal_berakhir').removeAttribute('readonly');
    }
</script>

<?php $this->endSection(); ?>
