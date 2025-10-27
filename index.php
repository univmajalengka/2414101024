<?php
require 'koneksi.php';

// Ambil data jadwal kelas
$sql_schedule = "SELECT * FROM class_schedule ORDER BY FIELD(day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')";
$result_schedule = mysqli_query($koneksi, $sql_schedule);

// Ambil data testimoni

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitBoss Gym - Shape Your Dream Body</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Oswald:wght@700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="navbar">
        <div class="container">
            <a href="#" class="logo">Fit<span>Boss</span></a>
            <nav>
                <ul>
                    <li><a href="#paket">Packages</a></li>
                    <li><a href="#jadwal">Schedule</a></li>
                    
                </ul> 
            </nav>
            <div class="auth-buttons">
                <a href="login.php" class="btn btn-secondary">LOGIN</a>
                <a href="register.php" class="btn btn-primary">SIGN UP</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <h1>Get Your Dream Body, <br>Starting Today</h1>
                <p>Join thousands of members who have changed their lives with us.</p>
                <a href="#paket" class="btn btn-primary btn-large">View Membership Packages</a>
            </div>
        </section>

        <section id="paket" class="section">
            <div class="container">
                <h2 class="section-title">Choose a package according to your needs</h2>
                <div class="paket-container">
                    <div class="paket-card">
                        <h3>Membership Gym</h3>
                        <p class="harga">Rp 250.000 <span>/ month</span></p>
                        <ul>
                            <li>✅ Full Access to Gym Area</li>
                            <li>✅ Locker & Shower Room</li>
                            <li>✅ Initial Equipment Guidance</li>
                        </ul>
                        <a href="register.php?plan=Gym+Membership" class="btn btn-primary">Choose Package</a>
                    </div>
                    <div class="paket-card featured">
                        <span class="badge">Most Popular</span>
                        <h3>Gym + Class</h3>
                        <p class="harga">Rp 300.000 <span>/ month</span></p>
                        <ul>
                            <li>✅ All Gym Package Benefits</li>
                            <li>✅ Access to All Classes</li>
                            <li>✅ Online Class Booking System</li>
                        </ul>
                        <a href="register.php?plan=Gym+%2B+Class" class="btn btn-primary">Choose Package</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="jadwal" class="section section-dark">
            <div class="container">
                <h2 class="section-title">Weekly Class Schedule</h2>
                <div class="jadwal-grid">
    <?php while ($schedule = mysqli_fetch_assoc($result_schedule)) { ?>
        <div class="jadwal-card" style="background-image: url('images/<?php echo htmlspecialchars($schedule['class_image_url']); ?>');">
            <div class="info">
                <h3><?php echo strtoupper(htmlspecialchars($schedule['day_of_week'])); ?></h3>
                <p><?php echo htmlspecialchars($schedule['class_name']); ?> - <?php echo htmlspecialchars($schedule['coach_name']); ?></p>
            </div>
        </div>
    <?php } ?>
</div>
            </div>
        </section>

        </section>

<section id="fasilitas" class="section">
    <div class="container">
        <h2 class="section-title">Fasilitas Kami</h2>
        <div class="fasilitas-grid">
            <div class="fasilitas-card">
                <div class="icon">🏋️‍♂️</div>
                <h3>Area Gym</h3>
            </div>
            <div class="fasilitas-card">
                <div class="icon">🧘‍♀️</div>
                <h3>Area Kelas</h3>
            </div>
            <div class="fasilitas-card">
                <div class="icon">🏃‍♀️</div>
                <h3>Area Cardio</h3>
            </div>
            <div class="fasilitas-card">
                <div class="icon">💪</div>
                <h3>Area Fit Camp</h3>
            </div>
            <div class="fasilitas-card">
                <div class="icon">🚗</div>
                <h3>Area Parkir</h3>
            </div>
            <div class="fasilitas-card">
                 <div class="icon">🚹</div>
                 <h3>Toilet Pria</h3>
            </div>
            <div class="fasilitas-card">
                <div class="icon">🚺</div>
                 <h3>Toilet Wanita</h3>
            </div>
        </div>
    </div>
</section>

        
    </main>

    <footer class="footer">
        <div class="container">
            <a href="#" class="logo">Fit<span>Boss</span></a>
            <p class="alamat">Taman Sakura Cigasong, Majalengka</p>
            <div class="social-media">
                <a href="#">IG</a> 
            </div>
            <p class="copyright">&copy; 2025 FitBoss Gym. Dibuat untuk Tugas Besar UNMA.</p>
        </div>
    </footer>
</body>
</html>