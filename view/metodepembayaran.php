<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>Metode-pembayaran</title>
</head>
<body class="bg-light">
  <div class="body">
        <header class="d-flex align-items-center gap-2 p-3">
            <a href="index.php?c=DonasiController&m=nominalDonasi&id_program=1" class="me-3 text-decoration-none fs-4">&#8592;</a>
            <h5 class="m-0 fw-bold"><?= htmlspecialchars($program['judul_program'] ?? '1000 Sumur untuk Desa Kering') ?></h5>
        </header>

        <section class="p-4">
            <h2 class="fs-5 mb-4 fw-semibold">Metode Pembayaran</h2>
      
            <div class="mb-4">
              <h3 class="fs-6 fw-bold">Transfer Bank</h3>
              <div class="list-group">
                <?php foreach($metode_pembayaran_list as $metode): ?>
                  <a href="index.php?c=DonasiController&m=transfer&id_metode=<?= $metode['id_metodepembayaran'] ?>" 
                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center payment-option">
                     Transfer <?= htmlspecialchars($metode['nama_bank']) ?>
                     <i class="bi bi-chevron-right"></i>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          </section>
        </div>
      
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
    <script src="js/scrip.js"></script>
</body>
</html>