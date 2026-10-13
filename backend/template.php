<?php
$expireAt = date("Y-m-d H:i:s", strtotime("8 days", (new \DateTime("2026-09-23 21:27:14"))->getTimestamp()));
echo (new \DateTime($expireAt)) > (new \DateTime()) ? "active" : "expired";
//$event->sendTicket($order, $productId);
?>