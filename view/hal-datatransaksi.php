<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
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
        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='index.php?c=DonasiController&m=getRekapByYear&tahun=<?= $_GET['tahun'] ?>'">
            <?php echo $_GET['bulan'] ?>
        </button>
        <form method="get" action="index.php" class="input-group mb-3 mt-3" id="search-container">
            <input type="hidden" name="c" value="DonasiController">
            <input type="hidden" name="m" value="search">
            <?php if (isset($_GET['tahun'])): ?>
                <input type="hidden" name="tahun" value="<?= $_GET['tahun'] ?>">
            <?php endif; ?>
            <?php if (isset($_GET['bulan'])): ?>
                <input type="hidden" name="bulan" value="<?= $_GET['bulan'] ?>">
            <?php endif; ?>

            <input type="search" name="search_tanggal" class="form-control" placeholder="Masukkan tanggal">
            <button type="submit" class="btn btn-dark">Search</button>
        </form>
        <div class="overflow-x-auto">
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Timestamp</th>
                <!-- <th>Donatur</th> -->
                <th>Program</th>
                <th>Penyelenggara</th>
                <th>Nominal</th>
                <th>Metode</th>
                <!-- <th>Bukti Pembayaran</th> -->
                <!-- <th>Aksi</th> -->
            </tr>
            <?php foreach($transaksi2 as $transaksi): ?>
            <tr>
                <!-- <?php var_dump($transaksi) ?> -->
                <td><?php echo $transaksi['id_transaksi']; ?></td>
                <td><?php echo $transaksi['tanggal_donasi']; ?></td>
                <!-- <td><?php echo $transaksi['username']; ?></td> -->
                <td><?php echo $transaksi['judul_program']; ?></td>
                <td><?php echo $transaksi['penyelenggara_program']; ?></td>
                <td><?php echo $transaksi['jumlah_nominal']; ?></td>
                <td><?php echo $transaksi['nama_bank']; ?></td>
                
            </tr>
            <?php endforeach; ?>
        </table>
        </div>


    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</body>
</html>