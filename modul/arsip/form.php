<?php
// init variables
$vno_surat = '';
$vtanggal_surat = '';
$vtanggal_diterima = '';
$vperihal = '';
$vid_departemen = '';
$vid_pengirim = '';
$vfile = '';
$vid = '';
$file_name = '';
$showAlert = false;
$alertType = '';
$alertMsg = '';

if(isset($_GET['hal']) && $_GET['hal'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = mysqli_prepare($koneksi, "SELECT id_arsip, no_surat, tanggal_surat, tanggal_diterima, perihal, id_departemen, id_pengirim, file FROM arsip WHERE id_arsip = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_result, $no_surat_result, $tanggal_surat_result, $tanggal_diterima_result, $perihal_result, $id_departemen_result, $id_pengirim_result, $file_result);

    if(mysqli_stmt_fetch($stmt)) {
        $vid = $id_result;
        $vno_surat = $no_surat_result;
        $vtanggal_surat = $tanggal_surat_result;
        $vtanggal_diterima = $tanggal_diterima_result;
        $vperihal = $perihal_result;
        $vid_departemen = $id_departemen_result;
        $vid_pengirim = $id_pengirim_result;
        $vfile = $file_result;
    }
    mysqli_stmt_close($stmt);
}

if($file_name == '') {
   $file_name = $_POST['file_lama'] ?? $vfile;
}
// === HANDLE INSERT / UPDATE ===
if(isset($_POST['bsimpan'])) {

    // Ambil data form
    $no_surat = trim($_POST['no_surat'] ?? '');
    $tanggal_surat = trim($_POST['tanggal_surat'] ?? '');
    $tanggal_diterima = trim($_POST['tanggal_diterima'] ?? '');
    $perihal = trim($_POST['perihal'] ?? '');
    $id_departemen = trim($_POST['id_departemen'] ?? '');
    $id_pengirim = trim($_POST['id_pengirim'] ?? '');

    // ID arsip untuk proses UPDATE
    $id_post = isset($_POST['id_arsip']) ? (int)$_POST['id_arsip'] : 0;

    // Default: pakai file lama
    $file_name = $_POST['file_lama'] ?? $vfile;

    // === HANDLE FILE UPLOAD ===
    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_original = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];

        // Validasi ukuran file
        if($file_size > 5 * 1024 * 1024) {
            $showAlert = true;
            $alertType = 'error';
            $alertMsg = 'Ukuran file terlalu besar! Max 5MB.';
        } else {

            $file_ext = strtolower(pathinfo($file_original, PATHINFO_EXTENSION));
            $allowed_ext = ['pdf','doc','docx','xlsx','jpg','jpeg','png'];

            if(!in_array($file_ext, $allowed_ext)) {
                $showAlert = true;
                $alertType = 'error';
                $alertMsg = 'Tipe file tidak didukung!';
            } else {

                // generate nama file baru
                $file_name = time() . '_' . str_replace(' ', '_', $file_original);
                $upload_path = 'file/' . $file_name;

                // buat folder jika belum ada
                if(!is_dir('file')) mkdir('file', 0755, true);

                // upload file
                if(move_uploaded_file($file_tmp, $upload_path)) {

                    // hapus file lama jika UPDATE
                    if($id_post > 0 && !empty($_POST['file_lama']) 
                        && file_exists('file/' . $_POST['file_lama'])) {

                        unlink('file/' . $_POST['file_lama']);
                    }

                } else {
                    $showAlert = true;
                    $alertType = 'error';
                    $alertMsg = 'Gagal upload file!';
                }
            }
        }
    }


    // VALIDASI WAJIB
if(!$showAlert && ($no_surat === '' || $tanggal_surat === '' || $perihal === '' || $id_departemen === '' || $id_pengirim === '')) {

    $showAlert = true;
    $alertType = 'error';
    $alertMsg = 'No Surat, Tanggal Surat, Perihal, Departemen, dan Pengirim tidak boleh kosong!';

} elseif(!$showAlert) {

    // ===========================
    //   MODE UPDATE
    // ===========================
    if ($id_post > 0) {

        $stmt = mysqli_prepare($koneksi,
            "UPDATE arsip SET 
                no_surat=?, 
                tanggal_surat=?, 
                tanggal_diterima=?, 
                perihal=?, 
                id_departemen=?, 
                id_pengirim=?, 
                file=?
             WHERE id_arsip=?"
        );

        if (!$stmt) die("Query gagal diparsing: " . mysqli_error($koneksi));

        mysqli_stmt_bind_param($stmt, "ssssissi",
            $no_surat,
            $tanggal_surat,
            $tanggal_diterima,
            $perihal,
            $id_departemen,
            $id_pengirim,
            $file_name,
            $id_post
        );

        if (mysqli_stmt_execute($stmt)) {

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Update data SUKSES!',
                    confirmButtonColor: '#0054A6'
                }).then(() => {
                    document.location='?halaman=arsip_surat';
                });
            </script>";
            exit;

        } else {

            $showAlert = true;
            $alertType = 'error';
            $alertMsg = 'Update data GAGAL!';
        }

        mysqli_stmt_close($stmt);
    }

    // ===========================
    //   MODE INSERT
    // ===========================
    else {

        $stmt = mysqli_prepare($koneksi,
            "INSERT INTO arsip 
                (no_surat, tanggal_surat, tanggal_diterima, perihal, id_departemen, id_pengirim, file)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) die("Query gagal diparsing: " . mysqli_error($koneksi));

        mysqli_stmt_bind_param($stmt, "ssssiss",
            $no_surat,
            $tanggal_surat,
            $tanggal_diterima,
            $perihal,
            $id_departemen,
            $id_pengirim,
            $file_name
        );

        if (mysqli_stmt_execute($stmt)) {

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Input data SUKSES!',
                    confirmButtonColor: '#0054A6'
                }).then(() => {
                    document.location='?halaman=arsip_surat';
                });
            </script>";
            exit;

        } else {

            $showAlert = true;
            $alertType = 'error';
            $alertMsg = 'Input data GAGAL!';
        }

        mysqli_stmt_close($stmt);
    }
}
}
?>

<!-- SHOW ALERT SWEETALERT2 (hanya untuk error) -->
<?php if($showAlert): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: '<?=$alertType?>',
    title: '<?=$alertType === "success" ? "Sukses" : "Peringatan"?>',
    text: '<?=$alertMsg?>',
    confirmButtonColor: '<?=$alertType === "success" ? "#0054A6" : "#dc3545"?>'
});
</script>
<?php endif; ?>

<style>
     body {
    background: url("assets/SMK3.jpg") no-repeat center center fixed;
    background-size: cover;
    position: relative;
    z-index: 0;
}

body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45); /* Overlay lebih lembut */
    z-index: -1;
}
    .card-pkt {
        border-left: 6px solid #F37021;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: 0.3s;
    }

    .card-pkt:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    .card-pkt .card-header {
        background-color: #0054A6;
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .btn-pkt {
        background-color: #28a745;
        border: none;
        color: white;
        border-radius: 10px;
        padding: 12px 25px;
        font-size: 15px;
        font-weight: 600;
    }

    .btn-pkt:hover {
        background-color: #218838;
        color: white;
    }
</style>

<div class="card card-pkt mt-3 mb-3">
    <h5 class="card-header">Form Data Arsip Surat</h5>
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_arsip" value="<?=htmlspecialchars($vid)?>">
            <input type="hidden" name="file_lama" value="<?= htmlspecialchars($vfile) ?>">

            
            <div class="mb-3">
                <label for="no_surat" class="form-label">No Surat <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="no_surat" name="no_surat" value="<?=htmlspecialchars($vno_surat)?>" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_surat" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="<?=htmlspecialchars($vtanggal_surat)?>" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_diterima" class="form-label">Tanggal Diterima</label>
                <input type="date" class="form-control" id="tanggal_diterima" name="tanggal_diterima" value="<?=htmlspecialchars($vtanggal_diterima)?>">
            </div>

            <div class="mb-3">
                <label for="perihal" class="form-label">Perihal <span class="text-danger">*</span></label>
                <textarea class="form-control" id="perihal" name="perihal" rows="3" required><?=htmlspecialchars($vperihal)?></textarea>
            </div>

            <div class="mb-3">
                <label for="id_departemen" class="form-label">Departemen <span class="text-danger">*</span></label>
                <select class="form-control" id="id_departemen" name="id_departemen" required>
                    <option value="">-- Pilih Departemen --</option>
                    <?php
                    $dept = mysqli_query($koneksi, "SELECT id_departemen, nama_departemen FROM departemen ORDER BY nama_departemen ASC");
                    while($row = mysqli_fetch_array($dept)):
                    ?>
                    <option value="<?=$row['id_departemen']?>" <?=$vid_departemen == $row['id_departemen'] ? 'selected' : ''?>>
                        <?=htmlspecialchars($row['nama_departemen'])?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_pengirim" class="form-label">Pengirim Surat <span class="text-danger">*</span></label>
                <select class="form-control" id="id_pengirim" name="id_pengirim" required>
                    <option value="">-- Pilih Pengirim --</option>
                    <?php
                    $pengirim = mysqli_query($koneksi, "SELECT id_pengirim_surat, nama_pengirim FROM pengirim_surat ORDER BY nama_pengirim ASC");
                    while($row = mysqli_fetch_array($pengirim)):
                    ?>
                    <option value="<?=$row['id_pengirim_surat']?>" <?=$vid_pengirim == $row['id_pengirim_surat'] ? 'selected' : ''?>>
                        <?=htmlspecialchars($row['nama_pengirim'])?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label">File (PDF, DOC, DOCX, XLSX, JPG, PNG - Max 5MB)</label>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.xlsx,.jpg,.jpeg,.png">
                <?php if($vfile !== ''): ?>
                    <small class="text-muted d-block mt-2">
                        📎 File saat ini: <a href="file/<?=htmlspecialchars($vfile)?>" target="_blank"><?=htmlspecialchars($vfile)?></a>
                    </small>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2" style="width: fit-content;">
                <button type="submit" name="bsimpan" class="btn btn-pkt">Simpan</button>
                <button type="button" name="bbatal" class="btn btn-danger" onclick="resetForm()">Batal</button>
            </div>
            <input type="hidden" name="file_lama" value="<?= htmlspecialchars($vfile) ?>">

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function resetForm() {
    document.location='?halaman=arsip_surat';
}
</script>