<?php
$log_file = "robot_output.log";
$pid_file = "robot.pid";
$dir = "../uploads/";

// Logika 1: Mulai dengan Upload Baru
if (isset($_POST['start'])) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $file_path = $dir . "data_entry.xlsx";
    if (file_exists($log_file)) unlink($log_file);

    if (move_uploaded_file($_FILES["excel_file"]["tmp_name"], $file_path)) {
        jalankanRobot($log_file, $pid_file);
    }
}

// Logika 2: Jalankan Saja (Tanpa Upload)
if (isset($_POST['run_only'])) {
    if (file_exists($log_file)) unlink($log_file);
    jalankanRobot($log_file, $pid_file);
}

function jalankanRobot($log_file, $pid_file) {
    $cmd = "start /B python jalan_robot.py > $log_file 2>&1";
    pclose(popen($cmd, "r"));

    $tasklist = shell_exec("tasklist /FI \"IMAGENAME eq python.exe\" /NH");
    if (preg_match('/python\.exe\s+(\d+)/', $tasklist, $matches)) {
        file_put_contents($pid_file, $matches[1]);
    }
    header("Location: prosesspos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Log - SPSO Robot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta http-equiv="refresh" content="2"> 
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
        }
        .main-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .terminal-container {
            background-color: #1e1e1e;
            border-radius: 15px;
            padding: 20px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.5);
            border: 2px solid #333;
        }
        #log-screen {
            height: 450px;
            overflow-y: auto;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 13px;
            color: #00ff41; /* Warna hijau matrix/terminal */
            line-height: 1.6;
        }
        /* Styling Scrollbar */
        #log-screen::-webkit-scrollbar {
            width: 8px;
        }
        #log-screen::-webkit-scrollbar-track {
            background: #1e1e1e;
        }
        #log-screen::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 10px;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-stop-custom {
            background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%);
            border: none;
            color: white;
            font-weight: bold;
            padding: 12px 25px;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-stop-custom:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(239, 71, 58, 0.4);
            color: white;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="card main-card">
                    <div class="card-header-custom">
                        <div class="d-flex justify-content-between align-items-center px-3">
                            <div class="text-start">
                                <h4 class="mb-0"><i class="fa-solid fa-terminal me-2"></i> Monitor Terminal</h4>
                                <small class="text-white-50">SPSO Automation System</small>
                            </div>
                            <div>
                                <?php if (file_exists($pid_file)): ?>
                                    <span class="bg-success text-white status-badge">
                                        <i class="fa-solid fa-circle-play"></i> Robot Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="bg-secondary text-white status-badge">
                                        <i class="fa-solid fa-circle-stop"></i> Robot Berhenti
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 bg-white">
                        <div class="terminal-container">
                            <div id="log-screen">
                                <?php 
                                if (file_exists($log_file)) {
                                    $log_content = file_get_contents($log_file);
                                    if (empty($log_content)) {
                                        echo "<span class='text-muted'>[SYSTEM] Menunggu respon dari robot...</span>";
                                    } else {
                                        echo nl2br(htmlspecialchars($log_content));
                                    }
                                } else {
                                    echo "<span class='text-muted'>[SYSTEM] Tidak ada file log ditemukan. Silakan jalankan robot kembali.</span>";
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="small text-muted mb-3">
                                <i class="fa-solid fa-circle-info me-1"></i> 
                                Log diperbarui secara otomatis setiap 2 detik. 
                            </p>
                            <a href="stop.php" class="btn btn-stop-custom px-5" onclick="return confirm('Apakah Anda yakin ingin menghentikan robot secara paksa?')">
                                <i class="fa-solid fa-hand me-2"></i> ⛔ STOP ROBOT SEKARANG
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-footer text-center bg-light text-muted p-2" style="font-size: 0.75rem;">
                        SIAPP Terminal Monitoring v1.1
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Otomatis scroll ke bagian bawah log setiap kali halaman dimuat
        window.onload = function() {
            var logScreen = document.getElementById("log-screen");
            logScreen.scrollTop = logScreen.scrollHeight;
        };
    </script>
</body>
</html>