<!doctype html>
<html lang="id">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rekap Nilai Mahasiswa</title>

    <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
    <script>
      (() => {
        'use strict';
        const root = document.documentElement;

        // Applications with their own theming opt out of AdminLTE's color mode
        // entirely, here as well as in the bundle.
        if (root.getAttribute('data-lte-color-mode') === 'off') {
          return;
        }

        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
          stored = localStorage.getItem(STORAGE_KEY);
        } catch {
          // localStorage may be unavailable (private mode, sandboxed iframe).
        }
        // Mirror the precedence in color-mode.ts: the visitor's stored choice
        // wins, then a theme this page declared itself, then the OS preference.
        const authored = root.getAttribute('data-bs-theme');
        let resolved = 'light';
        if (stored === 'dark' || stored === 'light') {
          resolved = stored;
        } else if (authored === 'dark' || authored === 'light') {
          resolved = authored;
        } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
          resolved = 'dark';
        }
        root.setAttribute('data-bs-theme', resolved);
        root.style.colorScheme = resolved;
        // Flag values computed here, so the bundle does not mistake them for a
        // theme the page declared and stop following the OS preference.
        if (resolved !== authored) {
          root.setAttribute('data-lte-theme-resolved', '');
        }
      })();
    </script>
    <!--end::Theme Init-->

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE 4 | Users" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="./css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="./css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
  
      <!--end::Header-->
      <!--begin::Connection-->
      <?php
        require_once __DIR__ . "/koneksi.php"; 

        $filter = isset($_GET['predikat']) ? $_GET['predikat'] : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $sort_nilai = isset($_GET['sort_nilai']) ? $_GET['sort_nilai'] : '';
        $sort_nama = isset($_GET['sort_nama']) ? $_GET['sort_nama'] : '';
        $sort_nim = isset($_GET['sort_nim']) ? $_GET['sort_nim'] : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        //pilihan jumlah record per halaman (whitelist), default 10
        $limit_options = [5, 10, 15];
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        if (!in_array($limit, $limit_options, true)) {
          $limit = 10;
        }

        //kondisi filter predikat dan search nama
        $where = " WHERE 1=1";
        if ($filter !== 'all' && !empty($filter)) {
          $where .= " AND predikat = '$filter'";
        }
        if ($search !== '') {
          $keyword = mysqli_real_escape_string($conn, $search);
          $where .= " AND namalengkap LIKE '%$keyword%'";
        }

        //hitung total data untuk pagination
        $hitung = mysqli_query($conn, "SELECT COUNT(*) AS total FROM nilaimahasiswa" . $where);
        $total_data = mysqli_fetch_assoc($hitung)['total'];
        $total_page = max(1, ceil($total_data / $limit));
        if ($page > $total_page) {
          $page = $total_page;
        }
        $offset = ($page - 1) * $limit;
        $dari = ($total_data > 0) ? $offset + 1 : 0;
        $sampai = min($offset + $limit, $total_data);

        //query dasar
        $sql = "SELECT * FROM nilaimahasiswa" . $where;
        //sorting
        if ($sort_nama == 'asc') {
          $sql .= " ORDER BY namalengkap ASC";
        } elseif ($sort_nama == 'desc') {
          $sql .= " ORDER BY namalengkap DESC";
        } elseif ($sort_nilai == 'asc') {
          $sql .= " ORDER BY nilaiakhir ASC";
        } elseif ($sort_nilai == 'desc') {
          $sql .= " ORDER BY nilaiakhir DESC";
        } elseif ($sort_nim == 'asc') {
          $sql .= " ORDER BY nim ASC";
        } elseif ($sort_nim == 'desc') {
          $sql .= " ORDER BY nim DESC";
        }
        //pagination
        $sql .= " LIMIT $limit OFFSET $offset";

        $result = mysqli_query($conn, $sql);

        $params = $_GET;
        unset($params['page']);
        $params['limit'] = $limit; 
        $query = http_build_query($params);

        //helper: buat URL ke halaman tertentu dengan semua parameter lain tetap terjaga
        function pageUrl($params, $p) {
          return '?' . http_build_query(array_merge($params, ['page' => $p]));
        }
        ?>
      <!--end::Connection-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6">
                <h1 class="mb-0 fs-3">Rekap Nilai Mahasiswa</h1>
              </div>
              <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nilai</li>
                  </ol>
                </nav>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-12">
                <!--begin::Card-->
                <div class="card mb-4">
                  <!--begin::Card Header-->
                  <div class="card-header">
                    <div class="row g-2 align-items-center">
                      <div class="col-12 col-md-4">
                        <h3 class="card-title">Direktori Nilai</h3>
                      </div>
                      <div class="col-12 col-md-8">
                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                          <div class="input-group input-group-sm w-auto">
                            <span class="input-group-text">
                              <i class="bi bi-search" aria-hidden="true"></i>
                            </span>
                            <input
                              type="search"
                              id="user-search"
                              class="form-control"
                              placeholder="Cari Berdasarkan Nama"
                              aria-label="Cari Data"
                              value="<?php echo $search; ?>"
                              style="width: 190px"
                            />
                          </div>
                          <select
                            id="user-role-filter"
                            class="form-select form-select-sm w-auto"
                            aria-label="Filter Predikat"
                          >
                            <option value="all" <?php if ($filter == 'all') echo 'selected'; ?>>Semua Predikat</option>
                            <option value="sangat memuaskan" <?php if ($filter == 'sangat memuaskan') echo 'selected'; ?>>Sangat Memuaskan</option>
                            <option value="memuaskan" <?php if ($filter == 'memuaskan') echo 'selected'; ?>>Memuaskan</option>
                            <option value="cukup memuaskan" <?php if ($filter == 'cukup memuaskan') echo 'selected'; ?>>Cukup Memuaskan</option>
                            <option value="kurang memuaskan" <?php if ($filter == 'kurang memuaskan') echo 'selected'; ?>>Kurang Memuaskan</option>
                            <option value="tidak lulus" <?php if ($filter == 'tidak lulus') echo 'selected'; ?>>Tidak Lulus</option>

                          </select>
                          <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-add-user"
                          >
                            <i class="bi bi-person-plus-fill me-1" aria-hidden="true"> </i>
                            Tambah Data Nilai
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--end::Card Header-->

                  <!--begin::Card Body-->
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-hover align-middle m-0">
                        <thead>
                          <tr>
                            <th>
                              <?php
                              $next_sort = ($sort_nim == 'asc') ? 'desc' : 'asc';
                              if ($sort_nim == 'asc') {
                                $icon_class = "bi-chevron-up";
                              } elseif ($sort_nim == 'desc') {
                                $icon_class = "bi-chevron-down";
                              } else {
                                $icon_class = "bi-chevron-expand";
                              }
                              ?>
                              <button type="button" id="btnsortnim"
                                class="btn btn-sm text-reset fw-bold p-0 border-0 shadow-none"
                                data-sort="<?php echo $next_sort; ?>">
                                NIM
                                <i class="bi <?php echo $icon_class; ?> ms-1"></i>
                              </button>
                            </th>
                            <th>
                              <?php
                              $next_sort = ($sort_nama == 'asc') ? 'desc' : 'asc';
                              if ($sort_nama == 'asc') {
                                $icon_class = "bi-chevron-up";
                              } elseif ($sort_nama == 'desc') {
                                $icon_class = "bi-chevron-down";
                              } else {
                                $icon_class = "bi-chevron-expand";
                              }
                              ?>
                              <button type="button" id="btnsortnama"
                                class="btn btn-sm text-reset fw-bold p-0 border-0 shadow-none"
                                data-sort="<?php echo $next_sort; ?>">
                                Nama Lengkap
                                <i class="bi <?php echo $icon_class; ?> ms-1"></i>
                              </button>
                            </th>
                            <th>
                              <?php
                              $next_sort = ($sort_nilai == 'asc') ? 'desc' : 'asc';
                              if ($sort_nilai == 'asc') {
                                $icon_class = "bi-chevron-up";
                              } elseif ($sort_nilai == 'desc') {
                                $icon_class = "bi-chevron-down";
                              } else {
                                $icon_class = "bi-chevron-expand";
                              }
                              ?>
                              <button type="button" id="btnsortnilai"
                                class="btn btn-sm text-reset fw-bold p-0 border-0 shadow-none"
                                data-sort="<?php echo $next_sort; ?>">
                                Nilai Akhir
                                <i class="bi <?php echo $icon_class; ?> ms-1"></i>
                              </button>
                            </th>
                            <th>Nilai Huruf</th>
                            <th>Predikat</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tanggal Diubah</th>
                            <th class="text-end">Aksi</th>
                          </tr>
                        </thead>

                        <?php if (mysqli_num_rows($result) == 0) { ?>
                        <tbody>
                          <tr>
                            <td colspan="8" class="text-center text-body-secondary py-4">Data tidak ditemukan.</td>
                          </tr>
                        </tbody>
                        <?php } ?>

                        <?php
                          while($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tbody>
                          <tr>
                            <td>
                              <div class="d-flex align-items-center">                        
                                <span class="fw-medium"><?php echo $row['nim']; ?> </span>
                              </div>
                            </td>
                            <td>
                              <div class="d-flex align-items-center">
                                <?php
                                $path_foto = !empty($row['foto']) ? "img/" . $row['foto'] : "./img/defaultphoto.jpg";
                                ?>
                                <img src="<?php echo $path_foto; ?>"
                                alt="Foto Mahasiswa"
                                class="img-size-32 rounded-circle me-2"
                                style="object-fit: cover;"
                                />
                              <span class="fw-medium"><?php echo $row['namalengkap']; ?></span>
                              </div>
                            </td>
                            <td>
                              <span class="fw-medium"> <?php echo $row['nilaiakhir']; ?> </span>
                            </td>
                            <td>
                              <span class="fw-medium"><?php echo $row['nilaihuruf']; ?></span>
                            </td>
                            <td>
                              <span class="fw-medium"><?php echo $row['predikat']; ?></span>
                            </td>
                            <td>
                              <span class="fw-medium"><?php echo $row['entry_date']; ?></span>
                            </td>
                            <td>
                              <span class="fw-medium"><?php echo $row['modified_at']; ?></span>
                            </td>
                            <td class="text-end">
                              <div class="btn-group btn-group-sm">
                                <button
                                  type="button"
                                  class="btn btn-outline-secondary"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modal-edit-user"
                                  data-nim="<?php echo $row['nim']; ?>"
                                  data-nama="<?php echo $row['namalengkap']; ?>"
                                  data-nilai="<?php echo $row['nilaiakhir']; ?>"
                                  aria-label="Edit Data"
                                >
                                  <i class="bi bi-pencil" aria-hidden="true"> </i>
                                </button>
                                <button
                                  type="button"
                                  class="btn btn-outline-danger"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modal-delete-user"
                                  data-nim="<?php echo $row['nim']; ?>"
                                  aria-label="Hapus Nilai Ini"
                                >
                                  <i class="bi bi-trash" aria-hidden="true"> </i>
                                </button>
                              </div>
                            </td>
                          </tr>

                        </tbody>
                        <?php } ?>
                      </table>
                    </div>
                    <!-- /.table-responsive -->
                     <?php                        
                     mysqli_close($conn);
                     ?>
                  </div>
                  <!--end::Card Body-->
                  <!--begin::Card Footer-->
                  <div class="card-footer">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                      <!-- Dropdown jumlah record per halaman + info data -->
                      <div class="d-flex flex-wrap align-items-center gap-2 fs-7 text-body-secondary">
                        <label for="limit-select" class="mb-0">Tampilkan</label>
                        <select
                          id="limit-select"
                          class="form-select form-select-sm w-auto"
                          aria-label="Jumlah data per halaman"
                        >
                          <?php foreach ($limit_options as $opt) { ?>
                          <option value="<?php echo $opt; ?>" <?php if ($limit == $opt) echo 'selected'; ?>><?php echo $opt; ?></option>
                          <?php } ?>
                        </select>
                        <span>data per halaman</span>
                        <span class="ms-md-2">
                          | Menampilkan <?php echo $dari; ?> sampai <?php echo $sampai; ?> dari <?php echo $total_data; ?> data
                        </span>
                      </div>

                      <!-- Tombol navigasi -->
                      <ul class="pagination pagination-sm m-0">
                        <!-- First Page -->
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                          <a class="page-link" href="<?php echo pageUrl($params, 1); ?>" aria-label="Halaman Pertama">
                            <i class="bi bi-chevron-double-left" aria-hidden="true"></i> First
                          </a>
                        </li>
                        <!-- Previous -->
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                          <a class="page-link" href="<?php echo pageUrl($params, max(1, $page - 1)); ?>" aria-label="Sebelumnya">
                            <i class="bi bi-chevron-left" aria-hidden="true"></i> Previous
                          </a>
                        </li>

                        <!-- nomor halaman -->
                        <?php
                        $start = max(1, $page - 2);
                        $end   = min($total_page, $page + 2);
                        for ($i = $start; $i <= $end; $i++) { ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                          <a class="page-link" href="<?php echo pageUrl($params, $i); ?>"><?php echo $i; ?></a>
                        </li>
                        <?php } ?>

                        <!-- Next -->
                        <li class="page-item <?php if ($page >= $total_page) echo 'disabled'; ?>">
                          <a class="page-link" href="<?php echo pageUrl($params, min($total_page, $page + 1)); ?>" aria-label="Berikutnya">
                            Next <i class="bi bi-chevron-right" aria-hidden="true"></i>
                          </a>
                        </li>
                        <!-- Last Page -->
                        <li class="page-item <?php if ($page >= $total_page) echo 'disabled'; ?>">
                          <a class="page-link" href="<?php echo pageUrl($params, $total_page); ?>" aria-label="Halaman Terakhir">
                            Last <i class="bi bi-chevron-double-right" aria-hidden="true"></i>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <!--end::Card Footer-->
                </div>
                <!--end::Card-->
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->

            <!--begin::Add User Modal-->
            <div
              class="modal fade"
              id="modal-add-user"
              tabindex="-1"
              aria-labelledby="modal-add-user-label"
              aria-hidden="true"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action='inputnilai.php' method='post' enctype='multipart/form-data'>
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal-add-user-label">Tambah Data Nilai</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                      ></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="add-nim" class="form-label"> NIM </label>
                        <input
                          type="text" minlength=10 maxlength=10
                          class="form-control"
                          id="add-nim"
                          name="nim"
                          placeholder="contoh: 3202416080"
                          required
                        />
                      </div>
                      <div class="mb-3">
                        <label for="add-namalengkap" class="form-label"> Nama Lengkap </label>
                        <input
                          type="text"
                          class="form-control"
                          id="add-namalengkap"
                          name="namalengkap"
                          placeholder="Masukkan Nama Lengkap"
                          required
                        />
                        <div class="form-text">Masukkan Nama Lengkap Sesuai KTP</div>
                      </div>
                      <div class="mb-3">
                        <label for="add-nilaiakhir" class="form-label"> Nilai Akhir </label>
                        <input
                          type="number"
                          class="form-control"
                          id="add-nilaiakhir"
                          name="nilaiakhir"
                          placeholder="Masukkan Nilai Akhir"
                          min=0 max=100 step=0.01
                          required
                        />
                      </div>
                      <div class="mb-3">
                        <label for="add-foto" class="form-label">Foto Mahasiswa</label>
                        <input 
                          type="file" 
                          class="form-control" 
                          id="add-foto"
                          name="foto"
                          accept="image/*"
                        />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                      </button>
                      <button type="submit" class="btn btn-primary">Tambah Data</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!--end::Add User Modal-->

            <!--begin::Edit User Modal-->
            <div
              class="modal fade"
              id="modal-edit-user"
              tabindex="-1"
              aria-labelledby="modal-edit-user-label"
              aria-hidden="true"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action='editnilai.php' method='post' enctype='multipart/form-data'>
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal-edit-user-label">Edit Data Nilai</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                      ></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="edit-nim" class="form-label"> NIM </label>
                        <input
                          type="text" minlength=10 maxlength=10
                          class="form-control"
                          id="edit-nim"
                          name="nim"
                          value=""
                          readonly
                        />
                      </div>
                      <div class="mb-3">
                        <label for="edit-namalengkap" class="form-label"> Nama Lengkap </label>
                        <input
                          type="text"
                          class="form-control"
                          id="edit-namalengkap"
                          name="namalengkap"
                          value=""
                          required
                        />
                        <div class="form-text">Masukkan Nama Lengkap Sesuai KTP</div>
                      </div>
                      <div class="mb-3">
                        <label for="edit-nilaiakhir" class="form-label"> Nilai Akhir </label>
                        <input
                          type="number"
                          class="form-control"
                          id="edit-nilaiakhir"
                          name="nilaiakhir"
                          value=""
                          min=0 max=100 step=0.01
                          required
                        />
                      </div>
                      <div class="mb-3">
                        <label for="edit-foto" class="form-label"> Ubah Foto (Opsional) </label>
                        <input 
                          type="file" 
                          class="form-control" 
                          id="edit-foto" 
                          name="foto" 
                          accept="image/*" 
                        />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                      </button>
                      <button type="submit" class="btn btn-primary">Edit Data</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!--end::Edit User Modal-->
  
            <!--begin::Delete User Modal-->
            <div
              class="modal fade"
              id="modal-delete-user"
              tabindex="-1"
              aria-labelledby="modal-delete-user-label"
              aria-hidden="true"
            >
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="hapusnilai.php" method="post">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal-delete-user-label">Hapus Data</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                      ></button>
                    </div>
                    <div class="modal-body">
                      <p class="mb-0">
                        Apakah Anda Yakin Ingin Menghapus Data Ini?
                      </p>
                      <!-- Input hidden untuk menampung NIM -->
                      <input type="hidden" name="nim" id="delete-nilai-nim">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                      </button>
                      <button type="submit" class="btn btn-danger">
                        Hapus Data
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!--end::Delete User Modal-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">Web Programming Semester 5</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2026&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">Muhammad Refan</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="./js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->

    <script>
    document.addEventListener('DOMContentLoaded', function () {

      //modal hapus
      const deleteModal = document.getElementById('modal-delete-user');
      if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          const nim = button.getAttribute('data-nim');
          deleteModal.querySelector('#delete-nilai-nim').value = nim;
        });
      }

      //modal edit
      const editModal = document.getElementById('modal-edit-user');
      if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          editModal.querySelector('#edit-nim').value = button.getAttribute('data-nim');
          editModal.querySelector('#edit-namalengkap').value = button.getAttribute('data-nama');
          editModal.querySelector('#edit-nilaiakhir').value = button.getAttribute('data-nilai');
        });
      }

      //filter predikat
      const filterDropdown = document.getElementById('user-role-filter');
      if (filterDropdown) {
        filterDropdown.addEventListener('change', function () {
          const urlParams = new URLSearchParams(window.location.search);
          urlParams.set('predikat', this.value);
          urlParams.delete('page'); // kembali ke halaman 1
          window.location.href = '?' + urlParams.toString();
        });
      }

      //limit data per halaman
      const limitSelect = document.getElementById('limit-select');
      if (limitSelect) {
        limitSelect.addEventListener('change', function () {
          const urlParams = new URLSearchParams(window.location.search);
          urlParams.set('limit', this.value);
          urlParams.delete('page'); // kembali ke halaman 1
          window.location.href = '?' + urlParams.toString();
        });
      }

      //search nama
      const searchInput = document.getElementById('user-search');
      if (searchInput) {
        searchInput.addEventListener('keydown', function (event) {
          if (event.key === 'Enter') {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('search', this.value.trim());
            urlParams.delete('page');
            window.location.href = '?' + urlParams.toString();
          }
        });
      }

      //sorting
      ['nim', 'nama', 'nilai'].forEach(function (kolom) {
        const btn = document.getElementById('btnsort' + kolom);
        if (btn) {
          btn.addEventListener('click', function () {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.delete('sort_nim');
            urlParams.delete('sort_nama');
            urlParams.delete('sort_nilai');
            urlParams.set('sort_' + kolom, this.getAttribute('data-sort'));
            urlParams.delete('page');
            window.location.href = '?' + urlParams.toString();
          });
        }
      });

    });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!--begin::Color Mode Toggle-->
    <!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
     module (since 4.1) — no page script needed. Only the no-flash snippet
     in <head> stays inline, because it must run before first paint. -->
    <!--end::Color Mode Toggle-->

    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
