<?php
// Selalu mulai file PHP yang menggunakan session dengan session_start()
session_start();

// Panggil file koneksi
require 'koneksi.php';

// Ambil data dari form login
$email = $_POST['email'];
$password = $_POST['password'];

// Validasi sederhana
if (empty($email) || empty($password)) {
    // Arahkan ke halaman gagal jika ada field kosong
    header("Location: gagal.php");
    exit();
}

// Siapkan query untuk mencari user berdasarkan email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Cek apakah user ditemukan dan verifikasi password
if ($user = mysqli_fetch_assoc($result)) {
    // user dengan email tersebut ditemukan, sekarang verifikasi passwordnya
    if (password_verify($password, $user['password'])) {
        // Jika password cocok
        
        // Simpan informasi penting user ke dalam session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role']; // <-- SIMPAN PERAN (ROLE) KE SESSION
        
        // **LOGIKA PENGALIHAN BERDASARKAN PERAN (ROLE)**
        if ($user['role'] == 'admin') {
            // Jika perannya admin, arahkan ke dashboard admin
            header("Location: admin/dashboard.php");
        } else {
            // Jika perannya member, arahkan ke dashboard member
            header("Location: member/dashboard.php");
        }
        exit();

    } else {
        // Jika password salah, arahkan ke halaman gagal
        header("Location: gagal.php");
        exit();
    }
} else {
    // Jika email tidak ditemukan, arahkan ke halaman gagal
    header("Location: gagal.php");
    exit();
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);

?>