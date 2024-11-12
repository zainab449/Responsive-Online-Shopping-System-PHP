<?php
@include 'config.php';

if (isset($_POST['submit'])) {

    // Sanitize and escape input values
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($conn, $filter_name);
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $pass = mysqli_real_escape_string($conn, $filter_pass);
    $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
    $cpass = mysqli_real_escape_string($conn, $filter_cpass);
    $user_type = $_POST['user_type']; // Get user type

    // Check if user already exists
    $select_users = mysqli_query($conn, "SELECT * FROM `user` WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'User already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            // Store the plain password (Not recommended for production)
            mysqli_query($conn, "INSERT INTO `user`(name, email, password, user_type) VALUES('$name', '$email', '$pass', '$user_type')") or die('query failed');
            $message[] = 'Registered successfully!';
            header('location:login.php');
            exit; // Ensure no further code execution after redirection
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<section class="form-container">
    <form action="" method="post">
        <h3>Register Now</h3>
        <input type="text" name="name" class="box" placeholder="Enter your username" required>
        <input type="email" name="email" class="box" placeholder="Enter your email" required>
        <input type="password" name="pass" class="box" placeholder="Enter your password" required>
        <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
        <select name="user_type" class="box" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <input type="submit" class="btn" name="submit" value="Register Now">
        <p>Already have an account? <a href="login.php">Login Now</a></p>
    </form>
</section>

</body>
</html>
