<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Function to get the total item count and grand total in the cart
function getCartInfo($conn, $user_id)
{
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    $grand_total = 0;
    $item_count = 0;

    while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
        $item_count += $fetch_cart['quantity'];
        $grand_total += $fetch_cart['price'] * $fetch_cart['quantity'];
    }

    return [
        'itemCount' => $item_count,
        'grandTotal' => $grand_total,
    ];
}

// Get the current cart info
$cartInfo = getCartInfo($conn, $user_id);
?>


<?php


include 'config.php';
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($select_cart) > 0){

      $message[] = 'product already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      $message[] = 'product added to cart!';
   }

};

if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['cart_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   $message[] = 'cart quantity updated successfully!';
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:cart.php');
}
  
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/in.css">
</head>
<body>
    <?php
    // if (isset($message)) {
    //     foreach ($message as $message) {
    //         echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
    //     }
    // }
    ?>

    <div class="container">

        <div class="user-profile">
           
            <?php
                $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
                if(mysqli_num_rows($select_user) > 0){
                    $fetch_user = mysqli_fetch_assoc($select_user);
                };
            ?>

            <p> Welcome <span><?php echo $fetch_user['name']; ?></span> </p>
            
            <div class="flex">
                <a href="login.php" class="btn">login</a>
                <a href="register.php" class="option-btn">register</a>
                <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="delete-btn">logout</a>


<div class="checkout-btn">
                <a href="cart.php" class="btn">
                    Cart (<span id="itemCount"><?php echo $cartInfo['itemCount']; ?></span> )
                </a>
            </div>







            </div>
        </div>

        <div class="products">
            <h1 class="heading">latest products</h1>
            <div class="box-container">

                <?php
                $select_product = mysqli_query($conn, "SELECT * FROM `product`") or die('query failed');
                if(mysqli_num_rows($select_product) > 0){
                    while($fetch_product = mysqli_fetch_assoc($select_product)){
                ?>
                        <form method="post" class="box" action='<?php echo $_SERVER["REQUEST_URI"];?>'>
                            <img src="<?php echo $fetch_product['image']; ?>" alt="">
                            <div class="name"><?php echo $fetch_product['name']; ?></div>
                            <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
                            <input type="number" min="1" name="product_quantity" value="1">
                            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                            <input type="submit" value="add to cart" name="add_to_cart" class="btn" onclick="updateCartInfo()">

                        </form>







                <?php
                    };
                };
                ?>
            </div>
        </div>

    </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

     <script>
        // Function to update the cart info dynamically
        function updateCartInfo() {
            $.ajax({
                type: 'GET',
                url: 'update_cart_count.php', // Create a server-side script to handle this request
                dataType: 'json',
                success: function (data) {
                    // Update the item count in the checkout button
                    $('#itemCount').text(data.itemCount);
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }

        // Call the updateCartInfo function on page load
        $(document).ready(function () {
            updateCartInfo();
        });
    </script>
 
</body>
</html>
</body>
</html>
  