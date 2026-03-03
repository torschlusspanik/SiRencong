<?php
if (file_exists("robot.pid")) {
    $pid = file_get_contents("robot.pid");
    
    // Taskkill /F (Force) /T (Task tree - mematikan sampai ke chrome-nya)
    shell_exec("taskkill /F /T /PID $pid");
    
    // Hapus jejak PID
    unlink("robot.pid");
    
    echo "<h2>Robot Telah Dihentikan!</h2>";
    echo "Proses dengan PID $pid dan turunannya (Chrome) berhasil dimatikan.<br><br>";
} else {
    echo "<h2>Tidak ada robot yang sedang berjalan.</h2>";
}

echo "<a href='index.php' style='padding:10px; background:#ddd; text-decoration:none;'>Kembali ke Dashboard</a>";
?>