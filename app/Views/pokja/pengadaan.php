<?php $this->extend('shared_page/template'); ?>

<?php $this->section('content'); ?>
<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card-body">
<a href="<?= base_url('pengadaan/tambah_pengadaan') ?>" class="btn btn-primary">
    Tambah
</a>
<hr>
<div class="mb-3">
    <input type="text" id="searchPengadaan" class="form-control" placeholder="Cari data pengadaan..." onkeyup="cariPengadaan()">
</div>

    <div class="table-responsive">
    <table id="tablePengadaan" class="table table-bordered table-striped">

            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Pengadaan</th>
                    <!-- <th>Ref Tabel</th>
                    <th>PPK</th>
                    <th>Pokja PP</th> -->
                    <th>Nama Penyedia</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Progress</th>
                 
                    <th>Aksi</th>
                    
                </tr>
            </thead>
            <tbody >
                <?php $no = 1; ?>
                <?php foreach ($pengadaanData as $data): ?>
                <tr>
                    <td class="text-center" ><?= $no++; ?></td>
                    <td><?= $data['nama_pengadaan']; ?></td>
                    <!-- <td><?= $data['ref_tabel']; ?></td>
                    <td><?= $data['ppk']; ?></td>
                    <td><?= $data['pokja_pp']; ?></td> -->
                    <td><?= $data['nama_penyedia']; ?></td>
                    <td><?= date('d/m/Y', strtotime($data['tanggal_mulai'])); ?></td>
                    <td><?= date('d/m/Y', strtotime($data['tanggal_berakhir'])); ?></td>
                    <td>
    <!-- Tampilkan Progress dalam bentuk Persentase -->
    <div style="width: 100%; background-color: #e0e0e0; border-radius: 5px; overflow: hidden;">
        <div 
            style="width: <?= $data['progress']; ?>%; background-color: <?= $data['progress'] >= 75 ? 'green' : ($data['progress'] >= 50 ? 'yellow' : 'red'); ?>; height: 20px;">
        </div>
    </div>
    <div style="text-align: center;"><?= $data['progress']; ?>%</div>
</td>

                  
                
                    <td>
    <div class="center d-flex gap-2 justify-content-center">
        <a href="<?= base_url('/pengadaan/detail_pengadaan/' . $data['id']); ?>" class="btn btn-info btn-sm m-1">
            <i class="fas fa-info-circle"></i> <!-- Mengganti icon edit dengan icon detail -->
        </a>
        <a href="<?= base_url('/pengadaan/hapus_pengadaan/' . $data['id']); ?>" class="btn btn-danger btn-sm m-1" onclick="return confirm('Yakin ingin menghapus?');">
    <i class="fas fa-trash"></i>
</a>

    </div>
</td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function cariPengadaan() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchPengadaan");
    filter = input.value.toLowerCase();
    table = document.getElementById("tablePengadaan");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) { // Mulai dari 1 biar skip thead
        td = tr[i].getElementsByTagName("td");
        let rowVisible = false;
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    rowVisible = true;
                    break;
                }
            }
        }
        tr[i].style.display = rowVisible ? "" : "none";
    }
}

</script>   
<?php $this->endSection(); ?>
