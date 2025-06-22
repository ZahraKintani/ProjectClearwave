const express = require("express");
const cors = require("cors");
const bcrypt = require("bcrypt");
const mysql = require("mysql2/promise");
const jwt = require("jsonwebtoken");

const app = express();
const PORT = 8000; 
const JWT_SECRET = "clearwave";

app.use(express.json()); 
app.use(cors()); 

const router = express.Router();

const dbConfig = {
  host: "localhost",
  user: "root",
  password: "", 
  database: "clearwave",
};

const getDbConnection = async () => {
  try {
    const connection = await mysql.createConnection(dbConfig);
    return connection;
  } catch (error) {
    console.error("Database connection failed:", error);
    throw new Error("Failed to connect to the database.");
  }
};

const checkToken = (req, res, next) => {
  const token = req.body.token; 

  try {
    if (!token) {
      return res.status(401).json({ message: "Access denied. No token provided." });
    }

    const payload = jwt.verify(token, JWT_SECRET);

    if (!payload) {
      return res.status(401).json({ message: "Invalid token." });
    }

    req.body.payload = payload; 
    next(); 
  } catch (error) {
    console.error("Token verification failed:", error);
    res.status(401).json({ message: "Access denied: " + error.message });
  }
};

const findUserByIdentifier = async (identifier) => {
  const db = await getDbConnection();
  try {
    const sql = "SELECT id_user, username, email, password FROM user WHERE username = ? OR email = ?";
    const [rows] = await db.execute(sql, [identifier, identifier]);
    return rows[0] || null; 
  } catch (error) {
    console.error("Error in findUserByIdentifier:", error);
    return null;
  } finally {
    db.end(); // Close the database connection
  }
};

router.post("/register", async (req, res) => {
  const { username, email, password } = req.body;

  if (!username || !email || !password) {
    return res.status(400).json({ message: "Username, email, and password are required." });
  }

  try {
    const existingUser = await findUserByIdentifier(email); 
    if (existingUser) {
      return res.status(409).json({ message: "User with this email or username already exists." });
    }

    const passwordHash = await bcrypt.hash(password, 10); 

    const db = await getDbConnection();
    try {
      const sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
      const [result] = await db.execute(sql, [username, email, passwordHash]);

      if (result.affectedRows === 1) {
        res.status(201).json({ message: "User registered successfully.", userId: result.insertId });
      } else {
        res.status(500).json({ message: "Failed to register user." });
      } 
    } catch (error) {
      console.error("Error registering user:", error);
      res.status(500).json({ message: "Internal server error during registration." });
    } finally {
      db.end();
    }
  } catch (error) {
    console.error("Error during user registration setup:", error);
    res.status(500).json({ message: "Internal server error." });
  }
});


router.post("/auth", async (req, res) => {
  const { identifier, password } = req.body; 

  if (!identifier || !password) {
    return res.status(400).json({ message: "Identifier (username/email) and password are required." });
  }

  try {
    const user = await findUserByIdentifier(identifier);

    if (!user) {
      return res.status(401).json({ message: "Invalid username/email or password." });
    }

    const passwordMatch = await bcrypt.compare(password, user.password);

    if (!passwordMatch) {
      return res.status(401).json({ message: "Invalid username/email or password." });
    }

    const token = jwt.sign(
      { id_user: user.id_user, username: user.username, email: user.email }, 
      JWT_SECRET,
      { expiresIn: "1h" } 
    );

    res.status(200).json({ message: "Authentication successful.", token });
  } catch (error) {
    console.error("Error during authentication:", error);
    res.status(500).json({ message: "Internal server error during authentication." });
  }
});

router.get("/secretService", checkToken, (req, res) => {
  try {
    const payload = req.body.payload;
    res.status(200).json({ message: `Welcome ${payload.username}! This is a secret page.`, userData: payload });
  } catch (error) {
    res.status(401).json({ message: "Access denied. " + error.message });
  }
});


router.get("/donasi-programs", async (req, res) => {
  const db = await getDbConnection();
  try {
    const sql = "SELECT id_program, judul_program, deskripsi_program, gambar_program, penyelenggara_program FROM donasi_program ORDER BY id_program DESC";
    const [rows] = await db.execute(sql);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching all donation programs:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/donasi-programs/:id", async (req, res) => {
  const id_program = parseInt(req.params.id);

  if (isNaN(id_program)) {
    return res.status(400).json({ message: "Invalid program ID." });
  }

  const db = await getDbConnection();
  try {
    const sql = "SELECT id_program, judul_program, deskripsi_program, gambar_program, penyelenggara_program FROM donasi_program WHERE id_program = ?";
    const [rows] = await db.execute(sql, [id_program]);
    if (rows.length > 0) {
      res.status(200).json(rows[0]);
    } else {
      res.status(404).json({ message: "Donation program not found." });
    }
  } catch (error) {
    console.error("Error fetching donation program by ID:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/donasi-programs/:id/nominals", async (req, res) => {
  const id_program = parseInt(req.params.id);

  if (isNaN(id_program)) {
    return res.status(400).json({ message: "Invalid program ID." });
  }

  const db = await getDbConnection();
  try {
    const sql = "SELECT jumlah_nominal FROM nominal WHERE id_program = ? ORDER BY jumlah_nominal ASC";
    const [rows] = await db.execute(sql, [id_program]);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching nominals for program:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/donasi-programs/:id/payment-methods", async (req, res) => {
  const id_program = parseInt(req.params.id);

  if (isNaN(id_program)) {
    return res.status(400).json({ message: "Invalid program ID." });
  }

  const db = await getDbConnection();
  try {
    const sql = "SELECT id_metodepembayaran, nama_bank FROM metode_pembayaran WHERE id_program = ? ORDER BY nama_bank ASC";
    const [rows] = await db.execute(sql, [id_program]);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching payment methods for program:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/payment-methods/:id/bank-accounts", async (req, res) => {
  const id_metodepembayaran = parseInt(req.params.id);

  if (isNaN(id_metodepembayaran)) {
    return res.status(400).json({ message: "Invalid payment method ID." });
  }

  const db = await getDbConnection();
  try {
    const sql = "SELECT id_rekening, nama_bank, nomor_rekening FROM rekening_transfer WHERE id_metodepembayaran = ?";
    const [rows] = await db.execute(sql, [id_metodepembayaran]);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching bank accounts by method ID:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.post("/donasi-transactions", async (req, res) => {
  const { id_program, id_metodepembayaran, id_user, jumlah_nominal, path_bukti_pembayaran } = req.body;

  if (!id_program || !id_metodepembayaran || !id_user || !jumlah_nominal || !path_bukti_pembayaran) {
    return res.status(400).json({ message: "All transaction fields are required." });
  }

  const db = await getDbConnection();
  try {
    const sql = "INSERT INTO donasi_transaksi (id_program, id_metodepembayaran, id_user, jumlah_nominal, bukti_pembayaran, tanggal_donasi) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
    const [result] = await db.execute(sql, [id_program, id_metodepembayaran, id_user, jumlah_nominal, path_bukti_pembayaran]);

    if (result.affectedRows === 1) {
      res.status(201).json({ message: "Donation transaction saved successfully.", transactionId: result.insertId });
    } else {
      res.status(500).json({ message: "Failed to save donation transaction." });
    }
  } catch (error) {
    console.error("Error saving donation transaction:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/campaigns", async (req, res) => {
  const db = await getDbConnection();
  try {
    const sql = "SELECT * FROM kampanye ORDER BY tanggal_upload DESC";
    const [rows] = await db.execute(sql);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching all campaigns:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.post("/campaigns", async (req, res) => {
  const { judul, lokasi, deskripsi, foto, owner } = req.body;

  if (!judul || !lokasi || !deskripsi || !foto || !owner) {
    return res.status(400).json({ message: "All campaign fields are required." });
  }

  const db = await getDbConnection();
  try {
    const sql = "INSERT INTO kampanye (judul, lokasi, deskripsi, foto, owner) VALUES (?, ?, ?, ?, ?)";
    const [result] = await db.execute(sql, [judul, lokasi, deskripsi, foto, owner]);

    if (result.affectedRows === 1) {
      res.status(201).json({ message: "Campaign added successfully.", campaignId: result.insertId });
    } else {
      res.status(500).json({ message: "Failed to add campaign." });
    }
  } catch (error) {
    console.error("Error adding campaign:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/campaigns/:id", async (req, res) => {
  const id = parseInt(req.params.id);

  if (isNaN(id)) {
    return res.status(400).json({ message: "Invalid campaign ID." });
  }

  const db = await getDbConnection();
  try {
    const sql = "SELECT * FROM kampanye WHERE id = ?";
    const [rows] = await db.execute(sql, [id]);
    if (rows.length > 0) {
      res.status(200).json(rows[0]);
    } else {
      res.status(404).json({ message: "Campaign not found." });
    }
  } catch (error) {
    console.error("Error fetching campaign by ID:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.put("/campaigns/:id", async (req, res) => {
  const id = parseInt(req.params.id);
  const { judul, lokasi, deskripsi, foto, owner } = req.body;

  if (isNaN(id)) {
    return res.status(400).json({ message: "Invalid campaign ID." });
  }
  if (!judul || !lokasi || !deskripsi || !foto || !owner) {
    return res.status(400).json({ message: "All campaign fields are required for update." });
  }

  const db = await getDbConnection();
  try {
    const sql = "UPDATE kampanye SET judul = ?, lokasi = ?, deskripsi = ?, foto = ?, owner = ? WHERE id = ?";
    const [result] = await db.execute(sql, [judul, lokasi, deskripsi, foto, owner, id]);

    if (result.affectedRows === 1) {
      res.status(200).json({ message: "Campaign updated successfully." });
    } else if (result.affectedRows === 0) {
      res.status(404).json({ message: "Campaign not found or no changes made." });
    } else {
      res.status(500).json({ message: "Failed to update campaign." });
    }
  } catch (error) {
    console.error("Error updating campaign:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.delete("/campaigns/:id", async (req, res) => {
  const id = parseInt(req.params.id);

  if (isNaN(id)) {
    return res.status(400).json({ message: "Invalid campaign ID." });
  }

  const db = await getDbConnection();
  try {
    const sql = "DELETE FROM kampanye WHERE id = ?";
    const [result] = await db.execute(sql, [id]);

    if (result.affectedRows === 1) {
      res.status(200).json({ message: "Campaign deleted successfully." });
    } else if (result.affectedRows === 0) {
      res.status(404).json({ message: "Campaign not found." });
    } else {
      res.status(500).json({ message: "Failed to delete campaign." });
    }
  } catch (error) {
    console.error("Error deleting campaign:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/recap", async (req, res) => {
  const db = await getDbConnection();
  try {
    const sql = "SELECT * FROM donasi_transaksi";
    const [rows] = await db.execute(sql);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching all recaps:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/recap/years", async (req, res) => {
  const db = await getDbConnection();
  try {
    const sql = "SELECT DISTINCT YEAR(tanggal_donasi) AS tahun FROM donasi_transaksi ORDER BY tahun DESC";
    const [rows] = await db.execute(sql);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching all recap years:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/recap/months", async (req, res) => {
  const db = await getDbConnection();
  try {
    const sql = "SELECT DISTINCT MONTH(tanggal_donasi) AS bulan FROM donasi_transaksi ORDER BY bulan DESC";
    const [rows] = await db.execute(sql);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching all recap months:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/recap/month/:month/:year", async (req, res) => {
  const month = parseInt(req.params.month);
  const year = parseInt(req.params.year);

  if (isNaN(month) || isNaN(year) || month < 1 || month > 12) {
    return res.status(400).json({ message: "Invalid month or year." });
  }

  const db = await getDbConnection();
  try {
    const sql = `
      SELECT dt.*, u.username, dp.judul_program, mp.nama_bank
      FROM donasi_transaksi AS dt
      JOIN donasi_program AS dp ON dt.id_program = dp.id_program
      JOIN user AS u ON dt.id_user = u.id_user
      JOIN metode_pembayaran AS mp ON dt.id_metodepembayaran = mp.id_metodepembayaran
      WHERE MONTH(dt.tanggal_donasi) = ? AND YEAR(dt.tanggal_donasi) = ?
      ORDER BY dt.tanggal_donasi ASC
    `;
    const [rows] = await db.execute(sql, [month, year]);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching recap by month and year:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/recap/year/:year", async (req, res) => {
  const year = parseInt(req.params.year);

  if (isNaN(year)) {
    return res.status(400).json({ message: "Invalid year." });
  }

  const db = await getDbConnection();
  try {
    const sql = `
      SELECT DISTINCT MONTH(tanggal_donasi) AS bulan,
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
      END AS nama_bulan FROM donasi_transaksi WHERE YEAR(tanggal_donasi) = ? ORDER BY tanggal_donasi ASC
    `;
    const [rows] = await db.execute(sql, [year]);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error fetching recap months by year:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/recap/:id", async (req, res) => {
  const id = parseInt(req.params.id);

  if (isNaN(id)) {
    return res.status(400).json({ message: "Invalid transaction ID." });
  }

  const db = await getDbConnection();
  try {
    const sql = `
      SELECT
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
      WHERE dt.id_transaksi = ? LIMIT 1
    `;
    const [rows] = await db.execute(sql, [id]);
    if (rows.length > 0) {
      res.status(200).json(rows[0]);
    } else {
      res.status(404).json({ message: "Transaction recap not found." });
    }
  } catch (error) {
    console.error("Error fetching recap by ID:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});


router.get("/recap/search/:keyword", async (req, res) => {
  const keyword = req.params.keyword;

  if (!keyword) {
    return res.status(400).json({ message: "Search keyword is required." });
  }

  const db = await getDbConnection();
  try {
    const sql = `
      SELECT dt.*, dp.judul_program, dp.penyelenggara_program, mp.nama_bank, u.username
      FROM donasi_transaksi AS dt
      JOIN donasi_program AS dp ON dt.id_program = dp.id_program
      JOIN user AS u ON dt.id_user = u.id_user
      JOIN metode_pembayaran AS mp ON dt.id_metodepembayaran = mp.id_metodepembayaran
      WHERE dt.tanggal_donasi LIKE ? OR dt.jumlah_nominal LIKE ?
    `;
    const searchTerm = `%${keyword}%`; // Use LIKE for partial matches
    const [rows] = await db.execute(sql, [searchTerm, searchTerm]);
    res.status(200).json(rows);
  } catch (error) {
    console.error("Error searching recap by date/nominal:", error);
    res.status(500).json({ message: "Internal server error." });
  } finally {
    db.end();
  }
});

app.use("/", router);

app.listen(PORT, () => {
  console.log(`Donasi API is running at port: ${PORT}`);
});
