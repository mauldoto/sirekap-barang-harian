@yield('css')

<!-- Bootstrap Css -->
<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" id="app-style" rel="stylesheet"
    type="text/css" />
<style>
    /* Gaya untuk Overlay Loading */
    #loading-overlay {
        /* Menentukan posisi di seluruh layar */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* Background semi-transparan (penting!) */
        background-color: rgba(0, 0, 0, 0.5);
        /* Hitam dengan opasitas 50% */
        /* Warna bisa diubah, misalnya putih semi-transparan: rgba(255, 255, 255, 0.7) */

        /* Memastikan overlay berada di atas semua elemen lain */
        z-index: 9999;

        /* Mengatur Loader agar berada di tengah */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Gaya untuk Loader (Contoh: Spinner Sederhana) */
    .loader {
        border: 8px solid #f3f3f3;
        /* Light grey */
        border-top: 8px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 2s linear infinite;
        /* Animasi berputar */
    }

    /* Keyframes untuk Animasi Spin */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Gaya Konten Utama (Opsional, untuk demonstrasi) */
    #main-content {
        padding: 20px;
        text-align: center;
    }
</style>

@stack('page-css')
