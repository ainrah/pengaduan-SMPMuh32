<?php
require_once("database.php"); // Pastikan file koneksi database sudah disiapkan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ambil data dari form dengan validasi
        $id_laporan = $_POST['id_laporan'] ?? null;
        $nama_plp = $_POST['nama_plp'] ?? null;
        $no_hp_plp = $_POST['no_hp_plp'] ?? null;
        $kls_plp = $_POST['kls_plp'] ?? '';
        $nama_krb = $_POST['nama_krb'] ?? '';
        $no_hp_krb = $_POST['no_hp_krb'] ?? '';
        $kls_krb = $_POST['kls_krb'] ?? '';
        $nama_plk = $_POST['nama_plk'] ?? '';
        $no_hp_plk = $_POST['no_hp_plk'] ?? '';
        $tanggal_pengaduan = $_POST['tanggal_pengaduan'] ?? null;
        $tanggal_kejadian = $_POST['tanggal_kejadian'] ?? null;
        $tempat_kejadian = $_POST['tempat_kejadian'] ?? '';
        $kategori_kekerasan = $_POST['kategori_kekerasan'] ?? '';
        $subjek_pengaduan = $_POST['subjek_pengaduan'] ?? '';
        $kronologi_kejadian = $_POST['kronologi_kejadian'] ?? null;
        $bukti_kekerasan = $_POST['bukti_kekerasan'] ?? '';
        $status = $_POST['status'] ?? null;

        // Validasi data wajib diisi
        if (empty($id_laporan) || empty($nama_plp) || empty($no_hp_plp)  || empty($kronologi_kejadian)) {
            echo "Semua field harus diisi!";
            exit;
        }

        // Update data ke database
        $sql = "UPDATE laporan SET 
                    nama_plp = :nama_plp,
                    no_hp_plp = :no_hp_plp,
                    kls_plp = :kls_plp,
                    nama_krb = :nama_krb,
                    no_hp_krb = :no_hp_krb,
                    kls_krb = :kls_krb,
                    nama_plk = :nama_plk,
                    no_hp_plk = :no_hp_plk,
                    tanggal_pengaduan = :tanggal_pengaduan,
                    tanggal_kejadian = :tanggal_kejadian,
                    tempat_kejadian = :tempat_kejadian,
                    kategori_kekerasan = :kategori_kekerasan,
                    subjek_pengaduan = :subjek_pengaduan,
                    kronologi_kejadian = :kronologi_kejadian,
                    bukti_kekerasan = :bukti_kekerasan,
                    status = :status
                WHERE id_laporan = :id_laporan";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_laporan', $id_laporan, PDO::PARAM_STR);
        $stmt->bindValue(':nama_plp', htmlspecialchars($nama_plp), PDO::PARAM_STR);
        $stmt->bindValue(':no_hp_plp', htmlspecialchars($no_hp_plp), PDO::PARAM_STR);
        $stmt->bindValue(':kls_plp', htmlspecialchars($kls_plp), PDO::PARAM_STR);
        $stmt->bindValue(':nama_krb', htmlspecialchars($nama_krb), PDO::PARAM_STR);
        $stmt->bindValue(':no_hp_krb', htmlspecialchars($no_hp_krb), PDO::PARAM_STR);
        $stmt->bindValue(':kls_krb', htmlspecialchars($kls_krb), PDO::PARAM_STR);
        $stmt->bindValue(':nama_plk', htmlspecialchars($nama_plk), PDO::PARAM_STR);
        $stmt->bindValue(':no_hp_plk', htmlspecialchars($no_hp_plk), PDO::PARAM_STR);
        $stmt->bindValue(':tanggal_pengaduan', htmlspecialchars($tanggal_pengaduan), PDO::PARAM_STR);
        $stmt->bindValue(':tanggal_kejadian', htmlspecialchars($tanggal_kejadian), PDO::PARAM_STR);
        $stmt->bindValue(':tempat_kejadian', htmlspecialchars($tempat_kejadian), PDO::PARAM_STR);
        $stmt->bindValue(':kategori_kekerasan', htmlspecialchars($kategori_kekerasan), PDO::PARAM_STR);
        $stmt->bindValue(':subjek_pengaduan', htmlspecialchars($subjek_pengaduan), PDO::PARAM_STR);
        $stmt->bindValue(':kronologi_kejadian', htmlspecialchars($kronologi_kejadian), PDO::PARAM_STR);
        $stmt->bindValue(':bukti_kekerasan', htmlspecialchars($bukti_kekerasan), PDO::PARAM_STR);
        $stmt->bindValue(':status', htmlspecialchars($status), PDO::PARAM_STR);

        $stmt->execute();

        // Redirect atau tampilkan pesan sukses
        header("Location: index.php?success=1");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
