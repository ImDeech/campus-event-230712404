<?php
// Set header response
header('Content-Type: text/html; charset=UTF-8');

// Array untuk menyimpan pesan kesalahan dan status sukses
$errors = array();
$successMessage = "";

// Memastikan menggunakan request method POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Bersihkan dan ambil data dari form
    $fullName        = isset($_POST['fullName'])        ? trim(strip_tags($_POST['fullName'])) : '';
    $npm             = isset($_POST['npm'])             ? trim(strip_tags($_POST['npm'])) : '';
    $email           = isset($_POST['email'])           ? trim(strip_tags($_POST['email'])) : '';
    $phone           = isset($_POST['phone'])           ? trim(strip_tags($_POST['phone'])) : '';
    $studyProgram    = isset($_POST['studyProgram'])    ? trim(strip_tags($_POST['studyProgram'])) : '';
    $workshopSession = isset($_POST['workshopSession']) ? trim(strip_tags($_POST['workshopSession'])) : '';
    $notes           = isset($_POST['notes'])           ? trim(strip_tags($_POST['notes'])) : '';

    // 2. Server-side Validation Logic

    // Validasi nama lengkap (minimal 3 karakter)
    if (empty($fullName) || strlen($fullName) < 3) {
        $errors[] = "Nama Lengkap wajib diisi dan minimal 3 karakter.";
    }

    // Validasi NPM (minimal 9 digit)
    if (empty($npm) || !preg_match('/^\d{9}$/', $npm)) {
        $errors[] = "NPM harus berupa 9 digit angka (contoh: 210711234).";
    }

    // Validasi Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Alamat email tidak valid.";
    }

    // Validasi Nomor Telepon
    if (empty($phone) || !preg_match('/^\d{10,14}$/', $phone)) {
        $errors[] = "Nomor telepon harus berupa angka antara 10 hingga 14 digit.";
    }

    // Validasi Program Studi
    if (empty($studyProgram)) {
        $errors[] = "Silakan pilih Program Studi.";
    }

    // Validasi Sesi Workshop
    if (empty($workshopSession)) {
        $errors[] = "Silakan pilih Sesi Workshop.";
    }

    // Ganti baris baru dalam catatan agar tetap dalam format satu baris
    $notesClean = str_replace(array("\r", "\n", "|"), " ", $notes);
    if (empty($notesClean)) {
        $notesClean = "-";
    }

    // 3. Proses data (jika validasi sukses)
    if (empty($errors)) {
        $timestamp = date("Y-m-d H:i:s");
        $dataFile  = __DIR__ . '/../data/participants.txt';

        // Siapkan baris data
        // Format: Timestamp | NPM | Nama | Email | No Telepon | Program Studi | Sesi | Catatan
        $recordLine = "{$timestamp} | {$npm} | {$fullName} | {$email} | {$phone} | {$studyProgram} | {$workshopSession} | {$notesClean}" . PHP_EOL;

        // Pastikan direktori data ada
        $dataDir = dirname($dataFile);
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0755, true);
        }

        // Simpan data (tambah baris baru di participants.txt)
        if (file_put_contents($dataFile, $recordLine, FILE_APPEND | LOCK_EX) !== false) {
            $success = true;
        } else {
            $errors[] = "Gagal menyimpan data ke file data/participants.txt. Periksa hak akses folder.";
            $success = false;
        }
    } else {
        $success = false;
    }

} else {
    $errors[] = "Akses langsung tidak diizinkan. Silakan isi form pendaftaran.";
    $success = false;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <title>Status Pendaftaran - UAJY Technology Workshop 2026</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    <link rel="icon" type="image/png" href="../images/UAJY.png" />
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
    <div class="navbar-header home-navbar">
        <div class="header-container">
            <div class="logo-area">
                <img src="../images/UAJY.png" alt="Logo UAJY" class="home-logo" />
                <span class="logo-title">UAJY EVENT</span>
            </div>
            <ul class="nav-links">
                <li><a href="../index.xhtml">Beranda</a></li>
                <li><a href="../schedule.xhtml">Jadwal</a></li>
                <li><a href="../registration.xhtml">Pendaftaran</a></li>
                <li><a href="participants.php">Peserta</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="status-page">
        <div class="status-card">

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <h2>Pendaftaran Berhasil!</h2>
                    <p>Terima kasih, data pendaftaran Anda telah berhasil diproses dan disimpan ke file <code>data/participants.txt</code>.</p>
                </div>

                <h3 class="status-details-title">Rincian Data Pendaftaran:</h3>
                <table class="xhtml-table status-details-table">
                    <tr>
                        <th>Waktu Pendaftaran</th>
                        <td><?php echo htmlspecialchars($timestamp); ?></td>
                    </tr>
                    <tr>
                        <th>NPM</th>
                        <td><?php echo htmlspecialchars($npm); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td><?php echo htmlspecialchars($fullName); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($email); ?></td>
                    </tr>
                    <tr>
                        <th>No WhatsApp</th>
                        <td><?php echo htmlspecialchars($phone); ?></td>
                    </tr>
                    <tr>
                        <th>Program Studi</th>
                        <td><?php echo htmlspecialchars($studyProgram); ?></td>
                    </tr>
                    <tr>
                        <th>Sesi Workshop</th>
                        <td><?php echo htmlspecialchars($workshopSession); ?></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td><?php echo htmlspecialchars($notesClean); ?></td>
                    </tr>
                </table>

                <div class="status-actions">
                    <a href="participants.php" class="btn btn-primary">Lihat Semua Peserta &raquo;</a>
                    <a href="../registration.xhtml" class="btn btn-secondary">Daftar Peserta Lain</a>
                </div>

            <?php else: ?>
                <div class="alert alert-error">
                    <h2>Pendaftaran Gagal / Terjadi Kesalahan</h2>
                    <p>Silakan periksa pesan kesalahan di bawah ini:</p>
                    <ul class="status-error-list">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="status-back-action">
                    <a href="../registration.xhtml" class="btn btn-primary">Kembali ke Form Pendaftaran</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="footer-main home-footer status-footer">
        <div class="footer-columns">
            <div class="footer-contact">
                <img src="../images/UAJY-LOGOGRAM_V.5b7a003.png" alt="Logo Universitas Atma Jaya Yogyakarta" class="footer-logo" />
                <p>
                    Kampus II Gedung Thomas Aquinas<br />Jalan Babarsari 44 Yogyakarta<br />&#9742; 0274-487711<br />&#9993; Humas@uajy.ac.id
                </p>
            </div>
            <div class="footer-about">
                <strong>ABOUT</strong>
                <p>
                    Universitas Atma Jaya Yogyakarta (UAJY) is a private university founded by the laity and managed by 
                    Slamet Rijadi Foundation - Yogyakarta, under the patronage of Saint Albert Magnus sigma
                </p>
                <p>
                    UAJY was established on September 27, 1965, aiming to participate in educating nation with a local dimension and global orientation.
                </p>
            </div>
        </div>
        <div class="footer-copyright">&#169; 2026. Rafael Venansius Anastasios Devin - 230712404</div>
    </div>

</body>
</html>
