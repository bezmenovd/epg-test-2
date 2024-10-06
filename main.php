<?php

use App\Models\Order;
use App\Models\OrderProduct;

require_once "./vendor/autoload.php";


if ($argc !== 2) {
    print(<<<TEXT
    Usage: php main.php [id]
    Get order data\n
    TEXT);
    return;
}

$orderId = intval($argv[1]);

if (! ($orderId > 0)) {
    print("invalid id\n");
    return;
}


$env = parse_ini_file('.env');
$pdo = new PDO(
    sprintf("mysql:host=%s;dbname=%s", $env['DB_HOST'], $env['DB_NAME']),
    $env['DB_USER'],
    $env['DB_PASS'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
);


function getOrder(int $orderId): ?Order {
    global $pdo;
    
    $data = $pdo->query(<<<SQL
        SELECT * FROM `orders` LEFT JOIN `order_products` ON `orders`.`id` = `order_products`.`order_id` WHERE `orders`.`id` = $orderId 
    SQL)->fetchAll();

    if (empty($data)) {
        return null;
    }

    $order = new Order(
        id: $data[0]['order_id'],
        products: array_map(fn(array $item) => new OrderProduct(
            id: $item['id'],
            orderId: $item['order_id'],
            name: $item['name'],
            price: $item['price'],
            quantity: $item['quantity'],
            discountPercent: $item['discount_percent'],
        ), $data),
    );

    return $order;
}


$order = getOrder($orderId);

if (is_null($order)) {
    print("order not found \n");
    return;
}

printf("Order #%d \n", $order->id);
printf("Total: %.2f \n", $order->getTotal());
printf("Products: \n");

foreach ($order->products as $product) {
    /** @var OrderProduct $product */
    printf("%d \t %s \t %.2f \t %d \t %.2f \t %.2f \n", 
        $product->id, $product->name, $product->price, 
        $product->quantity, $product->discountPercent, $product->getTotal()
    );
}
