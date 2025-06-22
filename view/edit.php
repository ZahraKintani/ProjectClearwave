<header class="py-4 blue-light">
  <div class="container">
    <h1 class="text-center">Edit Kampanye</h1>
  </div>
</header>

<main class="container my-3">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <form action="index.php?c=DonasiController&m=edit&id=<?= $kampanye['id']; ?>" method="post" enctype="multipart/form-data">

            <input type="hidden" name="existing_foto" value="<?= htmlspecialchars($kampanye['foto']) ?>">

            <div class="mb-3">
              <label for="judul" class="form-label">Judul Kampanye</label>
              <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($kampanye['judul']) ?>"
                class="form-control" required />
            </div>

            <div class="mb-3">
              <label for="lokasi" class="form-label">Lokasi</label>
              <input type="text" name="lokasi" id="lokasi" value="<?= htmlspecialchars($kampanye['lokasi']) ?>"
                class="form-control" required />
            </div>

            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi" class="form-control" rows="5" required><?= htmlspecialchars($kampanye['deskripsi']) ?></textarea>
            </div>

            <div class="mb-3">
              <label for="owner" class="form-label">Penyelenggara</label>
              <input type="text" class="form-control" name="owner" id="owner"
                value="<?= htmlspecialchars($kampanye['owner']) ?>" required>
            </div>

            <div class="mb-3">
              <label for="foto" class="form-label">Ganti Foto (Opsional)</label>
              <input type="file" name="foto" id="foto" class="form-control" accept="image/*" />
            </div>

            <div class="d-flex justify-content-end gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
              </button>
              <a href="index.php?c=DonasiController&m=detail&id=<?= $kampanye['id']; ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Batal
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</main>
