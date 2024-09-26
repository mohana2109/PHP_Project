<?php
session_start();

require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/SMTP.php";
require_once "PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

function sendmail($customerEmail) {
    $name = "Purple";  // Name of your website or yours
    $from = "mohanasundari219@gmail.com";  // Your email address
    $password = "asva gqlu huab wexk";    // Your email password
    $to = $customerEmail;  // Use the dynamic email address
    $subject = "Order Confirmation";

    // Retrieve the selected products from the session
    $selectedProducts = $_SESSION["cart_item"];

    // Prepare the email content dynamically based on selected products
    $message = '<p>Thank you for your order. Here are your selected products:</p>';
    $totalAmount = 0;

    foreach ($selectedProducts as $product) {
        $subtotal = $product['price'] * $product['quantity'];
        $totalAmount += $subtotal;

        $message .= '<p>Name: ' . $product['name'] . '</p>';
        $message .= '<p>Quantity: ' . $product['quantity'] . '</p>';
        $message .= '<p>Subtotal: $' . $subtotal . '</p>';
        $message .= '<img src="' . $product['image'] . '" alt="' . $product['name'] . '" width="100">';
        $message .= '<hr>';
    }

    $message .= '<p>Total Order Amount: $' . $totalAmount . '</p>';

    $mail = new PHPMailer(true); // Passing true enables exceptions

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->Port = 587;
        $mail->SMTPSecure = "tls";

        // Email settings
        $mail->isHTML(true);
        $mail->setFrom($from, $name);
        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body = $message;

        // Enable SMTP debugging for troubleshooting
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        $mail->send();
        echo "Email to main recipient is sent!";
    } catch (Exception $e) {
        echo "Something went wrong with the main recipient: " . $mail->ErrorInfo;
    }
}

if (isset($_SESSION["customer_email"])) {
    $customerEmail = $_SESSION["customer_email"];
    sendmail($customerEmail);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
     <meta http-equiv="refresh" content="0; url=http://localhost\program\shopping cart with user login\shopping cart with user login\index.php" /> 
</head>
<body>
    <h1>Order Confirmation Page</h1>
    <!-- Add your order confirmation content here -->
</body>
</html>
<?php

// Handle the form submission
if (isset($_POST['sendmail'])) {
    // Add validation and sanitation as needed
    $customerEmail = $_POST['email'];

    // Store the customer's email in the session
    $_SESSION['customer_email'] = $customerEmail;
}

// Rest of your PHP code
?>
