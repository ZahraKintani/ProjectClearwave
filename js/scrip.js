const buttons = document.querySelectorAll('.donation-options .btn-outline-primary');
const nextButton = document.querySelector('.donation-options .btn-primary');

let selectedAmount = null;
nextButton.disabled = true;

buttons.forEach(btn => {
    btn.addEventListener('click', function () {
        buttons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        selectedAmount = this.innerText;
        nextButton.disabled = false;
    });
});

nextButton.addEventListener('click', function () {
    if (selectedAmount) {
        alert('Nominal yang dipilih: ' + selectedAmount);
        window.location.href = `index.php?c=DonasiController&m=metodePembayaran`;
    }
});

document.querySelectorAll('.payment-option').forEach(item => {
    item.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
      this.classList.add('active');
    });
});

const uploadInput = document.getElementById('upload');
const hapusBtn = document.getElementById('hapusBtn');

hapusBtn.addEventListener('click', function() {
    uploadInput.value = ''; // Ini untuk clear file input
});