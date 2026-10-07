<?php
if(!file_exists(__DIR__ . "/src/Models/EmailModel.php")){
    echo "email model file not found";
    die();
}
require_once __DIR__ . "/src/Models/EmailModel.php";
$host = "100.72.158.117";
$dbName = "if0_39722397_zvelake";
$dbUser = "if0_39722397";
$dbPass = "pehBevo9Zxfx";
$pdo = new \PDO("mysql:host=$host;dbname=$dbName;port=3305", $dbUser, $dbPass);

if(!$pdo){
    echo "Failed to connect to databse";
    exit;
}
$stmt = $pdo->query("SELECT o.*, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id LIMIT 2");
$order = $stmt->fetch();
$stmt = $pdo->query("SELECT id FROM products WHERE product_type = 'event'");
$productId = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM events WHERE product_id = ?");
$stmt->execute([$productId]);
$event = $stmt->fetch();
if(!$event){ return; }
$eventName = $event["title"];
$eventMeta = "{$event["event_date"]},{$event['location']}\n";
$paymentMethod = $order['payment_method'];
$totalAmount = $order['total_amount'];
$paymentDate = $order['payment_date'];
$orderNumber = $order['order_number'];
$userInfo = [
    "name" => "zwelakhe",
    "email" => "zwelakhe.mzwet@gmail.com"
];

$email = new EmailModel();
ob_start();
require_once(TEMPLATES_DIR . "/../Components/EventTicketReceipt.php");
$bodyHtml = ob_get_clean();
$subject = "Event ticket";
$toEmail = $userInfo['email'];
$toName = ($userInfo['name'] ?? $userInfo['first_name']) . ' ' . ($userInfo['last_name'] ?? '');
if (empty(trim($toName))) {
    $toName = $userInfo['name'] ?? $userInfo['email'];
}

$result = $email->prepare($subject, $toEmail, $toName, $bodyHtml, "")->send();
//$event->sendTicket($order, $productId);
?>