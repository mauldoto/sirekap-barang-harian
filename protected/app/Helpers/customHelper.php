<?php

if (!function_exists('generateReference')) {
    function generateReference($prefix) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 4;

        switch ($prefix) {
            case 'B':
                $kode = App\Models\Barang::pluck('kode')->toArray();

                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;

            case 'K':
                $kode = App\Models\Karyawan::pluck('kode')->toArray();
                
                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;

            case 'L':
                $kode = App\Models\Lokasi::pluck('kode')->toArray();
                
                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;

            case 'SL':
                $kode = App\Models\SubLokasi::pluck('kode')->toArray();
                
                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;

            case 'SM':
                $kode = App\Models\Stok::where('type', 'masuk')->pluck('no_referensi')->toArray();
                
                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;

            case 'SK':
                $kode = App\Models\Stok::where('type', 'keluar')->pluck('no_referensi')->toArray();
                
                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;

            case 'JOB':
                $kode = App\Models\Aktivitas::pluck('no_referensi')->toArray();
                
                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;

            case 'AKM':
                $kode = App\Models\Akomodasi::pluck('no_referensi')->toArray();
                
                do {
                    $code = $prefix . '-' . date('y');
                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[rand(0, strlen($characters) - 1)];
                    }
                } while (in_array($code, $kode));
                
                return $code;
                break;
            
            default:
                $code = $prefix . '-' . date('y');
                for ($i = 0; $i < $length; $i++) {
                    $code .= $characters[rand(0, strlen($characters) - 1)];
                }
                return $code;
                break;
        }        
    }
}