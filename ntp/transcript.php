<?php
// Menangani permintaan AJAX dari JavaScript
if (isset($_POST['ajax_action']) && $_POST['ajax_action'] == 'parse') {
    $raw_data = $_POST['raw_data'];
    // Escaping string agar aman dijalankan di shell command
    $cmd_input = escapeshellarg($raw_data);
    // Menjalankan skrip python
    $output = shell_exec("python parser_data.py $cmd_input");
    
    // Kirim hasil kembali ke browser sebagai JSON
    header('Content-Type: application/json');
    echo $output;
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transcript Data - SI-RENCONG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%); min-height: 100vh; padding: 40px 0; }
        .main-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; }
        .card-header-custom { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 25px; text-align: center; }
        textarea { font-family: 'Consolas', monospace; font-size: 13px; border-radius: 12px !important; border: 2px solid #eee !important; }
        textarea:focus { border-color: #1e3c72 !important; box-shadow: none !important; }
        .btn-process { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); border: none; color: white; font-weight: bold; padding: 12px; border-radius: 10px; transition: 0.3s; }
        .btn-process:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(37, 117, 252, 0.4); }
        .table-preview { font-size: 11px; white-space: nowrap; }
        .loading-overlay { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 10; align-items: center; justify-content: center; border-radius: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <a href="spos.php" class="btn btn-sm btn-light mb-3 shadow-sm"><i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Panel SPSO</a>
            
            <div class="card main-card position-relative">
                <div id="loader" class="loading-overlay">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 fw-bold">Sedang memproses data...</p>
                    </div>
                </div>

                <div class="card-header-custom">
                    <i class="fa-solid fa-wand-magic-sparkles fa-3x mb-3"></i>
                    <h3 class="fw-bold">Data Transcriber</h3>
                    <p class="mb-0 opacity-75">Salin teks mentah dan ubah menjadi Excel terstruktur dalam sekejap.</p>
                </div>

                <div class="card-body p-4 bg-white">
                    <div class="mb-4">
                        <label class="form-label fw-bold"><i class="fa-solid fa-paste me-2"></i>Tempel Data Mentah:</label>
                        <textarea id="raw_data" class="form-control" rows="8" placeholder="Contoh: 12603076537S 4629 OBUWESTI ERLIFIANINGRUM..."></textarea>
                    </div>

                    <button type="button" onclick="runTranscript()" class="btn btn-process w-100 shadow-sm">
                        <i class="fa-solid fa-gears me-2"></i> PROSES & TAMPILKAN PREVIEW
                    </button>

                    <div id="previewSection" class="mt-5" style="display: none;">
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-table me-2"></i>Preview Hasil Transcript</h5>
                            <a href="../uploads/data_terstruktur.xlsx" id="btnDownload" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-file-excel me-2"></i>Download Excel
                            </a>
                        </div>
                        
                        <div class="table-responsive rounded shadow-sm border">
                            <table class="table table-hover table-sm mb-0 table-preview">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>No_Entry</th>
                                        <th>Nopol</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Desa</th>
                                        <th>Kecamatan</th>
                                        <th>Potensi</th>
                                        <th>Tg_Cetak</th>
                                    </tr>
                                </thead>
                                <tbody id="previewBody">
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function runTranscript() {
    const rawData = document.getElementById('raw_data').value;
    if (!rawData.trim()) {
        Swal.fire('Peringatan', 'Silakan tempel data terlebih dahulu!', 'warning');
        return;
    }

    // Tampilkan Loader
    document.getElementById('loader').style.display = 'flex';

    let formData = new FormData();
    formData.append('ajax_action', 'parse');
    formData.append('raw_data', rawData);

    fetch('transcript.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('previewBody');
        tbody.innerHTML = ''; // Reset tabel

        if (data.length === 0) {
            Swal.fire('Info', 'Tidak ada data yang cocok dengan pola.', 'info');
        } else {
            data.forEach(item => {
                let row = `<tr>
                    <td class="fw-bold">${item.No}</td>
                    <td>${item.No_entry}</td>
                    <td class="text-primary fw-bold">${item.Nopol}</td>
                    <td>${item.Nama}</td>
                    <td>${item.Alamat}</td>
                    <td>${item.Desa}</td>
                    <td>${item.Kecamatan}</td>
                    <td class="text-danger fw-bold">${item.Potensi}</td>
                    <td>${item.Tg_cetak}</td>
                </tr>`;
                tbody.innerHTML += row;
            });
            document.getElementById('previewSection').style.display = 'block';
            Swal.fire('Berhasil!', data.length + ' data berhasil diproses.', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Gagal memproses data. Pastikan Python terinstal.', 'error');
    })
    .finally(() => {
        document.getElementById('loader').style.display = 'none';
    });
}
</script>

</body>
</html>