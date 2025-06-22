<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>Document</title>
</head>
<body class="bg-light">

    <div class="body">
        <header class="d-flex align-items-center gap-2 p-3">
            <a href="index.php?c=DonasiController&m=nominaldonasi&id_program=1" class="me-3 text-decoration-none fs-4">&#8592;</a>
            <h5 class="m-0 fw-bold"><?= htmlspecialchars($program['judul_program'] ?? '1000 Sumur untuk Desa Kering') ?></h5>
        </header>

        <section class="p-4">
            <h3 class="fw-semibold mb-3 text-center">Cerita di Balik Donasi</h3>
            <p class="text-muted">
              Di balik setiap tetes air, ada harapan. <br> <br> Indonesia adalah negeri yang kaya akan sumber daya alam, namun tidak semua daerah menikmati akses air bersih yang layak. Di banyak desa terpencil dan daerah rawan kekeringan, masyarakat harus menempuh perjalanan jauh hanya untuk mendapatkan air—yang belum tentu aman untuk dikonsumsi. Anak-anak terpaksa absen sekolah karena harus membantu orang tua mengambil air, sementara ibu-ibu harus mengorbankan waktu produktif mereka hanya untuk kebutuhan dasar ini.
              Yayasan WINGS Peduli mendengar jeritan sunyi dari desa-desa ini. Kami percaya bahwa air bersih bukanlah kemewahan, melainkan hak dasar setiap manusia. 
              <br> <br> Maka lahirlah inisiatif "1000 Sumur untuk Desa Kering"—sebuah gerakan untuk membangun sumur-sumur air bersih di wilayah-wilayah yang paling membutuhkan.
              Program ini bukan hanya soal menggali tanah dan mengalirkan air. Ini tentang menghidupkan kembali harapan, menggerakkan roda pendidikan, dan memungkinkan ekonomi lokal tumbuh. Setiap sumur yang dibangun adalah simbol kolaborasi antara kebaikan hati para donatur, kerja keras tim lapangan, dan semangat warga desa yang ingin bangkit.
              Hingga hari ini, puluhan sumur telah berdiri, membawa perubahan nyata bagi ribuan jiwa. Namun perjalanan masih panjang—ada ratusan desa lain yang menanti keajaiban serupa.
              <br> <br> Bersama, kita bisa menyalurkan kehidupan. Satu sumur, satu desa, satu perubahan.
            </p>
          </section>
    </div>
</body>
</html>