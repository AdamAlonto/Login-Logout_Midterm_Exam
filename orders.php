<?php
session_start();
require_once '../core/dbConfig.php';
require_once '../core/models.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../sql/login.php");
    exit;
}

$orders = getOrdersByUser($pdo, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FBFAF2;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #FFFADA;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        input[type="submit"] {
            background-color: #228B22;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: block; 
            margin: 20px auto; 
        }
        input[type="submit"]:hover {
            background-color: #2FF924;
        }
        .logout-container {
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Orders</h1>

        <?php if ($orders): ?>
            <table>
                <tr>
                    <th>Gun</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
                <?php 
                $totalPrice = 0;
                foreach ($orders as $order): 
                    $gunPrice = getGunPrice($pdo, $order['gun_id']); 
                    $orderTotal = $gunPrice * $order['quantity'];
                    $totalPrice += $orderTotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['gun_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo '₱' . number_format($gunPrice, 2); ?></td> 
                        <td><?php echo '₱' . number_format($orderTotal, 2); ?></td> 
                        <td>
                            <a href="editOrder.php?id=<?php echo $order['order_id']; ?>">Edit</a>
                            <a href="deleteOrder.php?id=<?php echo $order['order_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total Price:</strong></td>
                    <td><?php echo '₱' . number_format($totalPrice, 2); ?></td> 
                    <td></td>
                </tr>
            </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>

        <a href="index.php">Place a New Order</a>
        <div class="logout-container">
            <form action="logout.php" method="POST">
                <input type="submit" value="Logout">
            </form>
        </div>
    </div>
</body>
</html>
