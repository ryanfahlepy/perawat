<s?php
$session = \Config\Services::session();
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>

<div class="card-body">
    <!-- Filter untuk memilih jenis dokumen -->
    <form action="<?= current_url() ?>" method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="filter_type" class="form-control" onchange="this.form.submit()">
                    <option value="pl" <?= ($filter_type == 'pl') ? 'selected' : '' ?>>Dokumen Pengadaan/Penunjukkan Langsung</option>
                    <option value="tender" <?= ($filter_type == 'tender') ? 'selected' : '' ?>>Dokumen Tender</option>
                    <option value="ep" <?= ($filter_type == 'ep') ? 'selected' : '' ?>>Dokumen E-Purchasing</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Form untuk menampilkan semua nama dokumen -->
    <form action="<?= current_url() ?>" method="post">
    <input type="hidden" name="filter_type" value="<?= $filter_type ?>">
    <div class="row">
        <?php 
        if (!empty($dokumen_list)): 
            // Hitung jumlah dokumen per kolom
            $total = count($dokumen_list);
            $per_col = ceil($total / 3);

            // Bagi dokumen menjadi 3 bagian
            $columns = array_chunk($dokumen_list, $per_col);
            $global_index = 1; // Inisialisasi angka urutan
        ?>
            <!-- Loop untuk setiap kolom -->
            <?php foreach ($columns as $column): ?>
                <div class="col-md-4">
                    <?php foreach ($column as $dokumen): ?>
                        <div class="d-flex align-items-center mb-3">
                            <!-- Kotak angka -->
                            <div 
                                class="number-box text-center me-3" 
                                style="
                                    width: 40px; 
                                    height: 40px; 
                                    background-color: #f0f0f0; 
                                    border-radius: 50%; 
                                    font-weight: bold; 
                                    display: flex; 
                                    align-items: center; 
                                    justify-content: center;
                                    margin-right: 2px; /* Tambah jarak */
                                    margin-left: 2px; /* Tambah jarak */
                                ">
                                <?= $global_index++; ?>
                            </div>
                            <!-- Input dokumen -->
                            <div class="form-group flex-grow-1 mb-0">
                                <input 
                                    type="text" 
                                    class="form-control dokumen-input" 
                                    id="dokumen_<?= $dokumen['id_dokumen']; ?>" 
                                    name="dokumen[<?= $dokumen['id_dokumen']; ?>]" 
                                    value="<?= $dokumen['dokumen']; ?>" 
                                    readonly>
                            </div>
                            <!-- Tombol Hapus -->
                            <button type="button" class="btn delete-doc d-none" data-id="<?= $dokumen['id_dokumen']; ?>">
    <i class="fas fa-trash"></i>
</button>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="col-12">Tidak ada dokumen tersedia.</p>
        <?php endif; ?>
    </div>

    <!-- Tombol Edit/Cancel -->
    <button type="button" id="edit-btn" class="btn btn-primary mt-3">Edit</button>
    <button type="submit" id="submit-btn" class="btn btn-success mt-3" disabled>Submit</button>
</form>

</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('edit-btn').addEventListener('click', function() {
    const inputs = document.querySelectorAll('.dokumen-input');
    const submitBtn = document.getElementById('submit-btn');
    const editBtn = document.getElementById('edit-btn');
    const deleteButtons = document.querySelectorAll('.delete-doc'); // Ambil tombol delete
    
    // Check if the current button is "Edit" or "Cancel"
    if (editBtn.textContent === 'Edit') {
        // Convert inputs to editable
        inputs.forEach(input => {
            input.readOnly = false;
        });
        
        // Show the delete buttons (remove the 'd-none' class)
        deleteButtons.forEach(button => {
            button.classList.remove('d-none'); // Tampilkan tombol delete
            button.classList.add('active'); // Tampilkan tombol delete dengan warna aktif
        });

        // Change button text to "Cancel" and styles to danger
        editBtn.classList.remove('btn-primary');
        editBtn.classList.add('btn-danger');
        editBtn.textContent = 'Cancel';
        
        // Enable the "Submit" button
        submitBtn.disabled = false;
    } else {
        // Convert inputs back to read-only (like the initial state)
        inputs.forEach(input => {
            input.readOnly = true;
        });
        
        // Hide the delete buttons (add the 'd-none' class)
        deleteButtons.forEach(button => {
            button.classList.add('d-none'); // Sembunyikan tombol delete
            button.classList.remove('active'); // Hapus kelas aktif
        });

        // Change button text back to "Edit" and styles back to primary
        editBtn.classList.remove('btn-danger');
        editBtn.classList.add('btn-primary');
        editBtn.textContent = 'Edit';
        
        // Disable the "Submit" button
        submitBtn.disabled = true;
    }
});

document.querySelectorAll('.delete-doc').forEach(button => {
    button.addEventListener('click', function() {
        const docId = this.getAttribute('data-id');

        // Show the SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Dokumen ini akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirmed, send the delete request
                fetch('<?= base_url('lpse/templatedokumen/hapus') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_dokumen: docId })
                })
                .then(response => response.json())
                .then(data => {
                    // var_dump the data here for debugging
                    console.log(data); // This logs the response from the PHP controller
                    if (data.success) {
                        // Remove the document from the view
                        this.closest('.d-flex').remove();
                        Swal.fire(
                            'Terhapus!',
                            'Dokumen telah dihapus.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Gagal!',
                            'Dokumen gagal dihapus.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan saat menghapus dokumen.',
                        'error'
                    );
                });
            }
        });
    });
});




</script>

<style>
    .delete-doc i {
 /* Warna ikon saat tidak aktif */
    transition: color 0.3s ease;
}

.delete-doc.active i {
    background-color: transparent;
    color: red; /* Warna merah saat tombol aktif */
}

</style>

<?php $this->endSection(); ?>
