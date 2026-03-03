<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPSO Auto-Entry Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            transition: all 0.3s ease;
        }
        .main-card:hover {
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        .card-header-custom {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 25px;
            text-align: center;
            border-bottom: none;
        }
        .card-body-custom {
            padding: 40px;
            background: white;
        }
        .btn-back-custom {
            position: absolute;
            top: 25px;
            left: 25px;
            text-decoration: none;
            color: #1e3c72;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s ease;
            font-size: 1.1rem;
            z-index: 1000;
        }
        .btn-back-custom:hover {
            color: #0d6efd;
            transform: translateX(-5px);
        }
        .form-label-custom {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
        }
        .file-upload-wrapper:hover {
            border-color: #0d6efd;
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
        }
        .file-upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }
        .file-upload-message {
            text-align: center;
            z-index: 1;
        }
        .file-upload-message i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }
        .btn-start-custom {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            width: 100%;
            letter-spacing: 1px;
        }
        /* Style Merah untuk Jalankan Tanpa Upload */
        .btn-run-only {
            background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            width: 100%;
            letter-spacing: 1px;
        }
        .btn-start-custom:hover, .btn-run-only:hover {
            transform: scale(1.03);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            color: white;
        }
        .status-indicator {
            padding: 10px 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 500;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

    <a href="../index.php" class="btn-back-custom">
        <i class="fa-solid fa-circle-arrow-left fa-lg"></i>
        <span>Kembali ke Dashboard</span>
    </a>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                
                <div class="card main-card">
                    <div class="card-header-custom">
                        <i class="fa-solid fa-robot fa-3x mb-3"></i>
                        <h3 class="mb-0">Robot Entri SPSO</h3>
                        <p class="text-white-50 mb-0">System Automation for SIAPP</p>
                    </div>
                    
                    <div class="card-body-custom">
                        
                        <div class="mb-4">
                            <?php if (file_exists("robot.pid")): ?>
                                <div class="alert alert-success status-indicator border-0 shadow-sm" role="alert">
                                    <div class="spinner-grow spinner-grow-sm text-success" role="status"></div>
                                    <div><strong>STATUS: RUNNING</strong><br>Robot sedang memproses data...</div>
                                    <a href="stop.php" class="btn btn-danger btn-sm ms-auto" onclick="return confirm('Hentikan robot?')">STOP</a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-secondary status-indicator border-0 shadow-sm" role="alert">
                                    <i class="fa-solid fa-stop-circle fa-xl text-secondary"></i>
                                    <div><strong>STATUS: IDLE</strong><br>Silakan pilih metode eksekusi.</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <form action="prosesspos.php" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-4">
                                <label class="form-label-custom">Unggah File Data Entry (Opsional jika jalan tanpa upload)</label>
                                <div class="file-upload-wrapper" id="fileUploadWrapper">
                                    <input type="file" name="excel_file" class="file-upload-input" accept=".xlsx" onchange="displayFileName(this)">
                                    <div class="file-upload-message" id="fileUploadMessage">
                                        <i class="fa-regular fa-file-excel"></i>
                                        <span>Seret & Jatuhkan file atau <span class="text-primary fw-bold">Pilih File</span></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-3 mt-5">
                                <button type="submit" name="start" class="btn btn-start-custom" 
                                    <?php echo file_exists("robot.pid") ? "disabled" : ""; ?>>
                                    <i class="fa-solid fa-upload me-2"></i> MULAI PROSES OTOMATIS
                                </button>

                                <button type="button" class="btn btn-run-only" onclick="confirmRun()"
                                    <?php echo file_exists("robot.pid") ? "disabled" : ""; ?>>
                                    <i class="fa-solid fa-play me-2"></i> JALANKAN TANPA UPLOAD
                                </button>
                            </div>
                        </form>

                        <form id="runOnlyForm" action="prosesspos.php" method="POST" style="display:none;">
                            <input type="hidden" name="run_only" value="1">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function displayFileName(input) {
            if (input.files.length > 0) {
                var fileName = input.files[0].name;
                var messageElement = document.getElementById('fileUploadMessage');
                messageElement.innerHTML = '<i class="fa-solid fa-file-circle-check text-success"></i><span class="text-success fw-bold">' + fileName + '</span>';
            }
        }

        // Fungsi Pop-up Interaktif sesuai instruksi Anda
        function confirmRun() {
            Swal.fire({
                title: 'Konfirmasi Jalankan',
                text: "Apakah anda sudah mengedit excel secara manual?",
                icon: 'warning',
                footer: '<span class="text-danger fw-bold">⚠️ Pastikan excel telah disimpan!</span>',
                showCancelButton: true,
                confirmButtonColor: '#1e3c72',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YES',
                cancelButtonText: 'NO',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika YES, kirim form run_only
                    document.getElementById('runOnlyForm').submit();
                }
            })
        }
    </script>
</body>
</html>