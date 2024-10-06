CREATE TABLE IF NOT EXISTS `orders` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `order_products`(
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `order_id` bigint unsigned NOT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `price` decimal(8,2) NOT NULL,
    `quantity` int NOT NULL,
    `discount_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`id`),
    KEY `order_products_order_id_foreign` (`order_id`),
    CONSTRAINT `order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
);

INSERT INTO `orders` (`id`) VALUES 
(1), 
(2), 
(3);

INSERT INTO `order_products` (`order_id`, `name`, `price`, `quantity`, `discount_percent`) VALUES 
(1, "Product 1", 500, 2, 14),
(1, "Product 2", 100, 3, 0),
(2, "Product 1", 812.3, 1, 50);
