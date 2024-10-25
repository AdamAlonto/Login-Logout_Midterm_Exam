<?php
require_once '../core/dbConfig.php';
require_once '../core/models.php';

session_start();

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $order = getOrderByID($pdo, $orderId); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'gun_id' => $_POST['gun_id'],
            'quantity' => $_POST['quantity'],
            'added_by' => $_SESSION['user_id'] 
        ];
        updateOrder($pdo, $orderId, $data);
        header('Location: index.php'); 
        exit;
    }

    $guns = getAllGuns($pdo);
} else {
    die('Order ID not provided.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Your Order Here</title>
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
            max-width: 400px;
            margin: auto; 
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        select,
        input[type="number"],
        input[type="submit"] {
            width: 100%; 
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #1520A6;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Edit Your Order Here</h1>
    <form action="" method="POST">
        <label>Gun Type:</label>
        <select name="gun_id" required>
            <?php foreach ($guns as $gun): ?>
                <option value="<?php echo $gun['gun_id']; ?>" <?php echo $gun['gun_id'] == $order['gun_id'] ? 'selected' : ''; ?>>
                    <?php echo $gun['gun_name'] . ' (₱' . $gun['price'] . ')'; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($order['quantity']); ?>" required>

        <input type="submit" value="Update Order">
    </form>
</body>
</html>
