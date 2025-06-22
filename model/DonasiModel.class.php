<?php
require_once('Model.class.php'); 

class DonasiModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function findUserByIdentifier($identifier)
    {
        $identifier = $this->db->real_escape_string($identifier);
        $sql = "SELECT id_user, username, email, password FROM user WHERE username = ? OR email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement gagal (findUserByIdentifier): " . $this->db->error);
            return null;
        }
        $stmt->bind_param('ss', $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function registerUser($username, $email, $passwordHash)
    {
        $username = $this->db->real_escape_string($username);
        $email = $this->db->real_escape_string($email);
        $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement gagal (registerUser): " . $this->db->error);
            return false;
        }
        $stmt->bind_param('sss', $username, $email, $passwordHash);
        $isSuccess = $stmt->execute();
        if (!$isSuccess) {
            error_log("Eksekusi statement gagal (registerUser): " . $stmt->error);
        }
        $stmt->close();
        return $isSuccess;
    }

    public function ambilSemuaProgramDonasi()
    {
        $sql = "SELECT id_program, judul_program, deskripsi_program, gambar_program, penyelenggara_program FROM donasi_program ORDER BY id_program DESC";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function ambilProgramDonasiBerdasarkanId($id_program)
    {
        $sql = "SELECT id_program, judul_program, deskripsi_program, gambar_program, penyelenggara_program FROM donasi_program WHERE id_program = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement gagal (ambilProgramDonasiBerdasarkanId): " . $this->db->error);
            return null;
        }
        $stmt->bind_param('i', $id_program);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function ambilNominalUntukProgram($id_program)
    {
        $sql = "SELECT jumlah_nominal FROM nominal WHERE id_program = ? ORDER BY jumlah_nominal ASC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement gagal (ambilNominalUntukProgram): " . $this->db->error);
            return [];
        }
        $stmt->bind_param('i', $id_program);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }


    public function ambilMetodePembayaranUntukProgram($id_program)
    {
        $sql = "SELECT id_metodepembayaran, nama_bank FROM metode_pembayaran WHERE id_program = ? ORDER BY nama_bank ASC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement gagal (ambilMetodePembayaranUntukProgram): " . $this->db->error);
            return [];
        }
        $stmt->bind_param('i', $id_program);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }


    public function ambilRekeningTransferByMetodeId($id_metodepembayaran)
    {
        $sql = "SELECT id_rekening, nama_bank, nomor_rekening FROM rekening_transfer WHERE id_metodepembayaran = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement gagal (ambilRekeningTransferByMetodeId): " . $this->db->error);
            return [];
        }
        $stmt->bind_param('i', $id_metodepembayaran);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }


    public function simpanTransaksiDonasi($id_program, $id_metodepembayaran, $id_user, $jumlah_nominal, $path_bukti_pembayaran)
    {
        $sql = "INSERT INTO donasi_transaksi (id_program, id_metodepembayaran, id_user, jumlah_nominal, bukti_pembayaran, tanggal_donasi) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement gagal (simpanTransaksiDonasi): " . $this->db->error);
            return false;
        }
        $stmt->bind_param('iiiis', $id_program, $id_metodepembayaran, $id_user, $jumlah_nominal, $path_bukti_pembayaran);
        $isSuccess = $stmt->execute();
        if (!$isSuccess) {
            error_log("Eksekusi statement gagal (simpanTransaksiDonasi): " . $stmt->error);
        }
        $stmt->close();
        return $isSuccess;
    }

    // ICHA
    public function getAllKampanye()
    {
        $sql = "SELECT * FROM kampanye ORDER BY tanggal_upload DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function tambahKampanye($judul, $lokasi, $deskripsi, $foto, $owner)
    {
        $stmt = $this->db->prepare("INSERT INTO kampanye (judul, lokasi, deskripsi, foto, owner)
                                      VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$judul, $lokasi, $deskripsi, $foto, $owner]);
    }

    public function getKampanyeById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM kampanye WHERE id = ?");
        $stmt->bind_param("i", $id); 
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); 
    }


    public function updateKampanye($id, $judul, $lokasi, $deskripsi, $foto, $owner)
    {
        $sql = "UPDATE kampanye SET judul = ?, lokasi = ?, deskripsi = ?, foto = ?, owner = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$judul, $lokasi, $deskripsi, $foto, $owner, $id]);
    }

    public function deleteKampanye($id)
    {
        $sql = "DELETE FROM kampanye WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    //NAVA
    function getAllRekap()
    {
        $sql = "SELECT * FROM donasi_transaksi";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getAllYear()
    {
        $sql = "SELECT DISTINCT YEAR(tanggal_donasi) AS tahun
        FROM donasi_transaksi
        ORDER BY tahun DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getAllMonth()
    {
        $sql = "SELECT DISTINCT MONTH(tanggal_donasi) AS bulan
        FROM donasi_transaksi
        ORDER BY bulan DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getRekapByMonth($bulan, $tahun)
    {
        $sql = "SELECT dt.*, u.*, dp.*, mp.*
                FROM donasi_transaksi   AS dt
                JOIN donasi_program     AS dp ON dt.id_program          = dp.id_program
                JOIN user               AS u  ON dt.id_user             = u.id_user
                JOIN metode_pembayaran  AS mp ON dt.id_metodepembayaran = mp.id_metodepembayaran
                WHERE MONTH(dt.tanggal_donasi) = '$bulan'
                AND YEAR(dt.tanggal_donasi)  = '$tahun'
                ORDER BY dt.tanggal_donasi ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getRekapByYear($tahun)
    {
        $sql = "SELECT DISTINCT MONTH(tanggal_donasi) AS bulan,
                CASE MONTH(tanggal_donasi)
                WHEN 1 THEN 'Januari'
                WHEN 2 THEN 'Februari'
                WHEN 3 THEN 'Maret'
                WHEN 4 THEN 'April'
                WHEN 5 THEN 'Mei'
                WHEN 6 THEN 'Juni'
                WHEN 7 THEN 'Juli'
                WHEN 8 THEN 'Agustus'
                WHEN 9 THEN 'September'
                WHEN 10 THEN 'Oktober'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'Desember'
                END AS nama_bulan FROM donasi_transaksi WHERE YEAR(tanggal_donasi) = '$tahun' ORDER BY tanggal_donasi ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function getRekap($id)
    {
        $sql = "SELECT
                dt.*,
                dp.id_program,
                dp.judul_program,
                dp.penyelenggara_program,
                mp.nama_bank,
                u.username
                FROM donasi_transaksi AS dt
                JOIN donasi_program AS dp ON dt.id_program = dp.id_program
                JOIN user AS u ON dt.id_user = u.id_user
                JOIN metode_pembayaran AS mp ON dt.id_metodepembayaran = mp.id_metodepembayaran
                WHERE dt.id_transaksi = $id LIMIT 1";
        $result = $this->db->query($sql);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows[0];
    }

    function searchTanggal($keyword)
    {
        $keyword = $this->db->real_escape_string($keyword);
        $sql = "SELECT dt.*, dp.judul_program, dp.penyelenggara_program, mp.nama_bank, u.username
            FROM donasi_transaksi AS dt
            JOIN donasi_program AS dp ON dt.id_program = dp.id_program
            JOIN user AS u ON dt.id_user = u.id_user
            JOIN metode_pembayaran AS mp ON dt.id_metodepembayaran = mp.id_metodepembayaran
            WHERE dt.tanggal_donasi LIKE '%$keyword%' OR dt.jumlah_nominal LIKE '%$keyword%'";
        ;
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>