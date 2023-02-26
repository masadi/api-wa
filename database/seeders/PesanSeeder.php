<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Pesan;

class PesanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = [
            [
                'judul' => 'Database',
                'deskripsi' => 'Pertanyaan seputar pengelolaan database',
                'childs' => [],
                'pesan' => [
                    [
                        'judul' => 'Cara menggunakan database versi 5.x di versi 6.x',
                        'deskripsi' => '- Matikan services eRaporSMKDB
                        - copy folder eRaporSMK di drive C, amankan di drive lain
                        - Uninstall e-Rapor SMK versi 5
                        - Restart Komputer/Laptop
                        - Install eRapor SMK versi 6
                        - Matikan services eRaporSMKDB
                        - Hapus folder database di folder C:\eRaporSMK
                        - Copy folder database hasil backup di atas dan paste di folder C:\eRaporSMK
                        - Jalankan services eRaporSMKDB
                        - Buka folder C:\eRaporSMK\updater
                        - Klik kanan file update-erapor.bat dan pilih Run as Administrator
                        - Klik kanan file symlink.bat dan pilih Run as Administrator
                        - Selesai',
                    ],
                    [
                        'judul' => 'Services eRaporSMKDB tidak bisa running',
                        'deskripsi' => '- Masuk ke folder C:\eRaporSMK\webserver\bin, cari file pg_ctl.bat
                        - Klik kanan file tersebut dan klik Run as Administrator',
                    ]
                ]
            ],
            [
                'judul' => 'Update Aplikasi',
                'deskripsi' => 'Permasalahan saat update aplikasi',
                'childs' => [],
                'pesan' => [
                    [
                        'judul' => 'git pull gagal (part 1)',
                        'deskripsi' => '- Silahkan ketik ulang git config --global --add safe.directory C:/eRaporSMK/dataweb [enter]
                        - Kemudian ketik lagi git pull [enter]',
                    ],
                    [
                        'judul' => 'git pull gagal (part 2)',
                        'deskripsi' => '- Silahkan ketik git stash [enter]
                        - Kemudian ketik lagi git pull [enter]',
                    ],
                    [
                        'judul' => 'git pull gagal (part 3)',
                        'deskripsi' => '- Silahkan ketik git clean -df [enter]
                        - Kemudian ketik lagi git pull [enter]',
                    ],
                    [
                        'judul' => 'git pull gagal (part 4)',
                        'deskripsi' => '- Silahkan download dulu aplikasi git melalui disini
                        - Tutup CMD nya kemudian buka kembali dan ulangi dari awal',
                    ],
                    [
                        'judul' => 'composer update gagal',
                        'deskripsi' => '- Silahkan download aplikasi composer disini
                        - Tutup CMD nya kemudian buka kembali dan ulangi dari awal',
                    ]
                ]
            ]
        ];
        foreach($kategori as $k){
            $new = $this->kategori($k);
            if($k['childs']){
                foreach($k['childs'] as $childs){
                    $sub = $this->kategori($childs, $new->id);
                    if($childs['pesan']){
                        foreach($childs['pesan'] as $pesan){
                            $this->pesan($pesan, $sub->id);
                        }
                    }
                }
            }
            foreach($k['pesan'] as $pesan){
                $this->pesan($pesan, $new->id);
            }
        }
    }
    private function kategori($k, $kategori_id = NULL){
        return Kategori::updateOrCreate(
            [
                'judul' => $k['judul'],
            ],
            [
                'deskripsi' => trim($k['deskripsi']),
                'induk' => $kategori_id,
            ]
        );
    }
    private function pesan($pesan, $kategori_id){
        return Pesan::updateOrCreate(
            [
                'judul' => $pesan['judul'],
            ],
            [
                'deskripsi' => trim($pesan['deskripsi']),
                'kategori_id' => $kategori_id,
            ]
        );
    }
}
