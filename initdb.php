<?php
require_once ("enumToInt.php");
require_once ("mysqlConnection.php");
$queries = array();

array_push($queries, "
DROP DATABASE library;
");
array_push($queries, "
CREATE DATABASE library;
");
array_push($queries, "
USE library;
");
array_push($queries, "
DROP TABLE bookmarks;
");
array_push($queries, "
DROP TABLE users;
");
array_push($queries, "
DROP TABLE books;
");


array_push($queries, "
CREATE TABLE `bookmarks` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `book_id` int(11) UNSIGNED NOT NULL,
  `location` varchar(64) NOT NULL,
  `ts` bigint UNSIGNED
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
array_push($queries, "
CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `isbn` varchar(17) NOT NULL,
  `path` text NOT NULL,
  `tn_path` varchar(32),
  `title` varchar(256) NOT NULL,
  `author` varchar(64),
  `series` varchar(64),
  `permission_lvl` ENUM ('guest', 'user', 'uploader', 'admin')
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
array_push($queries, "
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `google_id` varchar(32),
  `permission_lvl` ENUM ('guest', 'user', 'uploader', 'admin')
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");

array_push($queries, "
INSERT INTO `users` (`id`, `email`, `password`, `name`,`google_id`, `permission_lvl`) VALUES
(123, 'jani.niemitalo@gmail.com', '-', 'Jani Niemitalo', '-', 'admin');
");
array_push($queries, "
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`user_id`,`book_id`),
  ADD KEY `book_bookmark` (`book_id`);
");

array_push($queries, "
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);
");

array_push($queries, "
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);
");
array_push($queries, "
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT
");
array_push($queries, "
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;
");
array_push($queries, "
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `book_bookmark` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_bookmark` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

for ($i = 0; $i < count($queries); $i++) {
    $res = $conn->query($queries[$i]);
    //echo $queries[$i]. "<br>";
    echo $res ? "[OK] " . $queries[$i] : "[ERR] " . $conn->error;
    echo "<br/>";
}
