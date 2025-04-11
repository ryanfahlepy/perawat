<table class="table table-bordered table-striped" id="example1">
    <thead>
        <tr class="text-center">
            <th>No</th>
            <th>Photo</th>
            <th>Nama Pengguna</th>
            <th>Username</th>
            <th>Level Pengguna</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($data as $dt): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <div class="image">
                        <img src="/assets/dist/img/user/<?= $dt->photo; ?>" class="img-circle" width="50" alt="User Image">
                    </div>
                </td>
                <td><?= $dt->nama; ?></td>
                <td><?= $dt->username; ?></td>
                <td><?= $dt->nama_level; ?></td>
                <?php
                $color = ($dt->status == 'Aktif') ? 'bg-info' : 'bg-danger' ?>
                <td>
                    <badge class="badge <?= $color; ?>"><?= $dt->status; ?></badge>
                </td>
                <?php
                if ($dt->status == 'Aktif') {
                    $tooltip = 'nonaktifkan';
                    $aksi = 'nonaktif';
                    $icon = 'fas fa-exclamation-triangle';
                    $color = 'btn-warning';
                } else {
                    $tooltip = 'aktifkan';
                    $aksi = 'aktif';
                    $icon = 'fas fa-check-circle';
                    $color = 'btn-success';
                }
                ?>
                <td>
                    <a href="#" type="button" class="btn btn-sm btn-primary btn-edituser" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Edit" data-id="<?= $dt->id; ?>">
                        <i class="far fa-fw fa-edit"></i>
                    </a>
                    <a href="#" class="btn btn-sm ml-2 <?= $color; ?> btn-user<?= $tooltip ?>" data-bs-toggle="tooltip"
                        data-bs-placement="top" title=<?= $tooltip; ?> data-id="<?= $dt->id ?>">
                        <i class="<?= $icon; ?>"></i>
                    </a>
                    <a href="#" class="btn btn-sm ml-2 btn-danger btn-hapususer" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Hapus" data-id="<?= $dt->id ?>">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- Data Table -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": true,
        });
    });
    $(document).on("submit", "#edituser", function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Apakah Anda yakin ingin menyimpan perubahan data pengguna?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var data_user = new FormData($("#edituser")[0]);

                $.ajax({
                    method: "post",
                    url: $("#edituser").attr("action"),
                    data: data_user,
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (responds) {
                        if (responds.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: responds.psn,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            tampil_tabel_user();
                        } else {
                            toastr.error('Data belum valid, ulangi lagi');
                            $.each(responds.errors, function (key, value) {
                                $('[name="' + key + '"]').addClass('is-invalid');
                                $('[name="' + key + '"]').next().text(value);
                                if (value == "") {
                                    $('[name="' + key + '"]').removeClass('is-invalid');
                                    $('[name="' + key + '"]').addClass('is-valid');
                                }
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Terjadi kesalahan saat mengirim data.',
                        });
                    }
                });
            }
        });
    });

    $(document).on("click", ".btn-edituser", function () {
        var id_user = $(this).data("id");

        Swal.fire({
            title: 'Edit Data Pengguna?',
            text: "Apakah Anda yakin ingin mengubah data pengguna ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/manuser/form_edit",
                    method: "POST",
                    data: { id: id_user },
                    dataType: "json",
                    success: function (responds) {
                        $('.tampil').html(responds);
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: 'Terjadi kesalahan saat memuat data.',
                        });
                    }
                });
            }
        });
    });

    $(document).on("click", ".btn-hapususer", function () {
        var id_user = $(this).data("id");

        Swal.fire({
            title: 'Yakin ingin menghapus pengguna ini?',
            text: "Data akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/manuser/hapus",
                    method: "POST",
                    data: { id: id_user },
                    dataType: "json",
                    success: function (responds) {
                        if (responds.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: responds.psn,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            tampil_tabel_user();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Data gagal dihapus',
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on("click", ".btn-useraktifkan", function () {
        var id_user = $(this).data("id");

        Swal.fire({
            title: 'Konfirmasi',
            text: "Yakin ingin mengaktifkan pengguna ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/manuser/aktif",
                    data: { id: id_user },
                    dataType: "json",
                    success: function (responds) {
                        if (responds.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: responds.psn,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            tampil_tabel_user();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Data gagal diaktifkan'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan koneksi ke server.'
                        });
                    }
                });
            }
        });
    });
    $(document).on("click", ".btn-usernonaktifkan", function () {
        var id_user = $(this).data("id");

        Swal.fire({
            title: 'Konfirmasi',
            text: "Yakin ingin menonaktifkan pengguna ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, nonaktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/manuser/nonaktif",
                    method: "POST",
                    data: { id: id_user },
                    dataType: "json",
                    success: function (responds) {
                        if (responds.status) {
                            toastr.success(responds.psn);
                            tampil_tabel_user();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Data gagal dinonaktifkan.'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengirim permintaan.'
                        });
                    }
                });
            }
        });
    });


</script>