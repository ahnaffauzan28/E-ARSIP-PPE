<?php

// init
$vnama_departemen = '';
$vid = '';
$showAlert = false;
$alertType = '';
$alertMsg = '';

// handle GET actions (edit / hapus)
if(isset($_GET['hal'])) {
    $hal = $_GET['hal'];

    if($hal === 'edit' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = mysqli_prepare($koneksi, "SELECT nama_departemen FROM departemen WHERE id_departemen = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $nama_result);
        if(mysqli_stmt_fetch($stmt)) {
            $vnama_departemen = $nama_result;
            $vid = $id;
        }
        mysqli_stmt_close($stmt);
    }

    if($hal === 'hapus' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = mysqli_prepare($koneksi, "DELETE FROM departemen WHERE id_departemen = ?");
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
                    document.location='?halaman=departemen';
                });
            </script>";
            exit;
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Hapus data GAGAL!',
                    confirmButtonColor: '#dc3545'
                }).then(() => {
                    document.location='?halaman=departemen';
                });
            </script>";
            exit;
        }
        
    }
}

// handle POST save (insert or update)
if(isset($_POST['bsimpan'])) {
    $nama = trim($_POST['nama_departemen'] ?? '');
    $id_post = isset($_POST['id_departemen']) ? (int)$_POST['id_departemen'] : 0;

    if($nama === '') {
        $showAlert = true;
        $alertType = 'error';
        $alertMsg = 'Nama departemen tidak boleh kosong!';
    } else {
        if($id_post > 0) {
            // update existing
            $stmt = mysqli_prepare($koneksi, "UPDATE departemen SET nama_departemen = ? WHERE id_departemen = ?");
            mysqli_stmt_bind_param($stmt, "si", $nama, $id_post);
            if(mysqli_stmt_execute($stmt)) {
                $showAlert = true;
                $alertType = 'success';
                $alertMsg = 'Update Data SUKSES!';
                $vnama_departemen = '';
                $vid = '';
            } else {
                $showAlert = true;
                $alertType = 'error';
                $alertMsg = 'Update Data GAGAL!';
            }
            mysqli_stmt_close($stmt);
        } else {
            // insert new
            $stmt = mysqli_prepare($koneksi, "INSERT INTO departemen (nama_departemen) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $nama);
            if(mysqli_stmt_execute($stmt)) {
                $showAlert = true;
                $alertType = 'success';
                $alertMsg = 'Simpan Data SUKSES!';
                $vnama_departemen = '';
                $vid = '';
            } else {
                $showAlert = true;
                $alertType = 'error';
                $alertMsg = 'Simpan Data GAGAL!';
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
        transform: none;
       
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
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 20px;
    }

    .btn-pkt:hover {
        background-color: #218838 !important;
        color: white !important;
    }
</style>

<div class="card card-pkt mt-3">
    <h5 class="card-header">Form Data Departemen</h5>
    <div class="card-body">
        <form method="post">
            <input type="hidden" name="id_departemen" value="<?=htmlspecialchars($vid)?>">
            <div class="mb-3">
                <label for="nama_departemen" class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" value="<?=htmlspecialchars($vnama_departemen)?>" required>
            </div>

            <div class="d-flex gap-2" style="width: fit-content;">
                <button type="submit" name="bsimpan" class="btn btn-pkt">Simpan</button>
                <button type="button" name="bbatal" class="btn btn-danger" onclick="resetForm()">Batal</button>
            </div>
        </form>
    </div>
</div>

<div class="card card-pkt mt-3 mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Departemen</h5>
        <div class="search-wrapper">
            <input type="text" class="form-control search-input" id="searchInput" placeholder="🔍 Cari departemen..." onkeyup="filterTable()">
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table table-bordered table-striped table-hover" id="dataTable">
                <thead class="table-head-sticky">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th class="text-center">Nama Departemen</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $tampil = mysqli_query($koneksi, "SELECT * FROM departemen ORDER BY id_departemen DESC");
                $no = 1;
                if(mysqli_num_rows($tampil) > 0) {
                    while ($data = mysqli_fetch_array($tampil)): ?>
                    <tr class="dataRow">
                        <td class="text-center"><?=$no++?></td>
                        <td class="searchable text-center"><?=htmlspecialchars($data['nama_departemen'])?></td>
                        <td class="text-center">
                            <a href="?halaman=departemen&hal=edit&id=<?=$data['id_departemen']?>" class="btn btn-sm btn-warning me-1">Edit</a>
                            <a href="#" onclick="return confirmDelete('?halaman=departemen&hal=hapus&id=<?=$data['id_departemen']?>')" class="btn btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile;
                } else {
                    echo "<tr><td colspan='3' class='text-center text-muted'>Tidak ada data departemen</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .card-pkt {
        border-left: 6px solid #F37021;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: 0.3s;
    }

    .card-pkt:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        transform: none;
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
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 20px;
    }

    .btn-pkt:hover {
        background-color: #218838 !important;
        color: white !important;
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
            tr.innerHTML = '<td colspan="3" class="text-center text-muted"><i class="bi bi-search"></i> Tidak ada data yang cocok</td>';
            noDataRow.appendChild(tr);
        }
    } else {
        const noDataRow = document.getElementById('noDataMessage');
        if (noDataRow) noDataRow.remove();
    }
}

function resetForm() {
    document.querySelector('form').reset();
    document.location='?halaman=departemen';
}

function confirmDelete(url) {
    Swal.fire({
        title: "Hapus Data?",
        text: "Data yang dihapus tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Tidak"
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