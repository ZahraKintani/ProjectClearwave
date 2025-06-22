<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>ProgramDonasi</title>
    <link rel="stylesheet" href="style.css">

    
</head>
<body class="bg-light">

    <div class="body">
        <?php include("navbar.php"); ?>
        
        <main class="p-3 text-center">
            <div class="cover mb-4">
                <h2 class="fw-bold">Ulurkan Tangan <br>Alirkan Kehidupan</h2>
            </div>
            
            <section class="mb-4 border rounded p-3">
                <img src="img/sumur.jpg" alt="Program 1" class="img-fluid rounded mb-2" style="width: 70%;" />
                <h2 class="fs-6">1000 Sumur untuk Desa Kering</h2>
                <p>Yayasan WINGS Peduli</p>
               <a href="index.php?c=DonasiController&m=nominalDonasi&id_program=1" class="btn btn-outline-primary">Donasi Sekarang</a>
            </section>
        
            <section class="border rounded p-3">
                <img src="img/bersihntt.jpg" alt="Program 2" class="img-fluid rounded mb-2" style="width: 70%;" />
                <h2 class="fs-6">Air Bersih untuk Pelosok NTT</h2>
                <p>Yayasan Amal Peduli Nusantara</p>
                <a href="#" class="btn btn-outline-danger">Segera Dibuka</a>
            </section>
        </main>
    </div>
</body>
</html>