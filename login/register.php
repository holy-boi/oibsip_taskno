<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}

//Load Composer's autoloader
require 'vendor/autoload.php';

include 'config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
    $code = mysqli_real_escape_string($conn, md5(rand()));

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $msg = "<div class='alert alert-danger'>{$email} - This email address has been already exists.</div>";
    } else {
        if ($password === $confirm_password) {
            $sql = "INSERT INTO users (name, email, password, code) VALUES ('{$name}', '{$email}', '{$password}', '{$code}')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "<div style='display: none;'>";
                $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                $transport->setUsername('emeriebow78@gmail.com');
                $transport->setPassword('bmoyqgmodswblekp');

                // Create a Swift Mailer instance
                $mailer = new Swift_Mailer($transport);

                // Create an email message
                $message = new Swift_Message('Subject of the Email');
                $message->setFrom(['emeriebow78@gmail.com' => 'Holiboi Verification']);
                $message->setTo([($email) => 'Recipient Name']);
                $message->setSubject('EMAIL VERIFICATION');
                $message->setBody("
    <html>
    <head>
        <title>Verification Link</title>
    </head>
    <body style='font-family: Arial, sans-serif; margin: 0; padding: 0;'>
                        <div style='background-image: url(\"background.jpg\"); background-size: cover; background-repeat: no-repeat; background-attachment: fixed; background-color: rgba(255, 255, 255, 0.8);'>
                            <div class='container' style='max-width: 600px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                                <h2 style='text-align: center;'>Email Verification Request</h2>
                                <p>Hello Dear,</p>
                                        <p>Here is the verification link below:</p>
        <b><a href='http://localhost/login/?verification={$code}'>  Verify Your Email Here  </a></b>
        <p>If you did not make this request, you can ignore this email.</p>
                                <p>Best regards,</p>
                                <p>HOLIBOI</p>
                            </div>
                            <img src='logo.png' alt='Logo' class='logo' style='display: block; margin: 20px auto; width: 150px; opacity: 0.2;'>

    </body>
    </html>", 'text/html');
                // Send the email
                $result = $mailer->send($message);

                echo 'Message has been sent';
            }
            if ($result) {
                echo "Email sent successfully!";
            } else {
                echo "Failed to send email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <title>Login Form</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Login Form" />
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
                            <img src="images/image2.svg" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>HOLIBOI REGISTRATION FORM</h2>
                        <p>After Seeing Successful Registration MessAge,Check Your Email To Confirm Your Account Before
                            Login</p>

                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="text" class="name" name="name" placeholder="Enter Your Name"
                                value="<?php if (isset($_POST['submit'])) {
                                    echo $name;
                                } ?>" required>
                            <input type="email" class="email" name="email" placeholder="Enter Your Email"
                                value="<?php if (isset($_POST['submit'])) {
                                    echo $email;
                                } ?>" required>
                            <input type="password" class="password" name="password" placeholder="Enter Your Password"
                                required>
                            <input type="password" class="confirm-password" name="confirm-password"
                                placeholder="Enter Your Confirm Password" required>
                            <button name="submit" class="btn" type="submit">Register</button>
                        </form>
                        <div class="social-icons">
                            <p>Have an account! <a href="index.php">Login</a>.</p>
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