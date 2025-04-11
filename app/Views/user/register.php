<?php
$session = \Config\Services::session();
?>
<?= $this->include('shared_page/header'); ?>
<head>
<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    .wrapper-center {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
</style>

</head>

<body class="hold-transition login-page"
    style="margin: 0; background-image: url('/assets/dist/img/banner.jpeg'); background-size: cover; background-position: center;">
    <div class="wrapper-center">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <h4><b>FORM REGISTER</b></h4>
                </div>
                <?= form_open('/login/simpanregister', ['id' => 'register']) ?>
                <div class="card-body">

                    <?= csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" name="nama" class="form-control" placeholder="Masukan nama" autofocus>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Masukan username">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password1" class="form-control" placeholder="Masukan Password">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password2" class="form-control"
                            placeholder="Masukan Konfirmasi Password">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="input-group mb-3">
                        <select name="level" id="level" class="form-control">
                            <option value="">--- Pilih Level ---</option>
                            <?php foreach ($dtlevel as $dt): ?>
                                <?php if ($dt->id !== '1'): ?>
                                    <option value="<?= $dt->id ?>"><?= $dt->nama_level ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="row">
                        <div class="col mt-3">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                    <div class="social-auth-links text-center">
                        <small class="text-danger">Sudah punya akun ? Silahkan <a href="/login">Login</a></small>
                    </div>
                </div>
                <!-- /.card-body -->
                <?= form_close() ?>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="/assets/plugins/toastr/toastr.min.js"></script>

    <script>
        $(document).on("submit", "#register", function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function (responds) {
                    if (responds.validasi) {
                        if (responds.simpan) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registrasi Berhasil!',
                                text: 'Selamat, registrasi anda berhasil. Segera hubungi admin untuk aktivasi akun.',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            setTimeout(function () {
                                window.location.href = "/login";
                            }, 3000);

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Password dan konfirmasi tidak sama. Silakan ulangi.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Tidak Valid!',
                            text: 'Silakan periksa kembali isian Anda.',
                            confirmButtonColor: '#d33'
                        });

                        $.each(responds.errors, function (key, value) {
                            const inputField = $('[name="' + key + '"]');
                            inputField.addClass('is-invalid');
                            inputField.next('.invalid-feedback').text(value);
                            if (value === "") {
                                inputField.removeClass('is-invalid').addClass('is-valid');
                            }
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Server!',
                        text: 'Terjadi masalah saat mengirim data. Coba lagi nanti.',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });
    </script>

</body>

</html>