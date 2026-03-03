<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Dashboard - SIAPP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .menu-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            border-radius: 15px;
        }
        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .icon-box {
            font-size: 4rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
        .card-title {
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <h2 class="mb-5 text-dark fw-bold">PILIH MODUL SISTEM</h2>
    
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card h-100 p-4 menu-card shadow-sm" onclick="location.href='spos.php'">
                <div class="card-body">
                    <div class="icon-box">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h4 class="card-title">PENGENTRIAN OTOMATIS</h4>
                    <p class="text-muted small">Modul untuk entri data otomatis via Excel.</p>
                    <span class="badge bg-primary">AKTIF</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 p-4 menu-card shadow-sm opacity-75">
                <div class="card-body">
                    <div class="icon-box text-secondary">
                        <i class="bi bi-file-earmark-lock"></i>
                    </div>
                    <h4 class="card-title text-secondary">TBD</h4>
                    <p class="text-muted small">Modul ini akan tersedia segera (To Be Decided).</p>
                    <span class="badge bg-secondary">COMING SOON</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 p-4 menu-card shadow-sm opacity-75">
                <div class="card-body">
                    <div class="icon-box text-secondary">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                    <h4 class="card-title text-secondary">TBD</h4>
                    <p class="text-muted small">Modul ini akan tersedia segera (To Be Decided).</p>
                    <span class="badge bg-secondary">COMING SOON</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>