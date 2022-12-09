Laravel 8, datatables, jquery, ajax, mysql, custom middleware role

Role :
- Owner
- Kasir
- Gudang

Beberapa FItur :
- Manajemen User
- Manajemen Produk
- Manajemen Satuan
- Manajemen Kategori
- Manajemen Produk
- Manajemen Supplier
- Manajemen Pelanggan
- Manajemen Stok
- Manajemen Pembelian
- Manajemen Barang Keluar
- Manajemen Penjualan
- Manajemen Pengiriman
- Manajemen Laporan Pendapatan
- Manajemen Laporan Lainya
- Manajemen Setting
- Manajemen Profil


Penggunaan Aplikasi
1. clone project aplikasi yang berada pada folder Project Tugas Akhir <br/>
2. copy file .env.example dan paste lalu ubah nama file .env.example menjadi .env <br/>
![image](https://user-images.githubusercontent.com/72261786/191931570-8ddc24a1-6e54-4a4a-a879-878918b49dd6.png) <br/><br/>


3. sesuaikan setting .env dengan setting database masing2 komputer <br/>
![image](https://user-images.githubusercontent.com/72261786/191931672-cafaff8d-179d-489c-b808-b4068ceed804.png) <br/><br/>


4. Buat database baru dengan nama yang menyesuaikan dengan setting yang ada di .env <br/>
![image](https://user-images.githubusercontent.com/72261786/191931980-89b7509e-3ae1-4115-b6f5-5d126e7cc536.png) <br/><br/>


5. Hapus folder vendor dan composer.lock jika ada <br/>
![image](https://user-images.githubusercontent.com/72261786/191932137-fbee3abe-a88b-4c0e-92e5-906eedcb9eb8.png) <br/><br/>


6. masukan perintah composer install <br/>
7. masukan perintah composer update --no-scripts <br/>
8. masukan perintah php artisan key:generate <br/>
9. masukan perintah composer dump-autoload <br/>
10. masukan perintah php artisan migrate <br/>
11. masukan perintah php artisan db:seed <br/>
12. Kemudian run aplikasi dengan perintah php artisan serve dan pada browser buka dengan url http://127.0.0.1:8000/ <br/>
13. login dengan akun admin yaitu username ridho dan passsword 12345678 <br/>
14. user dapat login dengan role lain, login dengan akun admin, lalu buat akun dengan role kasir atau gudang pada menu user dan login dengan akun tersebut <br/>
![image](https://user-images.githubusercontent.com/72261786/191933123-41a2aa67-465f-45a9-a791-d8f0ff434e2f.png) <br/>

