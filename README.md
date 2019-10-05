# Simple REST API using Slim Framework 3

Sebuah Rest API sederhana yang dibangun dari Slim 3.

## Installation

1 . Buat terlebih dahulu sebuah database dan di dalamnya terdapat tabel `news`.

```
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
```

2. Atur koneksi database di file _settings.php.example_ kemudian save as menjadi _settings.php_.

3. Jalankan perintah `composer install` di cmd / terminal. Perintah ini untuk mendapatkan library2 yang dibutuhkan, sesuai yang tercantum di file _composer.json_ .

4. Setelah itu coba testing dengan menjalankan `composer start` di cmd / terminal.

5. Silahkan ikuti dokumentasi postman ini https://documenter.getpostman.com/view/2550975/RWguxHGP untuk memudahkan testing.
