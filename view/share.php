<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Share Your Impact</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <header class="bg-primary text-white text-center py-4 shadow-sm">
    <h1>Share Your Impact</h1>
    <p class="fst-italic mb-0">Dari kepedulian, lahir perubahan</p>
  </header>

  <main class="container my-5">
    <div class="row">
      <?php foreach ($kampanye as $item): ?>
        <div class="col-12 col-sm-6 col-md-6 mb-4 d-flex align-items-stretch">
          <a href="index.php?c=Action&m=detail&id=<?= $item['id']; ?>" class="text-decoration-none text-dark w-100">
            <div class="card shadow-sm h-100">
              <img src="<?= $item['foto']; ?>" class="card-img-top" alt="<?= $item['judul']; ?>">
              <div class="card-body">
                <h6 class="card-title text-center"><?= $item['judul']; ?></h6>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
      <a href="index.php?c=Action&m=tambah" class="btn btn-primary">Bagikan Kampanye Baru</a>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
