<header class="d-flex align-items-center gap-2 p-3 py-4 blue-light">
  <div class="container">
    <h1 class="text-center">Detail Kampanye</h1>
  </div>
</header>


<main class="container my-5">
  <?php if (isset($kampanye)): ?>
    <div class="text-center mb-4">
      <img src="<?= $kampanye['foto']; ?>" alt="Gambar Kampanye" class="img-fluid rounded" style="max-height:300px;" />
    </div>
    <h2 class="fw-bold"><?= $kampanye['judul']; ?></h2>
    <p><strong>Lokasi:</strong> <?= $kampanye['lokasi']; ?></p>
    <p><strong>Penyelenggara:</strong> <?= $kampanye['owner']; ?></p>
    <p style="text-align: justify;"><?= $kampanye['deskripsi']; ?></p>


<div class="mt-4 d-flex justify-content-center gap-2">
  <a href="index.php?c=DonasiController&m=edit&id=<?= $kampanye['id']; ?>" class="btn btn-warning">
    <i class="bi bi-pencil-square me-1"></i> Edit
  </a>
  <a href="index.php?c=DonasiController&m=hapus&id=<?= $kampanye['id']; ?>" class="btn btn-danger">
    <i class="bi bi-trash me-1"></i> Hapus
  </a>
</div>

  <?php else: ?>
    <div class="alert alert-danger text-center">
      Kampanye tidak ditemukan.
    </div>
  <?php endif; ?>
</main>
