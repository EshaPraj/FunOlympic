SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `secure_auth`
--
CREATE DATABASE `secure_auth`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE secure_auth.users (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_last_updated` date NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `code` text NOT NULL,
  `otp` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `users`
--
ALTER TABLE secure_auth.users
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE secure_auth.users
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

COMMIT;