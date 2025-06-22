<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>Pembayaran</title>
</head>
<body class="bg-light">
  
  <div class="body">
      <header class="d-flex align-items-center gap-2 p-3">
          <a href="index.php?c=DonasiController&m=metodePembayaran" class="me-3 text-decoration-none fs-4">&#8592;</a>
          <h5 class="m-0 fw-bold">Instruksi Pembayaran Donasi</h5>
      </header>

        <section class="p-4">
            <div class="mb-4">
              <p class="mb-1 fw-semibold">Transfer Bank</p>
              <p class="mb-0"><?= htmlspecialchars($rekening_tujuan['nama_bank']) ?></p>
            </div>
      
            <div class="mb-4">
              <p class="mb-1 fw-semibold"><?= htmlspecialchars($program['penyelenggara_program']) ?></p>
              <p class="mb-2"><?= htmlspecialchars($rekening_tujuan['nomor_rekening']) ?></p>
              <p class="fw-semibold mb-1">Total Donasi</p>
              <div class="d-flex justify-content-between align-items-center border rounded p-2 bg-light">
                <span class="fw-bold text-primary">Rp <?= number_format($jumlah_nominal, 0, ',', '.') ?></span>
                <a href="index.php?c=DonasiController&m=nominalDonasi&id_program=<?= $program['id_program'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              </div>
            </div>
            
            <form action="index.php?c=DonasiController&m=simpanDonasi" method="POST" enctype="multipart/form-data">
              <!-- Hapus hidden inputs yang tidak perlu, karena data sudah di session -->
              <div class="mb-4">
                  <p class="fw-semibold">Upload Bukti Pembayaran</p>
                  <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Kirim</button>
            </form>

        </section>
    </div>
      
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scrip.js"></script>
</body>
</html>