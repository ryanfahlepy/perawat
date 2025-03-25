<?= $this->include('shared_page/header'); ?>

<body class="hold-transition login-page" style="background-image: url('/assets/dist/img/banner.jpeg'); background-size: cover; background-position: center;">
<style>
  .title {
    color: white;
    font-size: 2rem; /* Ukuran default */
    text-align: center;
    font-weight: bold;
  }

  /* Jika layar lebih kecil dari 768px, ukuran font akan mengecil */
  @media (max-width: 768px) {
    .title {
      font-size: 1.2rem;
    }
  }

  /* Jika layar lebih kecil dari 480px, ukuran font akan lebih kecil lagi */
  @media (max-width: 480px) {
    .title {
      font-size: 1rem;
    }
  }
</style>

<h2 class="title">SISTEM INFORMASI MANAJEMEN PENGARSIPAN DOKUMEN</h2>
<h2 class="title">PENGADAAN BARANG/JASA DISINFOLAHTAL</h2>


    <div class="login-box">
        <div class="login-logo">
            
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <!-- <p class="login-box-msg"><b>Silahkan Login Dahulu</b></p> -->
                <?= $this->include('shared_page/alert') ?>
                <div class="text-center mb-4">
                    <img src="/assets/dist/img/logo.png" width="200" class="img-circle" alt="User Image">
                </div>
                <form action="/login/ceklogin" method="POST">
                    <?= csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : ''; ?>" value="<?= old('username'); ?>" placeholder="Masukan username" autofocus required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            <?= $validation->getError('username'); ?>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : ''; ?>" placeholder="Masukan Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            <?= $validation->getError('password'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mt-3">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                </form>
            </div>
            <div class="social-auth-links text-center">
                <small class="text-danger">Belum punya akun ? <a href="/login/register">Registrasi</a></small>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="/assets/plugins/toastr/toastr.min.js"></script>

    <script type="text/javascript">
        $(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $('.toastrDefaultSuccess').click(function() {
                toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultInfo').click(function() {
                toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
            $('.toastrDefaultError').click(function() {
                toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            });
        });
    </script>

</body>

</html>