<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Function to get the total item count in the cart
function getCartItemCount($conn, $user_id)
{
    $result = mysqli_query($conn, "SELECT SUM(quantity) as total FROM `cart` WHERE user_id = '$user_id'");
    $row = mysqli_fetch_assoc($result);
    return ($row['total']) ? $row['total'] : 0;
}

// Get the current cart item count
$item_count = getCartItemCount($conn, $user_id);

// Return the cart information as a JSON response
echo json_encode(['itemCount' => $item_count]);
?>
