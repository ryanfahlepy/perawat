<?php
$session = \Config\Services::session();
?>
<div class="card-body">
    <?= $this->include('shared_page/alert'); ?>
    <div class="text-center">
        <img src="/assets/dist/img/user/<?= $session->photo ?>" class="img-circle" width="125" alt="User profil picture">
    </div>
    <h3 class="mt-5 profil-username text-center"><?= $session->nama; ?></h3>
</div>