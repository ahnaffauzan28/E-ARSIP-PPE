<?php
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : '';
session_start();

if (empty($_SESSION['id_user']) || empty($_SESSION['username'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location='index.php';</script>";
    exit;
}

// CEK ROLE
if ($_SESSION['role'] !== 'karyawan') {
    echo "<script>alert('Akses ditolak! Anda bukan karyawan'); window.location='admin.php';</script>";
    exit;
}
?>

<?php include "config/koneksi.php"; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>E-Arsip | PPE</title>

  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/custom.css" rel="stylesheet">

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

    html, body {
      height: 100%;
      overflow: hidden;
    }
    .main-content {
      height: calc(100vh - 70px - 50px); 
      overflow-y: auto;
      padding: 20px;
    }


  </style>

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-pkt sticky-top shadow-sm" style="height:70px;">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="?">

    <span class="brand-title">
        E-ARSIP
        <small class="text-muted d-block">PPE</small>
    </span>
</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="nav-link text-white" href="#">
            Data Arsip Dokumen
        </a>
    </li>
</ul>

      <div class="dropdown">
        <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
          <div class="avatar">PK</div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
         
          <li>
            <a class="dropdown-item text-danger" href="#" onclick="return konfirmasiLogout()">Logout</a>
          </li>

        </ul>
      </div>
    </div>
  </div>
</nav>

<script>
function konfirmasiLogout() {
    if (confirm("Apakah Anda yakin ingin logout?")) {
        window.location.href = "logout.php";
    }
    return false;
}
</script>

<!-- MAIN CONTENT SCROLL ONLY -->
<div class="main-content">

  <div class="card card-pkt mt-3 mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Arsip Dokumen</h5>

        <div class="search-wrapper">
            <input type="text" class="form-control search-input"
                   id="searchInput" placeholder="🔍 Cari arsip..."
                   onkeyup="filterTable()">
        </div>
    </div>

    <div class="card-body">
        <div class="table-wrapper">
            <!-- TABLE TETAP -->
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="table-head-sticky">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">No Surat</th>
                        <th class="text-center">Tanggal Surat</th>
                        <th class="text-center">Perihal</th>
                        <th class="text-center">Departemen</th>
                        <th class="text-center">Pengirim</th>
                        <th class="text-center">File</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $tampil = mysqli_query($koneksi, 
                    "SELECT 
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
ORDER BY a.id_arsip DESC"
                );

                $no = 1;
                if(mysqli_num_rows($tampil) > 0) {
                    while ($data = mysqli_fetch_array($tampil)):
                        $file_path = 'file/' . htmlspecialchars($data['file']);
                        $file_ext = strtolower(pathinfo($data['file'], PATHINFO_EXTENSION));
                ?>
                <tr class="dataRow">
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="searchable text-center"><?= htmlspecialchars($data['no_surat']) ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($data['tanggal_surat'])) ?></td>
                    <td class="text-center"><?= htmlspecialchars($data['perihal']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($data['nama_departemen']) ?></td>
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
                        </tr>
                    <?php endwhile; 
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>

</div>

<!-- FOOTER FIXED -->
<<footer class="footer py-3 text-center"
        style="background-color:#0054A6; height:50px; position:fixed; bottom:0; width:100%;">
    <p class="text-white mb-0">&copy; 2025-<?=date('Y')?> Mahnafz with "NgodingPintar"</p>
</footer>
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
        const searchableText = row.querySelector('.searchable').textContent.toLowerCase();
        
        if (searchableText.includes(input)) {
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

<script src="assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>