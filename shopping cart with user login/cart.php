<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted for updating the quantity
    if (isset($_POST['cart_quantity'], $_POST['cart_id'])) {
        $cart_id = $_POST['cart_id'];
        $new_quantity = (int)$_POST['cart_quantity'];

        // Update the cart with the new quantity
        $update_query = mysqli_query($conn, "UPDATE `cart` SET quantity = $new_quantity WHERE id = $cart_id AND user_id = $user_id");

        if (!$update_query) {
            die('Update query failed: ' . mysqli_error($conn));
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/in.css">
</head>

<body>
    <div class="container">

        <div class="shopping-cart">
            <h1 class="heading">Shopping Cart</h1>
            <table>
                <thead>
                    <!-- ... (same as in the original code) -->
                </thead>
                <tbody>
                    <?php
                    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                    $grand_total = 0;
                    $item_count = 0;
                    if (mysqli_num_rows($cart_query) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                            ?>
                            <tr>
                                <td><img src="<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
                                <td><?php echo $fetch_cart['name']; ?></td>
                                <td>$<?php echo $fetch_cart['price']; ?>/-</td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                        <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>$<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</td>
                                <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('remove item from cart?');">remove</a></td>
                            </tr>
                            <?php
                            $item_count += $fetch_cart['quantity'];
                            $grand_total += $sub_total;
                        }
                    } else {
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">No item added</td></tr>';
                    }
                    ?>
                    <tr class="table-bottom">
                        <td colspan='2'>
                            <a href='index.php' class="btn btn-success">Continue Shopping</a>
                        </td>
                        <td colspan="2">Grand Total :</td>
                        <td>$<?php echo $grand_total; ?>/-</td>
                        <td>
                            <a href="index.php?delete_all" onclick="return confirm('Delete all from cart?');"
                                class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Delete All</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-btn">
                <a href="total.php" class="btn">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>

</body>

</html>
