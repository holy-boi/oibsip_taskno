<?php

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

include 'config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $code = mysqli_real_escape_string($conn, md5(rand()));

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $query = mysqli_query($conn, "UPDATE users SET code='{$code}' WHERE email='{$email}'");

        if ($query) {        
            echo "<div style='display: none;'>";
            //Create an instance; passing `true` enables exceptions
           

            try {
                //Server settings
                $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
$transport->setUsername('emeriebow78@gmail.com');
$transport->setPassword('bmoyqgmodswblekp');

// Create a Swift Mailer instance
$mailer = new Swift_Mailer($transport);

// Create an email message
$message = new Swift_Message('Subject of the Email');
$message->setFrom(['emeriebow78@gmail.com' => 'LOGIN-FORM']);
$message->setTo([($email) => 'Recipient Name']);
$message->setSubject(' OAISIS INTERNSHIP LOGIN FORM');
$message->setBody("
                    <html>
                    <head>
                        <title>Password Change Request</title>
                    </head>
                    <body style='font-family: Arial, sans-serif; margin: 0; padding: 0;'>
                        <div style='background-image: url(\"background.jpg\"); background-size: cover; background-repeat: no-repeat; background-attachment: fixed; background-color: rgba(255, 255, 255, 0.8);'>
                            <div class='container' style='max-width: 600px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                                <h2 style='text-align: center;'>Password Change Request</h2>
                                <p>Hello,</p>
                                <p>You have requested to change your password. Please follow the link below to proceed with the password change:</p>
                                <p><a href='http://localhost/login/change-password.php?reset={$code}'>Reset Password</a></p>
                                <p>If you did not make this request, you can ignore this email.</p>
                                <p>Best regards,</p>
                                <p>HOLIBOI</p>
                            </div>
                            <img src='logo.png' alt='Logo' class='logo' style='display: block; margin: 20px auto; width: 150px; opacity: 0.2;'>
                        </div>
                    </body>
                    </html>
                ", 'text/html');




// Send the email
$result = $mailer->send($message);
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";        
            $msg = "<div class='alert alert-info'>We've send a verification link on your email address.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>$email - This email address do not found.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <title>Login Form - Brave Coder</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords"
        content="Login Form" />
    <!-- //Meta tag Keywords -->

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--/Style-CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <!--//Style-CSS -->

    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>

</head>

<body>

    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                <div class="holiboi">Holiboi</div>
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/image3.svg" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2 style="font-size:20px;">HOLIBOI PASSWORD RECOVERING </h2>
                        <p>Input Your Registered Emai And Recovering Link Will Be Sent To Your Email,Follow The Process And Get Your Account Back</p>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                            <button name="submit" class="btn" type="submit">Send Reset Link</button>
                        </form>
                        <div class="social-icons">
                            <p>Back to! <a href="index.php">Login</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //form -->
        </div>
    </section>
    <!-- //form section start -->

    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>

</body>

</html>
