# Langkah-Langkah Menginstal Sistem Kedisiplinan Siswa

Catatan: Kesalahan selama instalasi dapat terjadi karena perbedaan perangkat keras. Jika mengalami masalah yang tidak dijelaskan dalam dokumentasi ini, silakan lihat forum diskusi online.

Penting: Penjelasan tahap ini hanya berlaku untuk instalasi pada sistem operasi Windows. Pastikan sudah menginstal Apache, MySQL (Database), Composer, Node.js, dan NPM. Versi PHP yang berbeda dapat mempengaruhi apakah sistem dapat berjalan atau tidak, jadi pastikan mengikuti versi yang sesuai dengan langkah-langkah berikut.

Langkah 1 (Instalasi Bahasa Pemrograman):
- Pastikan Anda menginstal PHP versi 8.2 dan sesuaikan dengan perangkat keras Anda. Unduh di https://windows.php.net/download#php-8.2.
- Setelah diinstal, ekstrak folder dan pindahkan ke dalam Program Files. Tambahkan path PHP ke dalam Environment Variable. Lihat https://devdojo.com/tnylea/installing-php-on-windows.
- Untuk memastikan PHP terinstal, jalankan `php --version` di Command Prompt/Terminal. Jika versi PHP sesuai, instalasi berhasil.
- Konfigurasi file PHP: Ubah nama file `php.ini-development` menjadi `php.ini` di lokasi "C:\Program Files\php-8.2.6-Win32-vs16-x64\php.ini-development".
- Jalankan `set PHP_INI=C:\Program Files\php-8.2.13-nts-Win32-vs16-x64\php.ini` di terminal. Sesuaikan path dengan lokasi file php.ini. Jalankan php --ini untuk memeriksa konfigurasi.
- Buka file php.ini, kemudian hapus tanda titik koma ; pada baris-baris berikut:
    ```
    extension_dir = "ext"
    extension=curl
    extension=fileinfo
    extension=gd
    extension=mbstring
    extension=openssl
    extension=pdo_mysql
    extension=pdo_sqlite
    extension=sqlite3
    ```

Kemungkinan error:
- Untuk masalah SSL, jalankan `composer config -g --disable-tls true di terminal`.
- Masalah sertifikat SSL PHP dapat diperbaiki dengan merujuk ke forum diskusi, lihat https://stackoverflow.com/questions/28858351/php-ssl-certificate-error-unable-to-get-local-issuer-certificate.

Langkah 2 (Instalasi Awal Proyek):
- Masuk ke folder sistem dengan perintah `cd school-discipline-assessment-system`.
- Install Framework Laravel melalui NPM dengan `composer global require laravel/installer` di terminal.
- Jalankan `composer install` atau `composer update`.
- Install dependensi JavaScript dengan perintah `npm install`.
- Jalankan `npm install --save-dev vite` agar sistem dapat dijalankan.

Langkah 3 (Setup Konfigurasi Awal Sistem):
- Jalankan `cp .env.example .env` di terminal.
- Generate key proyek dengan `php artisan key:generate`.
- Migrasikan database dengan `php artisan migrate`.
- Isi database dengan data awal menggunakan `php artisan db:seed`.

Langkah 4 (Menjalankan Aplikasi):
- Jalankan `npm run dev` di terminal agar sistem dapat diakses melalui web browser. Akses melalui localhost dengan port yang disediakan setelah perintah tersebut.
- Untuk pengguna awal, login ke akun Admin dengan Token: 0000 dan Password: 0000.