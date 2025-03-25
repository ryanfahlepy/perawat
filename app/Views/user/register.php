<?php
$session = \Config\Services::session();
?>
<?= $this->include('shared_page/header'); ?>

<body class="hold-transition login-page">
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
            e.preventDefault()
            $.ajax({
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                success: function (responds) {
                    if (responds.validasi) {
                        if (responds.simpan) {
                            toastr.success('Selamat, Registrasi anda berhasil, segera hubungi admin untuk minta aktifasi akun anda')
                            setTimeout(
                                function () {
                                    window.location.href = "/login"
                                },
                                3000);
                        } else {
                            toastr.error('Registrasi gagal, password & konfirmasinya tidak sama, ulangi lagi')
                        }
                    } else {
                        toastr.error('Data belum valid, ulangi lagi')
                        $.each(responds.errors, function (key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid')
                            $('[name="' + key + '"]').next().text(value)
                            if (value == "") {
                                $('[name="' + key + '"]').removeClass('is-invalid')
                                $('[name="' + key + '"]').addClass('is-valid')
                            }
                        })
                    }
                }
            });
        })
    </script>

    <script type="text/javascript">
        $(function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $('.toastrDefaultSuccess').click(function () {
                toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultInfo').click(function () {
                toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultError').click(function () {
                toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultWarning').click(function () {
                toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
        });
    </script>
</body>

</html>