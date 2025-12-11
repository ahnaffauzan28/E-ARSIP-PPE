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
$showAlert = false;
$alertType = '';
$alertMsg = '';

// handle GET actions (edit / hapus)
if(isset($_GET['hal'])) {
    $hal = $_GET['hal'];

    // === MODE EDIT ===
    if($hal === 'edit' && isset($_GET['id'])) {
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

    // === MODE HAPUS ===
    if($hal === 'hapus' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = mysqli_prepare($koneksi, "DELETE FROM arsip WHERE id_arsip = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if(mysqli_stmt_execute($stmt)) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Hapus Data',
                    text: 'Data berhasil dihapus!',
                    confirmButtonColor: '#0054A6'
                }).then(() => {
                    document.location='?halaman=arsip_surat';
                });
            </script>";
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Hapus data GAGAL!',
                    confirmButtonColor: '#dc3545'
                }).then(() => {
                    document.location='?halaman=arsip_surat';
                });
            </script>";
        }
        mysqli_stmt_close($stmt);
        exit;
    }
}

// === HANDLE INSERT / UPDATE ===
if(isset($_POST['bsimpan'])) {
    $no_surat = trim($_POST['no_surat'] ?? '');
    $tanggal_surat = trim($_POST['tanggal_surat'] ?? '');
    $tanggal_diterima = trim($_POST['tanggal_diterima'] ?? '');
    $perihal = trim($_POST['perihal'] ?? '');
    $id_departemen = trim($_POST['id_departemen'] ?? '');
    $id_pengirim = trim($_POST['id_pengirim'] ?? '');
    $id_post = isset($_POST['id_arsip']) ? (int)$_POST['id_arsip'] : 0;

    // validasi field wajib
    if($no_surat === '' || $tanggal_surat === '' || $perihal === '' || $id_departemen === '' || $id_pengirim === '') {
        $showAlert = true;
        $alertType = 'error';
        $alertMsg = 'No Surat, Tanggal Surat, Perihal, Departemen, dan Pengirim tidak boleh kosong!';
    } else {
        if($id_post > 0) {
            // UPDATE DATA
            $stmt = mysqli_prepare($koneksi, "UPDATE arsip SET no_surat=?, tanggal_surat=?, tanggal_diterima=?, perihal=?, id_departemen=?, id_pengirim=? WHERE id_arsip=?");
            mysqli_stmt_bind_param($stmt, "sssssii", $no_surat, $tanggal_surat, $tanggal_diterima, $perihal, $id_departemen, $id_pengirim, $id_post);

            if(mysqli_stmt_execute($stmt)) {
                $showAlert = true;
                $alertType = 'success';
                $alertMsg = 'Update data SUKSES!';
                $vno_surat = '';
                $vtanggal_surat = '';
                $vtanggal_diterima = '';
                $vperihal = '';
                $vid_departemen = '';
                $vid_pengirim = '';
                $vfile = '';
                $vid = '';
            } else {
                $showAlert = true;
                $alertType = 'error';
                $alertMsg = 'Update data GAGAL!';
            }
            mysqli_stmt_close($stmt);
        } else {
            // INSERT DATA BARU
            $stmt = mysqli_prepare($koneksi, "INSERT INTO arsip (no_surat, tanggal_surat, tanggal_diterima, perihal, id_departemen, id_pengirim) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssii", $no_surat, $tanggal_surat, $tanggal_diterima, $perihal, $id_departemen, $id_pengirim);

            if(mysqli_stmt_execute($stmt)) {
                $showAlert = true;
                $alertType = 'success';
                $alertMsg = 'Simpan data SUKSES!';
                $vno_surat = '';
                $vtanggal_surat = '';
                $vtanggal_diterima = '';
                $vperihal = '';
                $vid_departemen = '';
                $vid_pengirim = '';
                $vfile = '';
                $vid = '';
            } else {
                $showAlert = true;
                $alertType = 'error';
                $alertMsg = 'Simpan data GAGAL!';
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!-- SHOW ALERT SWEETALERT2 -->
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
        font-size: 14px;
        font-weight: 600;
    }

    .btn-pkt:hover {
        background-color: #218838;
        color: white;
    }
</style>


<div class="card card-pkt mt-3 mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Arsip Surat</h5>
        <div class="search-wrapper">
            <input type="text" class="form-control search-input" id="searchInput" placeholder="🔍 Cari arsip..." onkeyup="filterTable()">
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="?halaman=arsip_form" class="btn btn-pkt">+ Tambahkan Dokumen</a>
        </div>
        <div class="table-wrapper">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="table-head-sticky">
                    <tr>
                        <th width="5%" class="text-center">No.</th>
                        <th class="text-center">No. Surat</th>
                        <th class="text-center">Tanggal Surat</th>
                        <th class="text-center">Perihal</th>
                        <th class="text-center">Departemen</th>
                        <th class="text-center">Pengirim</th>
                        <th class="text-center">File</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $tampil = mysqli_query($koneksi, "SELECT 
    a.id_arsip, 
    a.no_surat, 
    a.tanggal_surat, 
    a.perihal, 
    a.file, 
    d.nama_departemen, 
    CONCAT(p.nama_pengirim, ' / ', p.no_hp) AS pengirim_info
FROM arsip a
LEFT JOIN departemen d ON a.id_departemen = d.id_departemen
LEFT JOIN pengirim_surat p ON a.id_pengirim = p.id_pengirim_surat
ORDER BY a.id_arsip DESC");
                $no = 1;
                if(mysqli_num_rows($tampil) > 0) {
                    while ($data = mysqli_fetch_array($tampil)): 
                        $file_path = 'file/' . htmlspecialchars($data['file']);
                        $file_ext = strtolower(pathinfo($data['file'], PATHINFO_EXTENSION));
                    ?>
                        <tr class="dataRow">
                            <td class="text-center"><?=$no++?></td>
                            <td class="searchable text-center" ><?=htmlspecialchars($data['no_surat'])?></td>
                            <td class="text-center"><?=date('d-m-Y', strtotime($data['tanggal_surat']))?></td>
                           <td class="searchable text-center" >
                                <?= htmlspecialchars(substr($data['perihal'], 0, 50)) ?><?= strlen($data['perihal']) > 50 ? '...' : '' ?>
                            </td>
                            <td class="text-center"><?=htmlspecialchars($data['nama_departemen'])?></td>
                            <td class="text-center"><?= htmlspecialchars($data['pengirim_info']) ?></td>
                            <td class="text-center">
                                <?php if(!empty($data['file']) && file_exists($file_path)): ?>
                                   
                                        <button type="button" class="btn btn-sm btn-info" onclick="previewFile('<?=$file_path?>', '<?=$file_ext?>')">
                                            👁️ Preview
                                        </button>
                                        <a href="<?=$file_path?>" target="_blank" class="btn btn-sm btn-success" download>
                                            📥 Unduh
                                        </a>
                                    
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="?halaman=arsip_form&hal=edit&id=<?=$data['id_arsip']?>" class="btn btn-sm btn-warning me-1">Edit</a>
                                <a href="javascript:void(0);" onclick="return confirmDelete('?halaman=arsip_surat&hal=hapus&id=<?=$data['id_arsip']?>')" class="btn btn-sm btn-danger">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile;
                } else {
                    echo "<tr><td colspan='8' class='text-center text-muted'>Tidak ada data arsip surat</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
        font-size: 14px;
        font-weight: 600;
    }

    .btn-pkt:hover {
        background-color: #218838;
        color: white;
    }

    /* Styling untuk table wrapper dengan scroll */
    .table-wrapper {
        height: 500px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 8px;
    }

    .table-wrapper table {
        margin-bottom: 0;
    }

    /* Header table sticky */
    .table-head-sticky {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
    }

    .table-head-sticky th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    /* Scrollbar styling */
    .table-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: #0054A6;
        border-radius: 10px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #003d7a;
    }

    .search-wrapper {
        position: relative;
        width: 280px;
    }

    .search-input {
        border-radius: 25px !important;
        border: 2px solid #E0E0E0 !important;
        padding: 10px 15px !important;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .search-input:focus {
        border-color: #0054A6 !important;
        box-shadow: 0 4px 15px rgba(0, 84, 166, 0.2) !important;
        outline: none;
        background-color: #f8f9ff;
    }

    .search-input::placeholder {
        color: #999;
        font-style: italic;
    }
</style>

<!-- Modal Preview File -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Content akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#dataTable tbody .dataRow');
    let visibleCount = 0;

    rows.forEach(row => {
        // Ambil semua elemen dengan class "searchable"
        const cols = row.querySelectorAll('.searchable');
        let match = false;

        cols.forEach(col => {
            if (col.textContent.toLowerCase().includes(input)) {
                match = true;
            }
        });

        if (match) {
            row.style.display = '';
            row.style.animation = 'fadeIn 0.3s ease';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });


    // tampilkan pesan jika tidak ada hasil
    if (visibleCount === 0) {
        const noDataRow = document.querySelector('#dataTable tbody');
        if (!document.getElementById('noDataMessage')) {
            const tr = document.createElement('tr');
            tr.id = 'noDataMessage';
            tr.innerHTML = '<td colspan="8" class="text-center text-muted">Tidak ada data yang cocok</td>';
            noDataRow.appendChild(tr);
        }
    } else {
        const noDataRow = document.getElementById('noDataMessage');
        if (noDataRow) noDataRow.remove();
    }
}

function previewFile(filePath, fileExt) {
    const previewContent = document.getElementById('previewContent');
    const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    const pdfExts = ['pdf'];
    const docExts = ['doc', 'docx', 'txt'];
    const xlsExts = ['xlsx', 'xls'];

    if (imageExts.includes(fileExt)) {
        previewContent.innerHTML = `<img src="${filePath}" style="width: 100%; height: auto;" alt="Preview">`;
    } else if (pdfExts.includes(fileExt)) {
        previewContent.innerHTML = `<iframe src="${filePath}" style="width: 100%; height: 600px; border: none;"></iframe>`;
    } else if (docExts.includes(fileExt)) {
        previewContent.innerHTML = `<p class="text-muted text-center"><i class="bi bi-file-text"></i><br>File dokumen tidak dapat di-preview langsung.<br><a href="${filePath}" target="_blank" download class="btn btn-sm btn-primary mt-2">Unduh File</a></p>`;
    } else if (xlsExts.includes(fileExt)) {
        previewContent.innerHTML = `<p class="text-muted text-center"><i class="bi bi-file-spreadsheet"></i><br>File spreadsheet tidak dapat di-preview langsung.<br><a href="${filePath}" target="_blank" download class="btn btn-sm btn-primary mt-2">Unduh File</a></p>`;
    } else {
        previewContent.innerHTML = `<p class="text-muted text-center">Tipe file tidak didukung untuk preview.<br><a href="${filePath}" target="_blank" download class="btn btn-sm btn-primary mt-2">Unduh File</a></p>`;
    }

    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'), {});
    modal.show();
}

function confirmDelete(url) {
    Swal.fire({
        title: 'Hapus Data?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
    return false;
}

// animasi fade-in untuk baris
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>