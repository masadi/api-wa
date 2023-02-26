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
                        'deskripsi' => '- Matikan services eRaporSMKDB'."\n".'- copy folder eRaporSMK di drive C, amankan di drive lain'."\n".'- Uninstall e-Rapor SMK versi 5'."\n".'- Restart Komputer/Laptop'."\n".'- Install eRapor SMK versi 6'."\n".'- Matikan services eRaporSMKDB'."\n".'- Hapus folder '."\n".'database di folder C:\eRaporSMK'."\n".'- Copy folder database hasil backup di atas dan paste di folder C:\eRaporSMK'."\n".'- Jalankan services eRaporSMKDB'."\n".'- Buka folder C:\eRaporSMK\updater'."\n".'- Klik kanan file update-erapor.bat dan pilih Run as Administrator'."\n".'- Klik kanan file symlink.bat dan pilih Run as Administrator'."\n".'- Selesai',
                    ],
                    [
                        'judul' => 'Services eRaporSMKDB tidak bisa running',
                        'deskripsi' => '- Masuk ke folder C:\eRaporSMK\webserver\bin, cari file pg_ctl.bat'."\n".'- Klik kanan file tersebut dan klik Run as Administrator',
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
                        'deskripsi' => '- Silahkan ketik ulang git config --global --add safe.directory C:/eRaporSMK/dataweb [enter]'."\n".'- Kemudian ketik lagi git pull [enter]',
                    ],
                    [
                        'judul' => 'git pull gagal (part 2)',
                        'deskripsi' => '- Silahkan ketik git stash [enter]'."\n".'- Kemudian ketik lagi git pull [enter]',
                    ],
                    [
                        'judul' => 'git pull gagal (part 3)',
                        'deskripsi' => '- Silahkan ketik git clean -df [enter]'."\n".'- Kemudian ketik lagi git pull [enter]',
                    ],
                    [
                        'judul' => 'git pull gagal (part 4)',
                        'deskripsi' => '- Silahkan download dulu aplikasi git melalui disini'."\n".'- Tutup CMD nya kemudian buka kembali dan ulangi dari awal',
                    ],
                    [
                        'judul' => 'composer update gagal',
                        'deskripsi' => '- Silahkan download aplikasi composer disini'."\n".'- Tutup CMD nya kemudian buka kembali dan ulangi dari awal',
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
