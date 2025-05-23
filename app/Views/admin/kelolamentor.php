<?php

$session = \Config\Services::session();
$level = $session->level;
?>
<?php $this->extend('shared_page/template'); ?>
<?php $this->section('content'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<div class="card-body">
    <h4>Daftar Level (3, 4, 5, 6)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Tingkat PK</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dataLevel)): ?>
                <?php foreach ($dataLevel as $level): ?>
                    <tr>
                        <td><?= esc($level->nama_level ?? '-') ?></td>
                        <td class="text-center">
                            <a href="<?= site_url('admin/manmentor/kelolaform/' . esc($level->id ?? 0)) ?>"
                                class="btn btn-info btn-sm w" title="Info">
                                <i class="fas fa-info-circle text-white"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="text-center">Tidak ada data user untuk level 4, 5, atau 6.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card-body">
    <h4>Daftar User (Level 2, 3, 4, 5, 6)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center align-middle" style="width: 70px;">Foto</th>
                <th class="text-center align-middle" style="width: 25%;">Nama</th>
                <th class="text-center align-middle" style="width: 25%;">Tingkat PK</th>
                <th class="text-center align-middle" style="width: 30%;">Mentor</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dataUser)): ?>
                <?php foreach ($dataUser as $user): ?>
                    <?php
                    $userId = esc($user['id']);
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php if (!empty($user['photo'])): ?>
                                <img src="<?= base_url('assets/dist/img/user/' . esc($user['photo'])) ?>" width="50" height="50"
                                    alt="User Photo">
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($user['nama'] ?? '-') ?></td>
                        <td><?= esc($user['nama_level'] ?? '-') ?></td>
                        <td class="text-center align-middle">
                            <form action="<?= site_url('admin/manmentor/setMentor') ?>" method="post" class="d-inline">
                                <input type="hidden" name="user_id" value="<?= $userId ?>">
                                <div class="position-relative" style="min-width: 220px;">
                                    <select name="mentor_id" class="form-select form-select-sm border rounded-pill px-3 py-2"
                                        onchange="this.form.submit()">
                                        <option value="" disabled <?= !isset($userMentorMapping[$userId]) ? 'selected' : '' ?>>
                                            Pilih Mentor</option>
                                        <?php foreach ($mentorOptions[$userId] as $mentor): ?>
                                            <option value="<?= esc($mentor['id']) ?>" <?= (isset($userMentorMapping[$userId]) && $userMentorMapping[$userId] == $mentor['id']) ? 'selected' : '' ?>>
                                                <?= esc($mentor['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data user.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateCountdownElement(el) {
            let intervalId;  // deklarasi intervalId di sini supaya bisa diakses dalam fungsi

            const deadlineStrRaw = el.dataset.deadline;

            if (!deadlineStrRaw || deadlineStrRaw === '0000-00-00T00:00:00') {
                el.innerHTML = "<span class='text-muted'>-</span>";
                return;
            }

            const deadline = new Date(deadlineStrRaw);
            if (isNaN(deadline.getTime())) {
                el.innerHTML = "<span class='text-muted'>-</span>";
                return;
            }

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = deadline.getTime() - now;

                if (distance <= 0) {
                    el.innerHTML = "<span class='badge bg-danger'>Waktu Habis</span>";
                    clearInterval(intervalId);  // clear interval jika waktu habis
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                el.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }

            updateCountdown();
            intervalId = setInterval(updateCountdown, 1000);
            el._intervalId = intervalId;  // simpan interval supaya bisa clear nanti
        }

        // Inisialisasi countdown untuk semua elemen
        const countdownElements = document.querySelectorAll('[id^="countdown-"]');
        countdownElements.forEach(el => updateCountdownElement(el));

        // Fungsi untuk update tanggal via AJAX
        function updateTanggal(userId, tanggalMulai, tanggalBerakhir) {
            fetch('<?= site_url('admin/manmentor/updateTanggal') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                body: new URLSearchParams({
                    user_id: userId,
                    tanggal_mulai: tanggalMulai,
                    tanggal_berakhir: tanggalBerakhir
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const countdownEl = document.querySelector(`#countdown-${userId}`);
                        if (tanggalBerakhir) {
                            const deadlineISO = new Date(tanggalBerakhir).toISOString();
                            countdownEl.dataset.deadline = deadlineISO;

                            // Reset interval countdown untuk elemen itu saja
                            if (countdownEl._intervalId) clearInterval(countdownEl._intervalId);
                            updateCountdownElement(countdownEl);
                        } else {
                            countdownEl.dataset.deadline = '';
                            countdownEl.innerHTML = "<span class='text-muted'>-</span>";
                        }

                    } else {
                        alert('Gagal update tanggal: ' + data.message);
                    }
                })
                .catch(() => alert('Terjadi kesalahan saat update tanggal'));
        }

        // Event onchange untuk input tanggal mulai
        document.querySelectorAll('.tanggal-mulai').forEach(input => {
            input.addEventListener('change', function () {
                const userId = this.dataset.userid;
                const tanggalMulai = this.value;
                const tanggalBerakhirInput = this.closest('tr').querySelector('.tanggal-berakhir');
                const tanggalBerakhir = tanggalBerakhirInput ? tanggalBerakhirInput.value : '';

                updateTanggal(userId, tanggalMulai, tanggalBerakhir);
            });
        });

        // Event onchange untuk input tanggal berakhir
        document.querySelectorAll('.tanggal-berakhir').forEach(input => {
            input.addEventListener('change', function () {
                const userId = this.dataset.userid;
                const tanggalBerakhir = this.value;
                const tanggalMulaiInput = this.closest('tr').querySelector('.tanggal-mulai');
                const tanggalMulai = tanggalMulaiInput ? tanggalMulaiInput.value : '';

                updateTanggal(userId, tanggalMulai, tanggalBerakhir);
            });
        });
    });

</script>
<style>
    .custom-select-wrapper {
        position: relative;
        width: 200px;
    }

    .custom-select-wrapper::after {
        content: '▼';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        font-size: 12px;
        color: #555;
    }

    .custom-select-wrapper select {
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 25px;
    }
</style>

<?php $this->endSection(); ?>