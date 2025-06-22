<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include("navbar.php"); ?>
    <div id="wrapper">
        <header>
            <h1>Data Transaksi</h1>
        </header>
        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='index.php?c=DonasiController&m=tahunRekap'">
            <?php echo $_GET['tahun'] ?>
        </button>
        <h2>Pilih bulan transaksi</h2>
        <div id="btn-bulan" class="btn-daftar">
            <?php foreach($bulan2 as $bulan):?>
                <button class="btn btn-outline-primary w-100 mt-1" onclick="window.location.href='index.php?c=DonasiController&m=getRekapByMonth&bulan=<?= $bulan['nama_bulan'] ?>&tahun=<?php echo $_GET['tahun']?>'">
                    <?= $bulan['nama_bulan'] ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>