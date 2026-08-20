<?php
header('Content-Type: text/html; charset=UTF-8');

$dataFile = __DIR__ . '/../data/participants.txt';
$participants = array();

if (file_exists($dataFile)) {
    $lines = file($dataFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $parts = explode(" | ", $line);
        if (count($parts) >= 7) {
            $participants[] = array(
                'timestamp'    => trim($parts[0]),
                'npm'          => trim($parts[1]),
                'fullName'     => trim($parts[2]),
                'email'        => trim($parts[3]),
                'phone'        => trim($parts[4]),
                'studyProgram' => trim($parts[5]),
                'session'      => trim($parts[6]),
                'notes'        => isset($parts[7]) ? trim($parts[7]) : '-'
            );
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="id" lang="id">
<head>
    <title>Daftar Peserta - UAJY Technology Workshop 2026</title>
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
                <li><a href="participants.php" class="active">Peserta</a></li>
            </ul>
        </div>
    </div>

    <div class="participants-page">
        <div class="participants-card">
            
            <div class="participants-heading">
                <div>
                    <h1>Daftar Peserta Terdaftar</h1>
                    <p class="participants-description">
                        Data berikut dibaca langsung dari file flat text: <code>data/participants.txt</code>
                    </p>
                </div>
                <div>
                    <span class="badge badge-info participants-total">
                        Total Peserta: <?php echo count($participants); ?> Orang
                    </span>
                </div>
            </div>

            <?php if (count($participants) > 0): ?>
                <div class="participants-table-scroll" role="region" aria-label="Tabel peserta terdaftar" tabindex="0">
                    <table class="xhtml-table participants-table" id="scheduleTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NPM</th>
                                <th>Nama Lengkap</th>
                                <th>Program Studi</th>
                                <th>Sesi Workshop</th>
                                <th>No Telepon</th>
                                <th>Waktu Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($participants as $p): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($p['npm']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($p['fullName']); ?></td>
                                    <td><?php echo htmlspecialchars($p['studyProgram']); ?></td>
                                    <td><span class="badge badge-info"><?php echo htmlspecialchars($p['session']); ?></span></td>
                                    <td><?php echo htmlspecialchars($p['phone']); ?></td>
                                    <td><small><?php echo htmlspecialchars($p['timestamp']); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-error participants-empty">
                    Belum ada peserta yang mendaftar. Data akan muncul di sini setelah form pendaftaran diisi.
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="footer-main home-footer participants-footer"><div class="footer-columns"><div class="footer-contact"><img src="../images/UAJY-LOGOGRAM_V.5b7a003.png" alt="Logo Universitas Atma Jaya Yogyakarta" class="footer-logo" /><p>Kampus II Gedung Thomas Aquinas<br />Jalan Babarsari 44 Yogyakarta<br />&#9742; 0274-487711<br />&#9993; Humas@uajy.ac.id</p></div><div class="footer-about"><strong>ABOUT</strong><p>Universitas Atma Jaya Yogyakarta (UAJY) is a private university founded by the laity and managed by Slamet Rijadi Foundation - Yogyakarta, under the patronage of Saint Albert Magnus sigma</p><p>UAJY was established on September 27, 1965, aiming to participate in educating nation with a local dimension and global orientation.</p></div></div><div class="footer-copyright">&#169; 2026. UNIVERSITAS ATMA JAYA YOGYAKARTA</div></div>

</body>
</html>
