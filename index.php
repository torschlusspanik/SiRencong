<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://upload.wikimedia.org/wikipedia/commons/7/74/Coat_of_arms_of_East_Java.svg">
    <title>SI-RENCONG - UPT PPD Jombang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Mengatur arah flex agar footer di bawah */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
        }
        .welcome-text {
            color: #1e3c72;
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .app-title {
            font-size: 3rem;
            font-weight: 800;
            color: #1e3c72;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .menu-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            border-radius: 20px;
            height: 100%;
            background: white;
        }
        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .card-disabled {
            filter: grayscale(100%);
            opacity: 0.6;
            cursor: not-allowed;
            border: 1px dashed #999;
        }
        .icon-box {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        .btn-back {
            position: absolute;
            top: 25px;
            left: 25px;
            font-size: 1.5rem;
            color: #333;
            text-decoration: none;
            display: none;
            z-index: 1000;
        }
        .footer {
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.3);
        }
        #submenu-section { display: none; }
    </style>
</head>
<body>

    <a href="javascript:void(0)" class="btn-back" id="backBtn" onclick="showMainMenu()">
        <i class="bi bi-arrow-left-circle-fill"></i> Kembali
    </a>

    <div class="main-content">
        <div class="container text-center">
            
            <div class="mb-2">
                <p class="welcome-text">Selamat datang di SI-RENCONG</p>
                <h1 class="app-title">SI-RENCONG</h1>
            </div>
            
            <div id="main-menu-section">
                <h4 class="mb-5 text-secondary text-uppercase fw-bold" style="letter-spacing: 2px;">Pilih Modul Sistem</h4>
                <div class="row g-4 justify-content-center">
                    
                    <div class="col-md-4">
                        <div class="card p-4 menu-card shadow-sm" onclick="showSubMenu()">
                            <div class="card-body text-center">
                                <div class="icon-box text-primary"><i class="bi bi-robot"></i></div>
                                <h4 class="fw-bold text-uppercase">Pengentrian Otomatis</h4>
                                <p class="text-muted small">Kelola SPSO, NPP, dan NTP dalam satu pintu.</p>
                                <span class="badge bg-primary">AKTIF</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-4 menu-card shadow-sm card-disabled" onclick="alert('Modul Gropyokan Belum Tersedia')">
                            <div class="card-body text-center">
                                <div class="icon-box text-dark"><i class="bi bi-people-fill"></i></div>
                                <h4 class="fw-bold text-uppercase">Gropyokan</h4>
                                <p class="text-muted small">Bantu teman agar target tunggakan tercapai.</p>
                                <span class="badge bg-secondary">COMING SOON</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card p-4 menu-card shadow-sm card-disabled" onclick="alert('Modul Penkas Belum Tersedia')">
                            <div class="card-body text-center">
                                <div class="icon-box text-dark"><i class="bi bi-graph-up-arrow"></i></div>
                                <h4 class="fw-bold text-uppercase">Penkas</h4>
                                <p class="text-muted small">Penkas UPT PPD Jombang.</p>
                                <span class="badge bg-secondary">COMING SOON</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="submenu-section">
                <h4 class="mb-5 text-secondary text-uppercase fw-bold" style="letter-spacing: 2px;">Modul Pengentrian</h4>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-4">
                        <div class="card p-4 menu-card shadow-sm" onclick="location.href='spos/spos.php'">
                            <div class="card-body text-center">
                                <div class="icon-box text-success"><i class="bi bi-file-earmark-check"></i></div>
                                <h4 class="fw-bold">SPOS</h4>
                                <p class="text-muted small">Buka aplikasi Pengentrian SPOS.</p>
                                <span class="badge bg-success">READY</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4 menu-card shadow-sm card-disabled">
                            <div class="card-body text-center">
                                <div class="icon-box text-dark"><i class="bi bi-lock"></i></div>
                                <h4 class="fw-bold">NPP</h4>
                                <p class="text-muted small">Modul NPP masih terkunci.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4 menu-card shadow-sm card-disabled">
                            <div class="card-body text-center">
                                <div class="icon-box text-dark"><i class="bi bi-lock"></i></div>
                                <h4 class="fw-bold">NTP</h4>
                                <p class="text-muted small">Modul NTP masih terkunci.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="footer">
        &copy; 2026 Bachtiar - UPT PPD Jombang. All Rights Reserved.
    </div>

    <script>
        function showSubMenu() {
            document.getElementById('main-menu-section').style.display = 'none';
            document.getElementById('submenu-section').style.display = 'block';
            document.getElementById('backBtn').style.display = 'block';
        }

        function showMainMenu() {
            document.getElementById('main-menu-section').style.display = 'block';
            document.getElementById('submenu-section').style.display = 'none';
            document.getElementById('backBtn').style.display = 'none';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>