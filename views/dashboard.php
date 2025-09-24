<?php
date_default_timezone_set('Asia/Jakarta');
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

include "../routes/db.php";

// Ambil data terakhir
$latest = mysqli_query($conn, "SELECT * FROM sensor_data ORDER BY waktu DESC LIMIT 1");
$last = mysqli_fetch_assoc($latest);

// Ambil semua data untuk debugging
$data = mysqli_query($conn, "SELECT * FROM sensor_data ORDER BY waktu DESC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Toren</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Anta&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <h1>MONITORING TOREN</h1>
        <!-- ====== Layout Dua Kolom: Info & Grafik ====== -->
        <div class="dashboard-content">
            <!-- Info Card Grid 2x2 -->
            <div class="card card-info">
                <h3>INFO TERKINI</h3>
                <?php
                // Tentukan kelas warna berdasarkan persen (80-100 hijau, 40-79 kuning, 0-39 merah)
                $persen_val = 0;
                if (isset($last['persen_air']) && $last['persen_air'] !== null && $last['persen_air'] !== '') {
                    $persen_val = floatval($last['persen_air']);
                }
                if ($persen_val >= 80) {
                    $persenClass = 'p-hijau';
                } elseif ($persen_val >= 40) {
                    $persenClass = 'p-kuning';
                } else {
                    $persenClass = 'p-merah';
                }
                ?>
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Tinggi Air</div>
                        <div class="info-value"><?= $last['tinggi_air'] ?> <span class="info-unit">cm</span></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Volume Air</div>
                        <div class="info-value"><?= $last['volume_air'] ?> <span class="info-unit">L</span></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Persentase</div>
                        <div class="info-value <?= $persenClass ?>"><?= htmlspecialchars($persen_val) ?> <span class="info-unit">%</span></div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Estimasi Biaya</div>
                        <div class="info-value">Rp <?= number_format($last['estimasi_biaya'], 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <!-- Grafik Kanan -->
            <div class="card card-graph">
                <h3>GRAFIK PERSENTASE AIR</h3>
                <canvas id="chart" style="max-width:100%;min-height:220px;"></canvas>
                <div style="margin-top:70px;text-align:center;">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;margin:0 auto 8px auto;">
                        <ellipse cx="19" cy="28" rx="13" ry="7" fill="#6a11cb22" />
                        <path d="M19 4C19 4 8 17.5 8 25C8 31 13.5 34 19 34C24.5 34 30 31 30 25C30 17.5 19 4 19 4Z" fill="#6a11cb" stroke="#2575fc" stroke-width="2" />
                        <ellipse cx="19" cy="25" rx="7" ry="3" fill="#fff" fill-opacity=".5" />
                    </svg>
                    <div style="color:#6a11cb;font-weight:600;font-size:1.05rem;letter-spacing:0.5px;">
                        Pantau level air secara real-time dan pastikan toren selalu aman!
                    </div>
                    <?php
                    // Cek status monitoring aktif/tidak
                    $status = 'Tidak Aktif';
                    $statusColor = 'background:linear-gradient(90deg,#b91c1c 0%,#f87171 100%);';
                    if (!empty($last['waktu'])) {
                        $lastTime = strtotime($last['waktu']);
                        $now = time();
                        // Jika data terakhir kurang dari 1 menit dari waktu server, anggap aktif
                        if ($now - $lastTime < 60) {
                            $status = 'Aktif';
                            $statusColor = 'background:linear-gradient(90deg,#6a11cb 0%,#2575fc 100%);';
                        }
                    }
                    ?>
                    <span style="display:inline-block;margin-top:10px;padding:4px 16px;<?= $statusColor ?>color:#fff;border-radius:12px;font-size:0.98rem;font-weight:600;box-shadow:0 2px 8px #6a11cb22;">Status Monitoring: <?= $status ?></span>
                </div>
            </div>
        </div>

        <!-- Card Riwayat Data terpisah di bawah -->
        <div class="card riwayat-card" style="max-width:1200px;margin:32px auto 0 auto;padding-left:18px;padding-right:18px;">
            <h3>RIWAYAT DATA</h3>
            <div style="width:97%;overflow-x:auto;">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM sensor_data ORDER BY waktu DESC LIMIT 10");
                if (!$result) {
                    echo '<div style="color:red;text-align:center;">Query error: ' . mysqli_error($conn) . '</div>';
                } else if (mysqli_num_rows($result) === 0) {
                    echo '<div style="text-align:center;">Belum ada data riwayat</div>';
                } else {
                    echo '<table><thead><tr>';
                    echo '<th>No</th><th>Tinggi (cm)</th><th>Volume (L)</th><th>Persen</th><th>Estimasi Biaya</th><th>Waktu</th>';
                    echo '</tr></thead><tbody>';
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td style="background:#f8fafc;color:#222;font-weight:600;">' . $no++ . '</td>';
                        echo '<td style="background:#f8fafc;color:#222;font-weight:600;">' . htmlspecialchars($row['tinggi_air']) . '</td>';
                        echo '<td style="background:#f8fafc;color:#222;font-weight:600;">' . htmlspecialchars($row['volume_air']) . '</td>';
                        echo '<td style="background:#f8fafc;color:#222;font-weight:600;">' . htmlspecialchars($row['persen_air']) . '%</td>';
                        echo '<td style="background:#f8fafc;color:#222;font-weight:600;">Rp ' . number_format($row['estimasi_biaya'], 0, ',', '.') . '</td>';
                        $waktuFormat = date('d-m-Y H:i', strtotime($row['waktu']));
                        echo '<td style="background:#f8fafc;color:#222;">' . $waktuFormat . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                }
                ?>
            </div>
        </div>
        <a class="logout" href="logout.php">Logout</a>
    </div>

    <!-- ====== Chart.js Script & Card Height Sync ====== -->
    <script>
        // Samakan tinggi card-info dan card-graph
        window.addEventListener('DOMContentLoaded', function() {
            var info = document.querySelector('.card-info');
            var graph = document.querySelector('.card-graph');
            if (info && graph) {
                var maxH = Math.max(info.offsetHeight, graph.offsetHeight);
                info.style.height = maxH + 'px';
                graph.style.height = maxH + 'px';
            }
        });

        // Ambil data dari PHP
        <?php
        $result = mysqli_query($conn, "SELECT waktu, persen_air FROM sensor_data ORDER BY waktu DESC LIMIT 10");
        $labels = [];
        $dataChart = [];
        while ($r = mysqli_fetch_assoc($result)) {
            $labelWaktu = date('d-m H:i', strtotime($r['waktu']));
            $labels[] = "'" . $labelWaktu . "'";
            $dataChart[] = $r['persen_air'];
        }
        $labelsJs = implode(",", array_reverse($labels));
        $dataJs = implode(",", array_reverse($dataChart));
        $isEmpty = (count($labels) === 0);
        ?>
        const chartCanvas = document.getElementById('chart');
        if (chartCanvas && chartCanvas.getContext) {
            const ctx = chartCanvas.getContext('2d');
            // Buat gradasi warna garis sesuai background
            let gradient = ctx.createLinearGradient(0, 0, chartCanvas.width, 0);
            gradient.addColorStop(0, '#6a11cb');
            gradient.addColorStop(1, '#2575fc');
            // Gradasi transparan untuk area bawah garis
            let fillGradient = ctx.createLinearGradient(0, 0, 0, chartCanvas.height);
            fillGradient.addColorStop(0, 'rgba(106,17,203,0.38)'); // #6a11cb lebih tebal
            fillGradient.addColorStop(1, 'rgba(37,117,252,0.18)'); // #2575fc lebih tebal
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [<?php echo $isEmpty ? "'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'" : $labelsJs; ?>],
                    datasets: [{
                        label: 'Persentase Air (%)',
                        data: [<?php echo $isEmpty ? "40,55,60,70,65,80,90" : $dataJs; ?>],
                        borderColor: gradient,
                        backgroundColor: fillGradient,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } else {
            // Jika canvas tidak bisa diakses, tampilkan pesan
            if (chartCanvas) {
                chartCanvas.outerHTML = '<div style="color:red;text-align:center;padding:24px;">Grafik tidak dapat ditampilkan (canvas error)</div>';
            }
        }
    </script>
</body>

</html>