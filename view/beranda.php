<main class="container mt-4">
  <section class="py-4 blue-light border-bottom shadow-sm">
    <div class="container text-center">
      <div class="mb-3">
        <i class="bi bi-droplet-half display-4 text-primary"></i>
      </div>
      <h2 class="fw-bold">ClearWave</h2>
      <p class="lead text-muted mb-0">Waves of Change, Drops of Hope</p>
    </div>
  </section>
  <section>
    <div class="text-center mt-4">
      <a href="index.php?c=DonasiController&m=tambah" class="btn btn-primary">+ Bagikan Kampanye Baru</a>
    </div>

  </section>

  <h2 class="mt-4 text-center mb-3">Daftar Kampanye</h2>
  <div class="row justify-content-center">
    <?php if (!empty($kampanye) && is_array($kampanye)): ?>
      <?php foreach ($kampanye as $k): ?>
        <div class="col-md-6 col-lg-5 mb-4 d-flex">
          <div class="card shadow-sm border-0 w-100">
            <img src="<?= htmlspecialchars($k['foto']) ?>" class="card-img-top rounded-top" alt="Foto Kampanye"
              style="height: 180px; object-fit: cover;">

            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-semibold text-center mb-3"><?= htmlspecialchars($k['judul']) ?></h5>

              <div class="text-center mt-auto">
                <a href="index.php?c=DonasiController&m=detail&id=<?= $k['id'] ?>" class="btn btn-outline-primary btn-sm">
                  <i class="bi bi-eye me-1"></i> Lihat Detail
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p class="text-muted fst-italic">Belum ada kampanye tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</main>