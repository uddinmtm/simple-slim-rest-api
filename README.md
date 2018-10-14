# Simple REST API using Slim Framework 3

Sebuah Rest API sederhana yang dibangun dari Slim 3.

## Installation

Sebelumnya, untuk mencoba menginstall ini, buatlah terlebih dahulu sebuah database dan di dalamnya terdapat tabel `news`.

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