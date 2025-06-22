<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>Nominal</title>
</head>
  
<body class="bg-light">

    <div class="body">
  
        <header class="d-flex align-items-center gap-2 p-3">
            <a href="index.php?c=DonasiController&m=pilihandonasi" class="me-3 text-decoration-none fs-4">&#8592;</a>
            <h5 class="m-0 fw-bold"><?= htmlspecialchars($program['judul_program'] ?? '1000 Sumur untuk Desa Kering') ?></h5>
        </header>
  
        <section class="donation-info mb-4 text-center">
            <img src="img/sumur.jpg" alt="Sumur" class="img-fluid mb-3" />
            <p class="mb-0"><strong>Penggalangan Dana Oleh</strong></p>
            <p class="text-muted"><?= htmlspecialchars($program['penyelenggara_program'] ?? 'Yayasan WINGS Peduli') ?></p>
            <a href="index.php?c=DonasiController&m=ceritaDonasi" class="text-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-book-half"></i> Cerita di Balik Donasi
            </a>        
        </section>
  
        <section class="donation-options text-center px-4">
            <h4 class="mb-2">Donasi Anda</h4>
            <p class="text-muted">Minimal Rp 1.000</p>

            <!-- Tampilkan pesan error jika ada -->
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?c=DonasiController&m=metodePembayaran" method="post" id="nominalForm">
                <input type="hidden" name="id_program" value="<?= htmlspecialchars($program['id_program']) ?>">
                
                <div class="mb-3">
                    <label for="jumlah_nominal" class="form-label">Masukkan Nominal Donasi</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" 
                               class="form-control" 
                               id="jumlah_nominal" 
                               name="jumlah_nominal" 
                               min="1000" 
                               placeholder="50000" 
                               required>
                    </div>
                    <small class="form-text text-muted">Contoh: 50000 (tanpa titik atau koma)</small>
                </div>
                
                <button type="submit" class="btn btn-secondary w-100" id="submitBtn" disabled>
                    Lanjutkan
                </button>
            </form>

            <!-- Opsi nominal cepat (opsional) -->
            <div class="mt-4">
                <p class="text-muted mb-2">Atau pilih nominal cepat:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary quick-amount" data-amount="20000">Rp 20.000</button>
                    <button type="button" class="btn btn-outline-primary quick-amount" data-amount="50000">Rp 50.000</button>
                    <button type="button" class="btn btn-outline-primary quick-amount" data-amount="100000">Rp 100.000</button>
                    <button type="button" class="btn btn-outline-primary quick-amount" data-amount="200000">Rp 200.000</button>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nominalInput = document.getElementById('jumlah_nominal');
            const submitBtn = document.getElementById('submitBtn');
            const quickAmountBtns = document.querySelectorAll('.quick-amount');
            const form = document.getElementById('nominalForm');

            // Function to validate and enable/disable submit button
            function validateInput() {
                const value = parseInt(nominalInput.value);
                if (value && value >= 1000) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-primary');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.remove('btn-primary');
                    submitBtn.classList.add('btn-secondary');
                }
            }

            // Handle input change
            nominalInput.addEventListener('input', validateInput);
            nominalInput.addEventListener('keyup', validateInput);

            // Handle quick amount buttons
            quickAmountBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const amount = this.getAttribute('data-amount');
                    nominalInput.value = amount;
                    
                    // Remove active class from all buttons
                    quickAmountBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    validateInput();
                });
            });

            // Handle form submission
            form.addEventListener('submit', function(e) {
                const value = parseInt(nominalInput.value);
                if (!value || value < 1000) {
                    e.preventDefault();
                    alert('Minimal donasi adalah Rp 1.000');
                    return false;
                }
            });

            // Initial validation
            validateInput();
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>