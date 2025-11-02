<?php
session_start();
require '../koneksi.php'; // Panggil file koneksi

// Cek keamanan: pastikan hanya member yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php"); // Arahkan ke login jika bukan member
    exit();
}

// Ambil data member yang sedang login dari session
$user_id = $_SESSION['user_id'];
$nama_lengkap = $_SESSION['nama_lengkap'];


$sql_jadwal = "SELECT * FROM class_schedule ORDER BY FIELD(day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), class_time";
$result_jadwal = mysqli_query($koneksi, $sql_jadwal);
$jadwal_list = []; // Siapkan array untuk menampung data
while ($row = mysqli_fetch_assoc($result_jadwal)) {
    $jadwal_list[] = $row; // Masukkan setiap baris data ke array
}

// Ambil data booking milik member ini
// PERBAIKAN: Gunakan user_id sesuai tabel class_bookings
// Ambil data booking milik member ini
// Menggunakan user_id KARENA tabel class_bookings masih pakai nama itu
$sql_bookings = "SELECT class_schedule_id FROM class_bookings WHERE user_id = ?"; 
$stmt_bookings = mysqli_prepare($koneksi, $sql_bookings);
// Pastikan variabel $user_id sudah ada dari session (berisi ID dari tabel users)
mysqli_stmt_bind_param($stmt_bookings, "i", $user_id); 
mysqli_stmt_execute($stmt_bookings);
$result_bookings = mysqli_stmt_get_result($stmt_bookings);
$my_bookings = []; 
while ($booking = mysqli_fetch_assoc($result_bookings)) {
    $my_bookings[] = $booking['class_schedule_id'];
}
mysqli_stmt_close($stmt_bookings);


// Ambil data membership TERBARU milik member ini dari database
$sql_membership = "SELECT tipe_paket, tanggal_berakhir, status 
                   FROM memberships 
                   WHERE user_id = ? 
                   ORDER BY tanggal_mulai DESC 
                   LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql_membership);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result_membership = mysqli_stmt_get_result($stmt);
$membership = mysqli_fetch_assoc($result_membership); // Simpan data membership

// Tutup statement
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - FitBoss Gym</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="navbar">
        <div class="container">
            <a href="../index.php" class="logo">Fit<span>Boss</span></a>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profile.php">Profil</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <span class="welcome-user">Hi, <?php echo htmlspecialchars($nama_lengkap); ?></span>
                <a href="../logout.php" class="btn btn-primary">LOGOUT</a>
            </div>
        </div>
    </header>

    <main>
        <section class="section dashboard-section">
            <div class="container">
                <div class="member-card">
                    <div class="qr-code">
                        <img src="../images/placeholder-qr.png" alt="QR Code for Check-in">
                    </div>
                    <div class="member-info">
                <h2 class="member-name"><?php echo htmlspecialchars($nama_lengkap); ?></h2>
                <p class="membership-type"><?php echo $membership ? htmlspecialchars($membership['tipe_paket']) : 'Belum Ada Membership'; ?></p>
                <p class="expiry-date">
    <?php 
        if ($membership && $membership['tanggal_berakhir']) {
            echo "Aktif hingga: " . date('d F Y', strtotime($membership['tanggal_berakhir']));
        } else {
            echo "Status: Tidak Aktif"; 
        }
    ?>
</p>
                <div class="member-actions">
                <a href="payment.php" class="btn btn-primary renew-btn">Perpanjang Membership</a>
              </div>

              </div>

                </div>
                <div class="container">
                    <h2 class="section-title">BOOKING KELAS</h2>

                    <div id="aero-list" class="aero-grid" aria-live="polite">
    <?php if (!empty($jadwal_list)): ?>
        <?php foreach ($jadwal_list as $kelas): ?>
            <div class="aero-card">
                <div class="aero-head">
                    <h3 class="aero-title"><?php echo htmlspecialchars($kelas['class_name']); ?></h3>
                    <span class="aero-coach">Coach <?php echo htmlspecialchars($kelas['coach_name']); ?></span>
                </div>
                <div class="aero-meta">
                    <div class="aero-datetime">
                        <?php echo htmlspecialchars($kelas['day_of_week']); ?>, <?php echo date('H:i', strtotime($kelas['class_time'])); ?> WIB
                    </div>
                    <div class="aero-booked">
                        Booked: <b>0</b> </div>
                </div>
                <div class="aero-booking">
     <?php
         // Cek apakah ID kelas saat ini ada di dalam array booking member
         $is_booked = in_array($kelas['id'], $my_bookings);
     ?>

     <?php if ($is_booked): ?>
         <a href="../proses-booking.php?action=batal&jadwal_id=<?php echo $kelas['id']; ?>" class="btn btn-secondary btn-book">
             BATALKAN BOOKING
         </a>
     <?php else: ?>
         <a href="../proses-booking.php?action=booking&jadwal_id=<?php echo $kelas['id']; ?>" class="btn btn-primary btn-book">
             BOOKING
         </a>
     <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; color: var(--text-muted);">Jadwal kelas belum tersedia.</p>
    <?php endif; ?>
</div>
                    </div>

                    <p class="aero-note">
                    *Kelas akan <b>dilaksanakan</b> jika minimal terdapat <b>5</b> member yang telah melakukan booking.
                    </p>
                </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="copyright">&copy; 2025 FitBoss Gym. Dibuat untuk Tugas Besar UNMA.</p>
        </div>
    </footer>

</body>
</html>