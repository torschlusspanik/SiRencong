<?php
$pid_file = "robot_all.pid";
$dir = "../uploads/";
$log_filename = "logs/LogAll_" . date('Y-m-d_H-i-s') . ".log";

// Logika Menjalankan Robot
if (isset($_POST['start'])) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    if (!is_dir('logs')) mkdir('logs', 0777, true);
    
    // Simpan lokasi log ke file aktif
    file_put_contents("active_log.txt", $log_filename);
    
    // Jalankan robot dengan argumen file log
    $cmd = "start /B python robot_all.py \"$log_filename\"";
    pclose(popen($cmd, "r"));
    
    // Simpan PID
    $tasklist = shell_exec("tasklist /FI \"IMAGENAME eq python.exe\" /NH");
    if (preg_match('/python\.exe\s+(\d+)/', $tasklist, $matches)) {
        file_put_contents($pid_file, $matches[1]);
    }
    header("Location: dashboard_all.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Robot Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta http-equiv="refresh" content="3">
    <style>
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; display: flex; align-items: center; }
        .terminal-box { background: #000; color: #00ff41; padding: 20px; border-radius: 10px; height: 400px; overflow-y: auto; font-family: 'Courier New', monospace; border: 2px solid #333; }
        .card { border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4">
            <h3 class="text-center mb-4"><i class="fa-solid fa-robot"></i> Master Robot Controller</h3>
            
            <form method="POST">
                <div class="text-center mb-3">
                    <button type="submit" name="start" class="btn btn-primary btn-lg px-5">
                        <i class="fa-solid fa-play"></i> JALANKAN SEMUA MODUL (SPSO, NTP, NPP)
                    </button>
                </div>
            </form>

            <div class="terminal-box" id="log-screen">
                <?php
                $active_log = file_exists("active_log.txt") ? file_get_contents("active_log.txt") : "";
                if ($active_log && file_exists($active_log)) {
                    echo nl2br(htmlspecialchars(file_get_contents($active_log)));
                } else {
                    echo "[SYSTEM] Siap menjalankan modul robot secara paralel...";
                }
                ?>
            </div>
            
            <div class="text-center mt-3">
                <a href="stop.php" class="btn btn-danger" onclick="return confirm('Hentikan semua proses robot?')">
                    <i class="fa-solid fa-power-off"></i> STOP SEMUA
                </a>
            </div>
        </div>
    </div>
    <script>
        var log = document.getElementById("log-screen");
        log.scrollTop = log.scrollHeight;
    </script>
</body>
</html>