-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 27 Bulan Mei 2020 pada 16.51
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kambar`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `id_purchase` int(11) NOT NULL,
  `type` enum('accept','cancel') NOT NULL,
  `message` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `notification`
--

INSERT INTO `notification` (`id`, `id_purchase`, `type`, `message`, `timestamp`) VALUES
(7, 17, 'accept', 'Anda telah berhasil membeli 1 buah \"kaos keren\" dengan harga Rp 100.000,00', '2020-05-21 00:15:23'),
(8, 18, 'cancel', 'Anda tidak berhasil membeli 1 buah \"kaos tauhid, kami akan mengembalikan uang anda secepatnya', '2020-05-21 00:57:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(14) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `photo`, `description`) VALUES
(18, 'kaos keren', 100000, '3714830c954b593fce1344d0a6c27841.jpg', 'lorem'),
(19, 'kaos muslim not terorist', 200000, '176effee957620078a7a1e85f9e8bf9e.jpg', 'lorem'),
(20, 'kaos tauhid', 20000, '90bab091afe9a574f6bdc040b1b9db11.jpg', 'lorem');

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase`
--

INSERT INTO `purchase` (`id`, `user_id`, `product_id`, `description`, `quantity`, `timestamp`) VALUES
(17, 9, 18, 'warna merah', 1, '2020-05-21 00:07:19'),
(18, 9, 20, 'warna merah', 1, '2020-05-21 00:16:38'),
(19, 9, 20, 'warna merah', 1, '2020-05-21 00:59:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` int(14) NOT NULL,
  `token` varchar(255) NOT NULL,
  `role` enum('admin','client') NOT NULL DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `balance`, `token`, `role`) VALUES
(9, 'yudono putro utomo', 'yudonoputro@gmail.com', '$2y$10$v8rcmtsvzJ8uRmykABY3X.FeCE.x9D3mtLjOk3YB1xkxJa7QZXrei', 860000, 'pQAYvvPiSrpzuAPEamId!@ICBOtNps%jdbacNHyXGNynGQRGO%%bmHKamD!f%nin', 'client'),
(10, 'Admin', 'admin@gmail.com', '$2y$10$Ter7at5oEPAzFoYERbwuD.EXs2BlAg9rcU8yIwNr6lxqhYw0nY/v2', 20000, 'trEIJOKZMjrHDqGygaCug!ufZVWJkByhRIYLfjd@psZuMj@fdrSm!OB#HzvhKmat', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
