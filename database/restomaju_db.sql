-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 03:54 AM
-- Server version: 12.2.2-MariaDB-log
-- PHP Version: 8.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restomaju_db`
--
CREATE DATABASE IF NOT EXISTS `restomaju_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `restomaju_db`;

-- --------------------------------------------------------

--
-- Table structure for table `discount_codes`
--

CREATE TABLE `discount_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount_codes`
--

INSERT INTO `discount_codes` (`id`, `code`, `discount_percent`, `is_active`, `created_at`) VALUES
(1, 'DISKON10', 10.00, 1, '2026-06-25 03:31:50'),
(2, 'DISKON15', 15.00, 1, '2026-06-25 03:31:50'),
(3, 'DISKON20', 20.00, 1, '2026-06-25 03:31:50'),
(4, 'HARBOLNAS', 25.00, 1, '2026-06-25 03:31:50'),
(5, 'PROMO50', 50.00, 1, '2026-06-25 03:31:50'),
(6, 'OP', 80.00, 1, '2026-06-25 03:52:16');

-- --------------------------------------------------------

--
-- Table structure for table `log_harga_menu`
--

CREATE TABLE `log_harga_menu` (
  `log_id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `harga_lama` int(11) DEFAULT NULL,
  `harga_baru` int(11) DEFAULT NULL,
  `waktu_perubahan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `log_harga_menu`
--

-- Orphaned data removed to satisfy foreign key constraints

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` enum('food','drink','snack') NOT NULL,
  `price` int(11) NOT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `price`, `status`, `image`, `description`) VALUES
(1, 'Nasi Goreng Spesial', 'food', 25000, 'available', 'https://media.istockphoto.com/id/1246401756/photo/nasi-goreng-indonesian-chicken-fried-rice-on-black-plate-indonesian-cuisine-dish-balinese.jpg', 'Nasi goreng dengan telur, ayam, dan sayuran'),
(2, 'Ayam Bakar', 'food', 35000, 'available', 'https://media.istockphoto.com/id/1390217899/photo/ayam-bakar-madu-roasted-chicken-with-honey-herb-and-spice-from-indonesia.jpg', ''),
(3, 'Soto Ayam solo', 'food', 28000, 'available', 'https://www.shutterstock.com/image-photo/soto-ayam-typical-indonesian-food-600nw-2517244091.jpg', ''),
(4, 'Es Teh Manis', 'drink', 8000, 'available', 'https://img.magnific.com/premium-photo/tamarind-agua-fresca_198067-110187.jpg', 'Es teh dengan gula batu di padukan dengan lemon segar'),
(5, 'Jus Alpukat', 'drink', 15000, 'available', 'https://www.topwisata.info/wp-content/uploads/2022/05/Jus-Alpukat-1.jpg', 'Jus alpukat dengan susu coklat'),
(6, 'Americano ice/hot', 'drink', 12000, 'available', 'https://kitchenpedia.org/wp-content/uploads/2025/01/Iced_Americano.jpg', 'Kopi hitam pilihan nusantara'),
(7, 'Kentang Goreng', 'snack', 18000, 'available', 'https://www.rumahmesin.com/wp-content/uploads/2023/07/Cara-Membuat-Kentang-Goreng-Renyah-ala-KFC-dan-McD.jpeg', 'Kentang goreng renyah bumbu gurih di sertai sauce tomat dan mayones'),
(8, 'Cassava Cheese Cake', 'snack', 20000, 'available', 'https://img.magnific.com/premium-photo/delicious-cassava-cake-table_776674-937660.jpg', 'dengan taburan keju melimpah'),
(9, 'Mie Gomak Medan', 'food', 22000, 'available', 'https://tourtoba.com/wp-content/uploads/2018/01/mie-gomak-medan.jpg', 'Mie gomak medan spesial dengan bumbu kacang'),
(10, 'drink orange', 'drink', 15000, 'available', 'https://www.shutterstock.com/image-photo/all-time-favorite-drink-orange-600nw-1875730990.jpg', 'Perasan jeruk peras asli segar'),
(12, 'happy moctails', 'drink', 22000, 'available', 'https://i.pinimg.com/736x/7f/4c/04/7f4c0466dfadbafdb2a9094cbfbd9600.jpg', 'Mocktail ceria yang dijamin bisa menaikkan mood! Menampilkan gradasi warna memukau dari seduhan bunga telang (butterfly pea) dan lapisan citrus segar di bawahnya, berpadu sempurna dengan soda dingin yang melegakan.'),
(13, 'purple rain moctails', 'drink', 25000, 'available', 'https://i.pinimg.com/736x/98/d1/35/98d1350d70e45fabbe537ecaa1d5815c.jpg', 'Sensasi kesegaran berwarna ungu pekat yang menawan dengan serutan es melimpah. Perpaduan manis asam dari sirup buah, sentuhan lemon, dan daun mint segar yang memberikan efek rileks di setiap tegukannya.'),
(14, 'sparkling guava moctails', 'drink', 22000, 'available', 'https://sweetsandthankyou.com/wp-content/uploads/2024/05/Blue-Hawaiian18-2.jpg', 'Kesegaran tropis yang unik dengan perpaduan sirup rasa jambu biji (guava) dan sensasi sparkling dari air soda yang meletup di mulut. Tampilannya yang dihiasi garnish cantik membuatnya semakin menggugah selera.'),
(15, 'blue moon moctails', 'drink', 22000, 'available', 'https://i.pinimg.com/originals/ce/12/af/ce12af6382658a68699289f313308c80.png', 'Minuman segar berwarna biru cerah bak langit malam. Memadukan sirup blue curacao non-alkohol dengan perasan jeruk nipis dan air soda, memberikan sensasi dingin dan citrus yang sangat menyegarkan dahaga.'),
(16, 'rainbow selasih', 'drink', 25000, 'available', 'https://wiratech.co.id/wp-content/uploads/2023/08/Minuman-Segar-Kekinian.webp', ''),
(17, 'v60', 'drink', 20000, 'available', 'https://i.pinimg.com/736x/37/00/c7/3700c791bc00bb999ad2a33adbc4b386.jpg', 'kopi pilihan(guntur)'),
(19, 'flory latte coffe', 'drink', 22000, 'available', 'https://i.pinimg.com/736x/82/52/35/825235a91d19e1cb49d458c970e9cf7f.jpg', 'Latte panas yang lembut, memadukan espresso shot dengan steamed milk bercita rasa ringan. Disajikan cantik dengan sentuhan latte art bermotif floral di atasnya.'),
(21, 'tradional vietnamese coffe', 'drink', 23000, 'available', 'https://exploreve.com/wp-content/uploads/2025/08/3-16-1024x800.jpg', 'Seduhan kopi pekat menggunakan alat drip (phin) khas Vietnam yang menetes perlahan ke atas lapisan susu kental manis. Menghasilkan karakter kopi yang sangat kuat namun diimbangi rasa manis yang legit.'),
(22, 'ice milk coffe', 'drink', 23000, 'available', 'https://i.pinimg.com/originals/2b/15/e8/2b15e89ff619653b7ffb2a96162faba0.jpg', 'Kopi susu dingin klasik yang menyegarkan. Terbuat dari espresso pilihan yang dipadukan dengan kesegaran susu sapi murni dan es batu, memberikan keseimbangan rasa pahit dan gurih yang pas.'),
(23, 'Thai ice coffe', 'drink', 23000, 'available', 'https://www.recipessin.com/wp-content/uploads/2024/09/Thai-Iced-Coffee-Copy.jpg', 'Perpaduan unik antara kopi hitam pekat dengan racikan rempah khas Thailand, dicampur susu kental manis dan susu evaporasi. Disajikan dingin dengan tekstur yang creamy dan aroma yang khas.'),
(24, 'Ayam Geprek', 'food', 20000, 'available', 'https://i2.wp.com/resepkoki.id/wp-content/uploads/2017/07/Resep-Ayam-geprek-jogja.jpg?fit=2322%2C2167&ssl=1', 'Ayam goreng tepung garing yang digeprek dengan sambal bawang pedas gurih, disajikan dengan lalapan segar.'),
(25, 'Dimsum Ayam dan udang (4 pcs)', 'snack', 15000, 'available', 'https://salimahfood.com/wp-content/uploads/2019/05/dimsum.jpg', 'Dimsum kukus lembut padat daging dengan campuran ayam dan udang, disajikan bersama saus sambal asam manis.'),
(26, 'Sop Buntut Sapi', 'food', 45000, 'available', 'https://tse4.mm.bing.net/th/id/OIP.TM4cagyltPfas5J3c-ksxQHaEC?rs=1&pid=ImgDetMain&o=7&rm=3', 'Kuah kaldu sapi bening dan gurih dengan potongan buntut sapi empuk, kentang, wortel, dan taburan daun bawang.'),
(27, 'roti bakar', 'food', 15000, 'available', 'https://www.shutterstock.com/image-photo/roti-bakar-bandung-bread-toast-260nw-2230512039.jpg', ''),
(28, 'bakpau', 'food', 15000, 'available', 'https://cdn0-production-images-kly.akamaized.net/9MyI0GvlUbnJaNc7Y5uzHp1jJng=/1280x720/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/2384515/original/066579500_1539673228-bakpao_deluxtrade.jpg', ''),
(29, 'Tempe Mendoan', 'snack', 10000, 'available', 'https://th.bing.com/th/id/OIP.WQTAQJW7vWtSC5teMK0PzwHaFi?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3', 'Tempe iris tipis berlapis adonan tepung daun bawang yang digoreng setengah matang, disajikan dengan cocolan sambal kecap pedas.'),
(30, 'kebab turky', 'snack', 30000, 'available', 'https://cookingorgeous.com/wp-content/uploads/2021/06/lamb-shish-kebab-20.jpg', ''),
(31, 'corn dog', 'snack', 18000, 'available', 'https://www.maangchi.com/wp-content/uploads/2020/10/potato-corndog-tube-scaled.jpg', ''),
(32, 'bebek panggang', 'food', 40000, 'available', 'https://1.bp.blogspot.com/-PubIBDro8_U/U2Hb14FuJOI/AAAAAAAAAHM/_q0lRauP654/s1600/bebek+panggang.JPG', ''),
(33, 'lobster roll', 'snack', 25000, 'available', 'https://myhomemaderecipe.com/assets/images/1742001280523-mfcmyg8f.webp', ''),
(35, 'sahsimi flat', 'food', 150000, 'available', 'https://a.cdn-hotels.com/gdcs/production195/d1631/8f7586ab-5509-4c30-9100-ab9a903d6dad.jpg?impolicy=fcrop&w=1600&h=1066&q=medium', ''),
(36, 'odeng sup', 'food', 35000, 'available', 'https://i.pinimg.com/originals/28/21/e0/2821e0b5d0ec0d9d862e728de6711db9.jpg', ''),
(37, 'steak butter', 'food', 100000, 'available', 'https://easylowcarb.com/wp-content/uploads/2023/06/Grilled-Sirloin-Steak-EasyLowCarb-6.jpg', ''),
(38, 'shusi slash', 'food', 140000, 'available', 'https://png.pngtree.com/png-clipart/20220125/original/pngtree-sushi-slash-png-image_7223043.png', 'Hidangan khas Jepang berupa nasi yang dibumbui cuka dan dipadukan dengan ikan segar, seafood, atau sayuran.'),
(39, 'ramen', 'food', 25000, 'available', 'https://images5.alphacoders.com/132/1322094.png', 'Mi Jepang yang disajikan dalam kuah kaldu gurih dengan topping seperti telur, daging chashu, dan daun bawang.'),
(40, 'tempura tepung', 'snack', 25000, 'available', 'https://d3nrav7vo3lya8.cloudfront.net/categories/tempura/veg-tempura.webp', 'Udang atau sayuran yang dibalut adonan ringan lalu digoreng hingga renyah.'),
(41, 'takoyaki', 'snack', 20000, 'available', 'https://www.theforkbite.com/wp-content/uploads/2021/01/Takoyaki-balls-featured-1.11.21.jpg', 'Bola-bola tepung berisi potongan gurita yang dimasak dalam cetakan khusus dan diberi saus khas Jepang.'),
(42, 'Chiken katsu kare', 'food', 25000, 'available', 'https://homemadevibes.com/wp-content/uploads/2025/10/Chicken-Katsu-Curry.png', 'Nasi dengan kari Jepang yang kental dan potongan daging goreng tepung (tonkatsu atau chicken katsu).'),
(46, 'onigiri', 'snack', 20000, 'available', 'https://www.chopstickchronicles.com/wp-content/uploads/2020/03/Onigiri-2020-update-22-e1653097511419-1024x1024.jpg', 'Nasi kepal berbentuk segitiga atau bulat dengan isian seperti tuna, salmon, atau umeboshi, dibungkus rumput laut.'),
(47, 'Yakitiro', 'snack', 40000, 'available', 'https://www.chilipeppermadness.com/wp-content/uploads/2024/05/Chicken-Yakitori-Recipe1.jpg', 'Sate ayam khas Jepang yang dipanggang dan dibumbui saus tare atau garam.'),
(48, 'Mochi', 'snack', 20000, 'available', 'https://www.foodinjapan.org/wp-content/uploads/2023/03/mochi_1024x768.jpg', 'Kue tradisional Jepang yang terbuat dari beras ketan dengan tekstur kenyal dan berbagai isian manis.'),
(49, 'Tonkatsu', 'food', 50000, 'available', 'https://fryit.co/wp-content/uploads/2023/08/air-fryer-tonkatsu_rcpimg.jpg', 'Daging babi atau ayam yang dilapisi tepung roti dan digoreng hingga keemasan, disajikan dengan saus tonkatsu khas Jepang.'),
(50, 'Okonomiyaki', 'snack', 25000, 'available', 'https://i0.wp.com/fullofplants.com/wp-content/uploads/2017/10/the-best-vegan-okonomiyaki-gluten-free-with-jackfruit-thumb-3.jpg?fit=1400%2C1400&ssl=1', 'Pancake gurih khas Jepang yang terbuat dari adonan tepung, kol, dan berbagai topping seperti seafood atau daging.'),
(51, 'Gyudon', 'food', 35000, 'available', 'https://juliameals.com/wp-content/uploads/2025/11/9-1-1.png', 'https://juliameals.com/wp-content/uploads/2025/11/9-1-1.png'),
(52, 'Karaage', 'snack', 25000, 'available', 'https://twoplaidaprons.com/wp-content/uploads/2023/06/karaage-chicken-on-plate-with-dipping-sauce-thumbnail-shot.jpg', 'Ayam goreng khas Jepang yang dimarinasi dengan kecap asin, jahe, dan bawang putih sebelum digoreng renyah.'),
(53, 'Unagi Don', 'food', 35000, 'available', 'https://www.justonecookbook.com/wp-content/uploads/2021/07/Unadon-Eel-Rice-9592-I-2.jpg', 'Nasi dengan belut panggang yang dilumuri saus manis khas Jepang.'),
(54, 'Dorayaki', 'snack', 20000, 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSHMQIYWhPi7auR___1jJsBQMneadc5jymMIg&s', 'Kue tradisional Jepang berupa dua lapis pancake lembut yang diisi pasta kacang merah manis.'),
(55, 'Chawanmushi', 'snack', 25000, 'available', 'https://whattocooktoday.com/wp-content/uploads/2011/12/chawanmushi-6.jpg', 'Puding telur gurih yang dikukus dengan tambahan ayam, udang, jamur, dan bahan lainnya.'),
(56, 'Sukiyaki', 'food', 60000, 'available', 'https://www.justonecookbook.com/wp-content/uploads/2023/01/Sukiyaki-4752-I.jpg', 'Daging sapi, tahu, dan sayuran yang dimasak dalam kuah manis berbahan kecap asin dan gula.'),
(57, 'Matcha', 'drink', 30000, 'available', 'https://oregonsportsnews.com/wp-content/uploads/2019/09/Matcha.jpg', 'Minuman khas Jepang yang dibuat dari bubuk teh hijau premium (matcha) yang dikocok hingga lembut lalu dipadukan dengan susu segar. Memiliki rasa yang seimbang antara manis, creamy, dan sedikit pahit khas teh hijau, menjadikannya pilihan favorit untuk dinikmati kapan saja.');

--
-- Triggers `menu_items`
--
DELIMITER $$
CREATE TRIGGER `catat_perubahan_harga` BEFORE UPDATE ON `menu_items` FOR EACH ROW begin
if old.price <> new.price then
insert into log_harga_menu
set menu_id = old.id,
harga_lama = old.price,
harga_baru = new.price,
waktu_perubahan = now();
end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `total_amount` int(11) DEFAULT 0,
  `status` enum('pending','ready','paid') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `tip_amount` decimal(10,2) DEFAULT 0.00,
  `discount_code_id` int(11) DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_id`, `customer_name`, `total_amount`, `status`, `created_at`, `tip_amount`, `discount_percent`, `notes`) VALUES
(1, 5, 'if', 25000, 'paid', '2026-06-02 01:34:43', 0.00, 0.00, NULL),
(3, 6, 'ba', 93000, 'paid', '2026-06-04 09:04:37', 0.00, 0.00, NULL),
(4, 7, 'me', 30000, 'paid', '2026-06-06 00:56:57', 0.00, 0.00, NULL),
(5, 6, 'aku', 237000, 'paid', '2026-06-06 01:26:36', 10000.00, 10.00, NULL),
(6, 11, 'dia', 25000, 'paid', '2026-06-06 01:31:36', 5000.00, 50.00, NULL),
(7, 18, 'Diriku', 120000, 'paid', '2026-06-06 01:36:15', 10000.00, 10.00, NULL),
(8, 9, 'Budi', 50000, 'paid', '2026-06-06 07:50:22', 5000.00, 10.00, NULL),
(9, 15, 'Regie', 75000, 'paid', '2026-06-09 07:17:21', 0.00, 0.00, NULL),
(10, 6, 'mamat', 700000, 'paid', '2026-06-09 08:58:30', 10000.00, 10.00, NULL),
(11, 14, 'Mimi', 88000, 'paid', '2026-06-10 05:05:15', 0.00, 0.00, NULL),
(12, 3, 'Regie', 35000, 'paid', '2026-06-11 09:03:14', 0.00, 0.00, NULL),
(13, 4, 'Adhia', 78000, 'paid', '2026-06-12 14:29:00', 0.00, 0.00, NULL),
(14, 9, 'Cecep', 384000, 'pending', '2026-06-23 05:52:32', 0.00, 0.00, NULL),
(15, 14, 'Rafli', 96000, 'paid', '2026-06-23 08:57:51', 10000.00, 10.00, NULL),
(16, 3, 'reg', 120000, 'paid', '2026-06-23 09:15:12', 0.00, 0.00, NULL),
(17, 4, 'adh', 76000, 'ready', '2026-06-23 09:21:00', 0.00, 0.00, NULL),
(18, 6, 'Egy', 16000, 'pending', '2026-06-25 03:43:18', 0.00, 0.00, 'ga pake es');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `tg_after_order_paid` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status = 'paid' AND OLD.status != 'paid' THEN
        UPDATE restaurant_tables
        SET status = 'empty'
        WHERE id = NEW.table_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_after_order_placed` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    UPDATE restaurant_tables
    SET status = 'occupied'
    WHERE id = NEW.table_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 25000),
(3, 3, 3, 1, 28000),
(4, 3, 4, 1, 8000),
(5, 3, 8, 1, 15000),
(6, 3, 9, 1, 22000),
(7, 3, 10, 2, 10000),
(8, 4, 5, 1, 15000),
(9, 4, 8, 1, 15000),
(10, 5, 2, 3, 35000),
(11, 5, 4, 2, 8000),
(12, 5, 5, 1, 15000),
(13, 5, 7, 3, 18000),
(14, 5, 8, 1, 15000),
(15, 5, 10, 1, 10000),
(16, 5, 9, 1, 22000),
(17, 6, 1, 1, 25000),
(18, 7, 2, 1, 35000),
(19, 7, 3, 1, 28000),
(20, 7, 5, 2, 15000),
(21, 7, 6, 1, 12000),
(22, 7, 8, 1, 15000),
(23, 8, 1, 2, 25000),
(24, 9, 1, 1, 25000),
(25, 9, 2, 1, 35000),
(26, 9, 10, 1, 15000),
(27, 10, 2, 1, 35000),
(28, 10, 31, 1, 18000),
(29, 10, 30, 1, 30000),
(30, 10, 37, 1, 100000),
(31, 10, 38, 1, 140000),
(32, 10, 35, 2, 150000),
(33, 10, 13, 1, 25000),
(34, 10, 14, 1, 22000),
(35, 10, 57, 1, 30000),
(36, 11, 1, 1, 25000),
(37, 11, 2, 1, 35000),
(38, 11, 4, 1, 8000),
(39, 11, 8, 1, 20000),
(40, 12, 2, 1, 35000),
(41, 13, 1, 1, 25000),
(42, 13, 2, 1, 35000),
(43, 13, 7, 1, 18000),
(44, 14, 1, 1, 25000),
(45, 14, 2, 2, 35000),
(46, 14, 5, 1, 15000),
(47, 14, 7, 1, 18000),
(48, 14, 12, 1, 22000),
(49, 14, 17, 1, 20000),
(50, 14, 19, 1, 22000),
(51, 15, 1, 1, 25000),
(52, 15, 4, 1, 8000),
(53, 15, 25, 1, 15000),
(54, 16, 1, 1, 25000),
(55, 16, 5, 1, 15000),
(56, 16, 8, 1, 20000),
(57, 17, 8, 1, 20000),
(58, 17, 7, 1, 18000),
(59, 18, 4, 1, 8000);

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `tg_calculate_total_amount` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    UPDATE orders 
    SET total_amount = total_amount + (NEW.price * NEW.quantity)
    WHERE id = NEW.order_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_tables`
--

CREATE TABLE `restaurant_tables` (
  `id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `status` enum('empty','occupied','ready') DEFAULT 'empty'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant_tables`
--

INSERT INTO `restaurant_tables` (`id`, `table_number`, `status`) VALUES
(1, 'Meja 01', 'empty'),
(2, 'Meja 02', 'empty'),
(3, 'Meja 03', 'empty'),
(4, 'Meja 04', 'ready'),
(5, 'Meja 05', 'empty'),
(6, 'Meja 06', 'occupied'),
(7, 'Meja 07', 'empty'),
(8, 'Meja 08', 'empty'),
(9, 'Meja 09', 'occupied'),
(10, 'Meja 10', 'empty'),
(11, 'Meja 11', 'empty'),
(12, 'Meja 12', 'empty'),
(13, 'Meja 13', 'empty'),
(14, 'Meja 14', 'empty'),
(15, 'Meja 15', 'empty'),
(16, 'Meja 16', 'empty'),
(17, 'Meja 17', 'empty'),
(18, 'Meja 18', 'empty'),
(19, 'Meja 19', 'empty'),
(20, 'Meja 20', 'empty'),
(21, 'Meja 21', 'empty');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('waiter','kitchen','cashier','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'pelayan', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'waiter', '2026-06-06 01:13:16', '2026-06-06 01:13:16'),
(2, 'dapur', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'kitchen', '2026-06-06 01:13:16', '2026-06-06 01:13:16'),
(3, 'kasir', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'cashier', '2026-06-06 01:13:16', '2026-06-25 03:54:08'),
(4, 'admin', '$2y$10$wrNh47kbS6CavrdnHmMLeug7.utxyQcrrJqjQFkmcDPj4Pe7E7mey', 'admin', '2026-06-06 01:13:16', '2026-06-06 01:13:16');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pesanan_lengkap`
-- (See below for the actual view)
--
CREATE TABLE `view_pesanan_lengkap` (
`order_id` int(11)
,`customer_name` varchar(100)
,`table_number` varchar(10)
,`menu_name` varchar(100)
,`category` enum('food','drink','snack')
,`quantity` int(11)
,`price_per_item` int(11)
,`subtotal` bigint(21)
,`order_status` enum('pending','ready','paid')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_active_billing`
-- (See below for the actual view)
--
CREATE TABLE `vw_active_billing` (
`table_id` int(11)
,`table_number` varchar(10)
,`order_id` int(11)
,`customer_name` varchar(100)
,`subtotal` int(11)
,`order_status` enum('pending','ready','paid')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_analytics`
-- (See below for the actual view)
--
CREATE TABLE `vw_admin_analytics` (
`category` enum('food','drink','snack')
,`total_orders` bigint(21)
,`total_items_sold` decimal(32,0)
,`gross_revenue` decimal(42,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_kitchen_queue`
-- (See below for the actual view)
--
CREATE TABLE `vw_kitchen_queue` (
`order_id` int(11)
,`table_number` varchar(10)
,`customer_name` varchar(100)
,`menu_name` varchar(100)
,`quantity` int(11)
,`order_status` enum('pending','ready','paid')
,`order_time` timestamp
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `log_harga_menu`
--
ALTER TABLE `log_harga_menu`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `log_harga_menu`
--
ALTER TABLE `log_harga_menu`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- --------------------------------------------------------

--
-- Structure for view `view_pesanan_lengkap`
--
DROP TABLE IF EXISTS `view_pesanan_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pesanan_lengkap`  AS SELECT `o`.`id` AS `order_id`, `o`.`customer_name` AS `customer_name`, `rt`.`table_number` AS `table_number`, `m`.`name` AS `menu_name`, `m`.`category` AS `category`, `oi`.`quantity` AS `quantity`, `oi`.`price` AS `price_per_item`, `oi`.`quantity`* `oi`.`price` AS `subtotal`, `o`.`status` AS `order_status` FROM (((`orders` `o` join `restaurant_tables` `rt` on(`o`.`table_id` = `rt`.`id`)) join `order_items` `oi` on(`o`.`id` = `oi`.`order_id`)) join `menu_items` `m` on(`oi`.`menu_id` = `m`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_active_billing`
--
DROP TABLE IF EXISTS `vw_active_billing`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_active_billing`  AS SELECT `t`.`id` AS `table_id`, `t`.`table_number` AS `table_number`, `o`.`id` AS `order_id`, `o`.`customer_name` AS `customer_name`, `o`.`total_amount` AS `subtotal`, `o`.`status` AS `order_status` FROM (`restaurant_tables` `t` join `orders` `o` on(`t`.`id` = `o`.`table_id`)) WHERE `o`.`status` = 'ready' ;

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_analytics`
--
DROP TABLE IF EXISTS `vw_admin_analytics`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_admin_analytics`  AS SELECT `mi`.`category` AS `category`, count(distinct `o`.`id`) AS `total_orders`, sum(`oi`.`quantity`) AS `total_items_sold`, sum(`oi`.`quantity` * `oi`.`price`) AS `gross_revenue` FROM ((`orders` `o` join `order_items` `oi` on(`o`.`id` = `oi`.`order_id`)) join `menu_items` `mi` on(`oi`.`menu_id` = `mi`.`id`)) WHERE `o`.`status` = 'paid' GROUP BY `mi`.`category` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_kitchen_queue`
--
DROP TABLE IF EXISTS `vw_kitchen_queue`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_kitchen_queue`  AS SELECT `o`.`id` AS `order_id`, `t`.`table_number` AS `table_number`, `o`.`customer_name` AS `customer_name`, `mi`.`name` AS `menu_name`, `oi`.`quantity` AS `quantity`, `o`.`status` AS `order_status`, `o`.`created_at` AS `order_time` FROM (((`orders` `o` join `restaurant_tables` `t` on(`o`.`table_id` = `t`.`id`)) join `order_items` `oi` on(`o`.`id` = `oi`.`order_id`)) join `menu_items` `mi` on(`oi`.`menu_id` = `mi`.`id`)) WHERE `o`.`status` = 'pending' ORDER BY `o`.`id` ASC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_harga_menu`
--
ALTER TABLE `log_harga_menu`
  ADD CONSTRAINT `fk_log_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_table` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_order_discount` FOREIGN KEY (`discount_code_id`) REFERENCES `discount_codes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
