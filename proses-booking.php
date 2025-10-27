<?php
session_start();
require 'koneksi.php'; // Panggil koneksi

// Cek keamanan: pastikan hanya member yang login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'member' || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil aksi (booking/batal) dan ID jadwal dari URL
if (isset($_GET['action']) && isset($_GET['jadwal_id'])) {
    $action = $_GET['action'];
    $jadwal_id = (int)$_GET['jadwal_id'];
    $booking_date = date("Y-m-d"); // Tanggal booking hari ini

    if ($action == 'booking') {
        // Proses Booking (INSERT)
        // Cek dulu agar tidak double booking (opsional tapi bagus)
        $sql_check = "SELECT id FROM class_bookings WHERE user_id = ? AND class_schedule_id = ?";
        $stmt_check = mysqli_prepare($koneksi, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $jadwal_id);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) == 0) { // Hanya insert jika belum ada
            $sql_insert = "INSERT INTO class_bookings (user_id, class_schedule_id, booking_date, status) VALUES (?, ?, ?, 'Booked')";
            $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
            mysqli_stmt_bind_param($stmt_insert, "iis", $user_id, $jadwal_id, $booking_date);
            mysqli_stmt_execute($stmt_insert);
        }

    } elseif ($action == 'batal') {
        // Proses Batal Booking (DELETE)
        $sql_delete = "DELETE FROM class_bookings WHERE user_id = ? AND class_schedule_id = ?";
        $stmt_delete = mysqli_prepare($koneksi, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "ii", $user_id, $jadwal_id);
        mysqli_stmt_execute($stmt_delete);
    }
}

// Setelah proses selesai, arahkan kembali ke dashboard
header("Location: member/dashboard.php");
exit();

?>