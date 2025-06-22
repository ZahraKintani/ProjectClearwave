<?php
session_start();
require_once 'Controller.php';
require_once 'model/DonasiModel.class.php';

class DonasiController extends Controller{
    private $donasiModel;
    private $model;

    public function __construct(){
        $this->donasiModel = new DonasiModel();
        $this->model = new Model();

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = 1; 
            $_SESSION['username'] = 'testuser';
        }
    }

    private function isLoggedIn(){
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }


    public function pilihandonasi(){
        $daftarProgram = $this->donasiModel->ambilSemuaProgramDonasi();
        $this->loadView('pilihandonasi.php', ['programs' => $daftarProgram]);
    }

  
    public function nominalDonasi(){
        $id_program = filter_input(INPUT_GET, 'id_program', FILTER_VALIDATE_INT);

        if (!$id_program) {
            $error_message = "ID program tidak valid.";
            $this->loadView('pilihandonasi.php', ['error_message' => $error_message]);
            return;
        }

        $program = $this->donasiModel->ambilProgramDonasiBerdasarkanId($id_program);
        if (!$program) {
            $_SESSION['error_message'] = "Program donasi tidak ditemukan.";
            $this->redirect('index.php?c=DonasiController&m=pilihandonasi');
            return;
        }

        $_SESSION['donasi_id_program'] = $id_program;

        $opsiNominal = $this->donasiModel->ambilNominalUntukProgram($id_program);
        $this->loadView('nominaldonasi.php', [
            'program' => $program,
            'opsi_nominal' => $opsiNominal
        ]);
    }

 
    public function metodePembayaran(){
        if (!isset($_SESSION['donasi_id_program'])) {
            $_SESSION['error_message'] = "Silakan pilih program donasi terlebih dahulu.";
            $this->redirect('index.php?c=DonasiController&m=pilihandonasi');
            return;
        }

        $jumlah_nominal = filter_input(INPUT_POST, 'jumlah_nominal', FILTER_VALIDATE_INT);
        if (!$jumlah_nominal && isset($_GET['nominal'])) {
            $jumlah_nominal = filter_input(INPUT_GET, 'nominal', FILTER_VALIDATE_INT);
        }

        if (!$jumlah_nominal || $jumlah_nominal <= 0) {
            $_SESSION['error_message'] = "Jumlah nominal tidak valid.";
            $this->redirect('index.php?c=DonasiController&m=nominalDonasi&id_program=' . $_SESSION['donasi_id_program']);
            return;
        }

        $_SESSION['donasi_jumlah_nominal'] = $jumlah_nominal;
        $id_program = $_SESSION['donasi_id_program'];

        $metodePembayaran = $this->donasiModel->ambilMetodePembayaranUntukProgram($id_program);
        $this->loadView('metodepembayaran.php', [
            'jumlah_nominal' => $jumlah_nominal,
            'metode_pembayaran_list' => $metodePembayaran,
            'id_program' => $id_program
        ]);
    }

    
    public function transfer(){
        if (!isset($_SESSION['donasi_id_program']) || !isset($_SESSION['donasi_jumlah_nominal'])) {
            $_SESSION['error_message'] = "Informasi donasi tidak lengkap. Silakan ulangi proses.";
            $this->redirect('index.php?c=DonasiController&m=pilihandonasi');
            return;
        }

        $id_metodepembayaran = filter_input(INPUT_GET, 'id_metode', FILTER_VALIDATE_INT);
        if (!$id_metodepembayaran) {
            $_SESSION['error_message'] = "Metode pembayaran tidak valid.";
            $this->redirect('index.php?c=DonasiController&m=metodePembayaran');
            return;
        }

        $_SESSION['donasi_id_metodepembayaran'] = $id_metodepembayaran;

        $rekeningList = $this->donasiModel->ambilRekeningTransferByMetodeId($id_metodepembayaran);
        if (empty($rekeningList)) {
            $_SESSION['error_message'] = "Detail rekening untuk metode ini tidak ditemukan.";
            $this->redirect('index.php?c=DonasiController&m=metodePembayaran');
            return;
        }

        $rekeningTujuan = $rekeningList[0];
        $program = $this->donasiModel->ambilProgramDonasiBerdasarkanId($_SESSION['donasi_id_program']);

        $this->loadView('transfer.php', [
            'jumlah_nominal' => $_SESSION['donasi_jumlah_nominal'],
            'rekening_tujuan' => $rekeningTujuan,
            'program' => $program
        ]);
    }


    public function simpanDonasi(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error_message'] = "Aksi tidak diizinkan.";
            $this->redirect('index.php?c=DonasiController&m=pilihandonasi');
            return;
        }

        if (!$this->isLoggedIn()) {
            $_SESSION['error_message'] = "Anda harus login untuk melakukan donasi.";
            $this->redirect('index.php?c=DonasiController&m=login');
            return;
        }

        $id_program = $_SESSION['donasi_id_program'] ?? null;
        $jumlah_nominal = $_SESSION['donasi_jumlah_nominal'] ?? null;
        $id_metodepembayaran = $_SESSION['donasi_id_metodepembayaran'] ?? null;
        $id_user = $_SESSION['user_id'];

        if (!$id_program || !$jumlah_nominal || !$id_metodepembayaran) {
            $_SESSION['error_message'] = "Data donasi tidak lengkap. Sesi mungkin berakhir. Silakan ulangi.";
            $this->redirect('index.php?c=DonasiController&m=pilihandonasi');
            return;
        }

        // Proses upload file
        $path_bukti_pembayaran = null;
        if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['bukti_pembayaran'];
            $fileName = basename($file['name']);
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
            $maxFileSize = 5 * 1024 * 1024; // 5 MB

            if (!in_array($fileExtension, $allowedExtensions)) {
                $_SESSION['error_message'] = "Format file tidak diizinkan. Hanya JPG, JPEG, PNG, PDF.";
            } elseif ($fileSize > $maxFileSize) {
                $_SESSION['error_message'] = "Ukuran file terlalu besar. Maksimal 5MB.";
            } else {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFileName = uniqid('bukti_', true) . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpName, $destination)) {
                    $path_bukti_pembayaran = $destination;
                } else {
                    $_SESSION['error_message'] = "Gagal mengupload bukti pembayaran.";
                }
            }
        } else {
            $_SESSION['error_message'] = "Bukti pembayaran wajib diunggah. Error code: " . ($_FILES['bukti_pembayaran']['error'] ?? 'Tidak ada file');
        }

        if (!$path_bukti_pembayaran) {
            $redirectUrl = 'index.php?c=DonasiController&m=transfer';
            if ($id_metodepembayaran) {
                $redirectUrl .= '&id_metode=' . $id_metodepembayaran;
            }
            $this->redirect($redirectUrl);
            return;
        }

        $berhasil = $this->donasiModel->simpanTransaksiDonasi($id_program, $id_metodepembayaran, $id_user, $jumlah_nominal, $path_bukti_pembayaran);

        if ($berhasil) {
            unset($_SESSION['donasi_id_program']);
            unset($_SESSION['donasi_jumlah_nominal']);
            unset($_SESSION['donasi_id_metodepembayaran']);

            $_SESSION['success_message'] = "Donasi Anda telah berhasil diproses. Terima kasih!";
            $this->redirect('index.php?c=DonasiController&m=berhasilDonasi');
        } else {
            if (file_exists($path_bukti_pembayaran)) {
                unlink($path_bukti_pembayaran);
            }
            $_SESSION['error_message'] = "Terjadi kesalahan saat menyimpan donasi Anda. Silakan coba lagi.";
            $redirectUrl = 'index.php?c=DonasiController&m=transfer';
            if ($id_metodepembayaran) {
                $redirectUrl .= '&id_metode=' . $id_metodepembayaran;
            }
            $this->redirect($redirectUrl);
        }
    }


    public function ceritaDonasi()
    {
        $this->loadView('ceritadonasi.php');
    }

  
    public function berhasilDonasi()
    {
        $this->loadView('berhasildonasi.php');
    }

    public function loginForm()
    {
        $this->loadView('Login.php');
    }

    public function registerForm()
    {
        $this->loadView('registerform.php');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=DonasiController&m=loginForm');
            return;
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

        if (empty($username) || empty($password)) {
            $this->loadView('Login.php', ['error' => 'Username dan password harus diisi.']);
            return;
        }

        $user = $this->donasiModel->findUserByIdentifier($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $this->redirect('index.php?c=DonasiController&m=pilihandonasi'); 
        } else {
            $this->loadView('Login.php', ['error' => 'Username atau password salah.']);
        }
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=DonasiController&m=registerForm');
            return;
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
        $passwordConfirm = filter_input(INPUT_POST, 'password_confirm', FILTER_UNSAFE_RAW);

        if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
            $this->loadView('registerform.php', ['error' => 'Semua kolom harus diisi.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->loadView('registerform.php', ['error' => 'Format email tidak valid.']);
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->loadView('registerform.php', ['error' => 'Konfirmasi password tidak cocok.']);
            return;
        }

        if ($this->donasiModel->findUserByIdentifier($username) || $this->donasiModel->findUserByIdentifier($email)) {
            $this->loadView('registerform.php', ['error' => 'Username atau email sudah terdaftar.']);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $success = $this->donasiModel->registerUser($username, $email, $passwordHash);

        if ($success) {
            $_SESSION['success_message'] = "Registrasi berhasil! Silakan login.";
            $this->redirect('index.php?c=DonasiController&m=loginForm');
        } else {
            $this->loadView('registerform.php', ['error' => 'Registrasi gagal. Silakan coba lagi.']);
        }
    }

    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect('index.php?c=DonasiController&m=loginForm');
    }

    // ICHA
    public function index(){
        $data['kampanye'] = $this->donasiModel->getAllKampanye();
        $this->loadView('header.php');
        $this->loadView('beranda.php', $data);
    }


    public function tambah(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $judul = $_POST['judul'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $lokasi = $_POST['lokasi'] ?? null;
            $owner = $_POST['owner'] ?? '';

            if (empty($judul) || empty($deskripsi) || empty($owner) || !isset($_FILES['foto']) || $_FILES['foto']['error'] != UPLOAD_ERR_OK) {
                header("Location: index.php?c=DonasiController&m=dataInvalid");
                exit;
            }

            $fotoName = $_FILES['foto']['name'];
            $tmpName = $_FILES['foto']['tmp_name'];
            $path = "gambar/" . basename($fotoName); 

            if (!is_dir('gambar')) {
                mkdir('gambar', 0777, true);
            }

            if (move_uploaded_file($tmpName, $path)) {
                $this->donasiModel->tambahKampanye($judul, $lokasi, $deskripsi, $path, $owner);
                header("Location: index.php?c=DonasiController&m=berhasil");
                exit;
            } else {
                error_log("Gagal memindahkan file upload: " . $tmpName . " ke " . $path);
                header("Location: index.php?c=DonasiController&m=dataInvalid"); 
                exit;
            }
        } else {
            $this->loadView('header.php');
            $this->loadView('tambah.php');

        }
    }

    public function berhasil()
    {
        $this->loadView('header.php');
        $this->loadView('berhasil.php');
    }

    public function dataInvalid()
    {
        $this->loadView('header.php');
        $this->loadView('data-invalid.php');
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "<p>ID kampanye tidak ditemukan.</p>";
            return;
        }

        $kampanye = $this->donasiModel->getKampanyeById($id);
        if (!$kampanye) {
            echo "<p>Kampanye tidak ditemukan di database.</p>";
            return;
        }

        $this->loadView('header.php');
        $this->loadView("detail.php", ["kampanye" => $kampanye]);
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $judul = $_POST['judul'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $lokasi = $_POST['lokasi'] ?? null;
            $owner = $_POST['owner'] ?? '';

            $fotoPath = $_POST['existing_foto'] ?? null; 

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $fotoName = $_FILES['foto']['name'];
                $tmpName = $_FILES['foto']['tmp_name'];
                $newPath = "gambar/" . basename($fotoName);

                if (!is_dir('gambar')) {
                    mkdir('gambar', 0777, true);
                }

                if (move_uploaded_file($tmpName, $newPath)) {
                    $fotoPath = $newPath; 
                } else {
                    error_log("Gagal memindahkan file upload saat edit: " . $tmpName);
                }
            }
            $this->donasiModel->updateKampanye($id, $judul, $lokasi, $deskripsi, $fotoPath, $owner);
            header("Location: index.php?c=DonasiController&m=detail&id=$id"); 
            exit;
        } else {
            $data['kampanye'] = $this->donasiModel->getKampanyeById($id);
            if (!$data['kampanye']) {
                header("Location: index.php?c=DonasiController&m=beranda"); 
                exit;
            }
            $this->loadView('header.php');
            $this->loadView('edit.php', $data);

        }
    }

    public function hapus()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?c=DonasiController&m=beranda");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $kampanye = $this->donasiModel->getKampanyeById($id);

            if ($kampanye && isset($kampanye['foto']) && file_exists($kampanye['foto'])) {
                unlink($kampanye['foto']); 
            }

            $this->donasiModel->deleteKampanye($id);

            header("Location: index.php?c=DonasiController&m=beranda");
            exit;
        } else {
            $data['kampanye'] = $this->donasiModel->getKampanyeById($id);
            if (!$data['kampanye']) {
                header("Location: index.php?c=DonasiController&m=beranda");
                exit;
            }
            $this->loadView('header.php');
            $this->loadView('hapus.php', $data);
        }
    }


    // ICHA
    public function beranda()
    {
        $data['kampanye'] = $this->donasiModel->getAllKampanye();
        $this->loadView('header.php');
        $this->loadView('beranda.php', $data);
    }


    //NAVA
    function tahunRekap()
    {
        $model = $this->loadModel('DonasiModel');
        $rekap2 = $model->getAllYear();
        $this->loadView('hal-tahuntransaksi.php', ['rekap2' => $rekap2]);
    }

    function getRekapByYear()
    {
        $tahun = $_REQUEST['tahun'];
        $model = $this->loadModel('DonasiModel');
        $bulan2 = $model->getRekapByYear($tahun);
        $this->loadView('hal-bulantransaksi.php', ['bulan2' => $bulan2]);
    }

    function bulanRekap()
    {
        $model = $this->loadModel('DonasiModel');
        $rekap2 = $model->getAllMonth();
        $this->loadView('hal-bulantransaksi.php', ['rekap2' => $rekap2]);
    }

    function getRekapByMonth()
    {
        $bulan = $_REQUEST['bulan'];
        $tahun = $_REQUEST['tahun'];
        $bulanMap = [
            "Januari" => 1,
            "Februari" => 2,
            "Maret" => 3,
            "April" => 4,
            "Mei" => 5,
            "Juni" => 6,
            "Juli" => 7,
            "Agustus" => 8,
            "September" => 9,
            "Oktober" => 10,
            "November" => 11,
            "Desember" => 12
        ];
        $angkaBulan = isset($bulanMap[$bulan]) ? $bulanMap[$bulan] : null;
        $model = $this->loadModel('DonasiModel');
        $transaksi2 = $model->getRekapByMonth($angkaBulan, $tahun);
        $this->loadView('hal-datatransaksi.php', ['transaksi2' => $transaksi2]);
    }

    function search()
    {
        $keyword = $_GET['search_tanggal'] ?? '';
        $model = $this->loadModel('DonasiModel');
        $transaksi2 = $model->searchTanggal($keyword);
        $this->loadView('hal-datatransaksi.php', ['transaksi2' => $transaksi2]);
    }

}
?>