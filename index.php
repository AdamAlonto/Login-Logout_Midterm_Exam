<?php
session_start();
require_once '../core/dbConfig.php';
require_once '../core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../sql/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $added_by = $_SESSION['user_id'];
    $customer_id = addCustomer($pdo, $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['contact_number']);
    $gun_id = $_POST['gun_id'];
    $quantity = $_POST['quantity'];

    addOrder($pdo, $customer_id, $gun_id, $quantity, $added_by);
    header("Location: ../sql/orders.php"); 
    exit;
}

$guns = getAllGuns($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gun Shop</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FBFAF2;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background: #FFFADA;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h3 {
            margin-top: 20px;
            color: #555;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #2FF924;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #1520A6;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Adam's Ordering Guns</h1>
    <form action="" method="POST">
        <h3>Customer Information</h3>
        <label>First Name:</label>
        <input type="text" name="first_name" required>
        <label>Last Name:</label>
        <input type="text" name="last_name" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Contact Number:</label>
        <input type="text" name="contact_number" required>

        <h3>Choose Your Gun:</h3>
        <select name="gun_id" required>
            <?php foreach ($guns as $gun): ?>
                <option value="<?php echo $gun['gun_id']; ?>">
                    <?php echo htmlspecialchars($gun['gun_type'] . ' - ' . $gun['gun_name'] . ' (₱' . number_format($gun['price'], 2) . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <label>Quantity:</label>
        <input type="number" name="quantity" required min="1">
        <input type="submit" value="Place Order">
    </form>

    <a href="orders.php">Show My Orders</a> 
</body>
</html>
