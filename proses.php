<?php
if (isset($_POST['start'])) {
    $dir = "uploads/";
    if (!is_dir($dir)) mkdir($dir);
    
    $file_path = $dir . "data_entry.xlsx";
    
    if (move_uploaded_file($_FILES["excel_file"]["tmp_name"], $file_path)) {
        echo "<h2>Robot sedang bekerja...</h2><p>Mohon jangan menutup jendela browser ini.</p><hr>";
        
        // Menjalankan Python di latar belakang
        // Gunakan path lengkap python.exe jika perlu
        $cmd = "python jalan_robot.py 2>&1";
        $output = shell_exec($cmd);
        
        echo "<pre>$output</pre>";
        echo "<br><a href='index.php' class='btn btn-secondary'>Kembali ke Dashboard</a>";
    } else {
        echo "Gagal mengunggah file.";
    }
}
?>