<?php
$log_file = "robot_output.log";
$pid_file = "robot.pid";

if (isset($_POST['start'])) {
    $dir = "uploads/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    
    $file_path = $dir . "data_entry.xlsx";
    
    // Hapus log lama agar tampilan bersih
    if (file_exists($log_file)) unlink($log_file);

    if (move_uploaded_file($_FILES["excel_file"]["tmp_name"], $file_path)) {
        // Jalankan python di background dan arahkan output ke file log
        $cmd = "start /B python jalan_robot.py > $log_file 2>&1";
        pclose(popen($cmd, "r"));

        // Simpan PID untuk tombol STOP
        $tasklist = shell_exec("tasklist /FI \"IMAGENAME eq python.exe\" /NH");
        if (preg_match('/python\.exe\s+(\d+)/', $tasklist, $matches)) {
            file_put_contents($pid_file, $matches[1]);
        }

        header("Location: proses.php"); // Refresh ke tampilan log
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Monitoring Log Robot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="2"> 
</head>
<body class="bg-light p-5">
    <div class="container card shadow-sm p-4" style="max-width: 850px;">
        <h5 class="text-center mb-3">🖥️ Terminal Log Robot SIAPP</h5>
        <hr>
        
        <div class="mb-3">
            <div id="log-screen" class="bg-dark text-info p-3 rounded" style="height: 400px; overflow-y: auto; font-family: 'Consolas', 'Monaco', monospace; font-size: 14px; border: 2px solid #333;">
                <?php 
                if (file_exists($log_file)) {
                    $log_content = file_get_contents($log_file);
                    // Menampilkan log, nl2br untuk baris baru, htmlspecialchars untuk keamanan
                    echo empty($log_content) ? "Menunggu respon dari robot..." : nl2br(htmlspecialchars($log_content));
                } else {
                    echo "Belum ada aktivitas. Silakan upload file terlebih dahulu.";
                }
                ?>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-secondary flex-grow-1">Kembali ke Dashboard</a>
            <a href="stop.php" class="btn btn-danger flex-grow-1" onclick="return confirm('Hentikan proses robot?')">⛔ STOP ROBOT</a>
        </div>
    </div>

    <script>
        // Otomatis scroll ke baris log paling bawah
        var logScreen = document.getElementById("log-screen");
        logScreen.scrollTop = logScreen.scrollHeight;
    </script>
</body>
</html>