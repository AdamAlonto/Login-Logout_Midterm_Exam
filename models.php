<?php

function addCustomer($pdo, $first_name, $last_name, $email, $contact_number) {
    $sql = "INSERT INTO customers (first_name, last_name, email, contact_number) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $last_name, $email, $contact_number]);
    return $pdo->lastInsertId();
}

function getAllGuns($pdo) {
    $sql = "SELECT * FROM guns";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGunPrice($pdo, $gun_id) {
    $stmt = $pdo->prepare("SELECT price FROM guns WHERE gun_id = ?");
    $stmt->execute([$gun_id]);
    $gun = $stmt->fetch();
    
    return $gun ? $gun['price'] : 0; 
}

function addOrder($pdo, $customer_id, $gun_id, $quantity, $added_by) {
    $sql = "INSERT INTO orders (customer_id, gun_id, quantity, added_by) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$customer_id, $gun_id, $quantity, $added_by]);
}

function getOrdersByUser($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT o.order_id, o.gun_id, g.gun_name, o.quantity 
            FROM orders o 
            JOIN guns g ON o.gun_id = g.gun_id 
            WHERE o.added_by = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getOrderByID($pdo, $orderId) {
    $sql = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$orderId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateOrder($pdo, $orderId, $data) {
    $sql = "UPDATE orders SET gun_id = ?, quantity = ? WHERE order_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data['gun_id'], $data['quantity'], $orderId]);
}

function deleteOrder($pdo, $orderId) {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ?");
    return $stmt->execute([$orderId]);
}

?>
