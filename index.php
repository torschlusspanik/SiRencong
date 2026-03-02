<!DOCTYPE html>
<html lang="id">
<head>
    <title>SIAPP Auto-Entry Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white text-center">
                <h5 class="mb-0">🤖 Robot Entri SIAPP</h5>
            </div>
            <div class="card-body p-4">
                <form action="proses.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Upload File Excel (.xlsx)</label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx" required>
                        <small class="text-muted">Pastikan format kolom sesuai instruksi.</small>
                    </div>
                    <button type="submit" name="start" class="btn btn-primary w-100 py-2">Mulai Proses Otomatis</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>