<?php
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM arsip");
$d_total = mysqli_fetch_assoc($q_total);
$jumlah_total = (int)$d_total['jml'];
$tanggal_hari_ini = date('d M Y');
?>

<style>
/* CARD UTAMA */
.main-card {
    background-color: rgba(255, 255, 255, 0.35); /* lebih transparan */
    backdrop-filter: blur(6px);
    border-radius: 20px;
    padding: 18px 22px;
    margin-top: 25px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.16);
    border: 1px solid rgba(255, 255, 255, 0.7);
    border-left: 8px solid #F37021;
}

body {
    background: url("assets/Home-ESG.jpg") no-repeat center center fixed;
    background-size: cover;
    position: relative;
    z-index: 0;
}

body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: -1;
}

/* ====== HERO PKT (CARD PERTAMA & WADAH CHART) ====== */
.hero-pkt {
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 18px;
    padding: 22px 20px;
    margin: 0;
    border-left: 6px solid #0054A6;
    position: relative;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    transition: 0.3s ease;
}

.hero-pkt:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 26px rgba(0,0,0,0.18);
}

/* dekorasi halus */
.hero-pkt::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 10% 0%, rgba(0, 84, 166, 0.10) 0%, transparent 45%),
        radial-gradient(circle at 100% 100%, rgba(243, 112, 33, 0.12) 0%, transparent 55%);
    opacity: 1;
    pointer-events: none;
    z-index: 1;
}

/* isi card */
.hero-content {
    position: relative;
    z-index: 2;
}

/* HEADER: BADGE + TANGGAL DI SATU BARIS */
.hero-header {
    display: flex;
    justify-content: flex-start; /* badge di kiri */
    align-items: center;
    margin-bottom: 15px;
}

.hero-badge {
    background: linear-gradient(135deg, #0054A6, #0d8bff);
    color: #fff;
    font-size: 12px;
    padding: 5px 12px;
    border-radius: 999px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}

/* DATE BADGE DI UJUNG KANAN */
.hero-date {
    margin-left: auto;          /* dorong ke kanan */
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #555;

    padding: 4px 10px;
    border-radius: 999px;
    background: rgba(0, 84, 166, 0.06);
    border: 1px solid rgba(0, 84, 166, 0.2);
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.hero-date .icon {
    font-size: 15px;
}

/* LAYOUT DALAM HERO: TEKS (KIRI) + CHART (KANAN) */
.hero-layout {
    display: flex;
    gap: 22px;
    align-items: stretch;
    flex-wrap: wrap; /* responsif */
}

.hero-left {
    flex: 1 1 55%;
    min-width: 280px;
}

.hero-right {
    flex: 1 1 35%;
    min-width: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* judul & teks */
.hero-pkt h1 {
    color: #0054A6;
    font-weight: 700;
    text-shadow: 0px 2px 6px rgba(0,0,0,0.08);
    margin-bottom: 8px;
}

.hero-pkt p {
    color: #444;
    font-size: 15px;
    line-height: 1.5;
    margin-bottom: 14px;
}

/* tombol */
.btn-pkt-primary {
    background-color: #0054A6;
    border: none;
    color: white;
    border-radius: 999px;
    padding: 10px 22px;
    font-size: 14px;
    font-weight: 600;
    position: relative;
    z-index: 3;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-pkt-primary:hover {
    background-color: #F37021;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(243,112,33,0.35);
}

.btn-pkt-primary:active {
    transform: translateY(0);
    box-shadow: none;
}

/* ringkasan kecil di bawah teks */
.hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 10px;
}

.hero-meta-item {
    background-color: rgba(255,255,255,0.9);
    border-radius: 12px;
    padding: 8px 12px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    min-width: 120px;
}

.hero-meta-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: #777;
    margin-bottom: 2px;
}

.hero-meta-value {
    font-size: 16px;
    font-weight: 700;
    color: #0054A6;
}

.hero-meta-pill {
    display: inline-block;
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 999px;
    background: rgba(40,167,69,0.08);
    color: #28a745;
    font-weight: 600;
}

/* SECTION CHART DI DALAM HERO (KANAN) */
.chart-section {
    text-align: center;
    color: #222;
}

.chart-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
    color: #F37021;
}

.total-number {
    font-size: 30px;
    font-weight: 800;
    color: #F37021;
    margin-top: -2px;
    margin-bottom: 10px;
}

/* Agar chart tidak memanjang */
.chart-wrapper {
    width: 100%;
    max-width: 220px;
    height: 190px;
    margin: 0 auto;
}
</style>

<!-- CARD UTAMA -->
<!--div class="main-card"-->
    <div class="hero-pkt">
        <div class="hero-content">

            <!-- HEADER: BADGE KIRI + TANGGAL DI UJUNG KANAN -->
            <div class="hero-header">
                <div class="hero-badge">Dashboard E-ARSIP</div>

                <div class="hero-date">
                    <span class="icon">📅</span>
                    <span><?= $tanggal_hari_ini ?></span>
                </div>
            </div>

            <!-- LAYOUT KIRI-KANAN: TEKS & CHART -->
            <div class="hero-layout">

                <!-- KIRI: TEKS HERO -->
                <div class="hero-left">
                    <h1 class="display-6 fw-bold">Selamat Datang di Aplikasi Anda</h1>
                    <p class="mt-2">
                        Selamat datang di <strong>E-ARSIP | PPE</strong>, aplikasi manajemen arsip digital yang dirancang
                        untuk memudahkan pengelolaan dan pencarian dokumen penting Anda. Simpan, kelola, dan temukan arsip
                        kapan saja dengan lebih cepat dan terstruktur.
                    </p>

                    <div class="hero-meta">
                        <div class="hero-meta-item">
                            <div class="hero-meta-label">Total Arsip</div>
                            <div class="hero-meta-value"><?= $jumlah_total ?></div>
                        </div>
                        <div class="hero-meta-item">
                            <div class="hero-meta-label">Status Sistem</div>
                            <span class="hero-meta-pill">Aktif</span>
                        </div>
                    </div>

                    <button class="btn btn-pkt-primary mt-3" onclick="logout()">LogOut</button>
                </div>

                <!-- KANAN: CHART DI DALAM HERO -->
                <div class="hero-right">
                    <div class="chart-section">
                        <div class="chart-title">Total Arsip</div>
                        <div class="total-number"><?= $jumlah_total ?></div>

                        <div class="chart-wrapper">
                            <canvas id="arsipChart"></canvas>
                        </div>
                    </div>
                </div>

            </div> <!-- /.hero-layout -->
        </div> <!-- /.hero-content -->
    </div> <!-- /.hero-pkt -->
</div> <!-- /.main-card -->

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Ambil nilai total arsip dari PHP
const totalArsip = <?= $jumlah_total ?>;

const ctx = document.getElementById('arsipChart').getContext('2d');

// Gradient untuk cincin
let gradient = ctx.createLinearGradient(0, 0, 0, 200);
gradient.addColorStop(0, "rgba(255, 215, 0, 1)");
gradient.addColorStop(1, "rgba(255, 140, 0, 0.7)");

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Total Arsip'],
        datasets: [{
            data: [totalArsip],
            backgroundColor: [gradient],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        rotation: -90,
        circumference: 360,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return ' Total arsip: ' + context.parsed;
                    }
                }
            }
        }
    }
});

function logout() {
    if (confirm('Apakah Anda yakin ingin logout?')) {
        window.location.href = 'logout.php';
    }
}
</script>