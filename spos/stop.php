<?php
// Proses penghentian robot dilakukan di awal sebelum menampilkan HTML
$status_pesan = "Sedang menghentikan robot...";
$icon = "fa-spinner fa-spin";

if (file_exists("robot.pid")) {
    $pid = file_get_contents("robot.pid");
    
    // Taskkill /F (Force) /T (Task tree) untuk mematikan python dan chrome
    shell_exec("taskkill /F /T /PID $pid");
    
    // Hapus jejak PID dan Chromedriver sisa
    unlink("robot.pid");
    shell_exec("taskkill /F /IM chromedriver.exe /T > nul 2>&1");
    
    $status_pesan = "Robot Berhasil Dihentikan!";
    $icon = "fa-circle-check";
} else {
    $status_pesan = "Tidak ada robot yang sedang berjalan.";
    $icon = "fa-circle-info";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stopping Robot - SIAPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta http-equiv="refresh" content="3;url=spos.php">
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .stop-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: white;
            padding: 40px;
            text-align: center;
        }
        .icon-box {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ef473a;
        }
        .progress-bar-custom {
            height: 6px;
            border-radius: 3px;
            background: #eee;
            margin-top: 20px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            width: 0%;
            animation: fillProgress 3s linear forwards;
        }
        @keyframes fillProgress {
            from { width: 0%; }
            to { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card stop-card">
                    <div class="icon-box">
                        <i class="fa-solid <?php echo $icon; ?>"></i>
                    </div>
                    <h3 class="fw-bold text-dark"><?php echo $status_pesan; ?></h3>
                    <p class="text-muted">Sistem sedang membersihkan sesi dan menutup browser. Mohon tunggu sejenak...</p>
                    
                    <div class="progress-bar-custom">
                        <div class="progress-fill"></div>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">Mengalihkan ke Dashboard dalam 3 detik...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>