<main class="container my-5">
  <div class="card p-4 shadow-sm">
    <h3 class="mb-4 text-center">Yakin ingin menghapus?</h3>

    <div class="d-flex justify-content-center gap-2">
      <form action="index.php?c=DonasiController&m=hapus&id=<?= $kampanye['id']; ?>" method="post">
        <button type="submit" class="btn btn-danger">Ya</button>
      </form>
      <button type="button" class="btn btn-secondary" onclick="history.back()">Batal</button>
    </div>
  </div>
</main>
