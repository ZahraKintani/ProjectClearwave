<header class="d-flex align-items-center gap-2 p-3 py-4 blue-light">
  <div class="container">
    <h1 class="text-center">Tambah Kampanye Baru</h1>
  </div>
</header>


<main class="container my-3">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
          <form method="POST" enctype="multipart/form-data" action="index.php?c=DonasiController&m=tambah">
            <div class="mb-3">
              <label for="judul" class="form-label">Judul Kampanye</label>
              <input type="text" class="form-control" name="judul" id="judul" required>
            </div>
            <div class="mb-3">
              <label for="lokasi" class="form-label">Lokasi</label>
              <input type="text" class="form-control" name="lokasi" id="lokasi" required>
            </div>
            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" id="deskripsi" rows="5" required></textarea>
            </div>
            <div class="mb-3">
              <label for="owner" class="form-label">Penyelenggara</label>
              <input type="text" class="form-control" name="owner" id="owner" required>
            </div>
            <div class="mb-3">
              <label for="foto" class="form-label">Upload Gambar</label>
              <input type="file" class="form-control" name="foto" id="foto" accept="image/*" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kampanye
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
