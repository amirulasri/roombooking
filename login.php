<?php
include('config.php');
if(isset($_POST['email']) && isset($_POST['userpassword'])){
    $loginsqlstmt = $conn->prepare("SELECT `email`, `password` FROM `users` WHERE `email` = ?");
    $loginsqlstmt->bind_param("s", $_POST['email']);
    $loginsqlstmt->execute();
    $loginsqlstmt->store_result();
    if($loginsqlstmt->num_rows < 1){
        echo "<script>alert('Incorrect email or password'); window.location='login'</script>";
        die();
    }
    $loginsqlstmt->bind_result($dbemail, $dbpassword);
    $loginsqlstmt->fetch();
    if(password_verify($_POST['userpassword'], $dbpassword)){
        session_start();
        $_SESSION['useremail'] = $dbemail;
        header('location: index');
    }else{
        echo "<script>alert('Incorrect email or password'); window.location='login'</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <title>Room Booking Login</title>
</head>
<body>
    <div class="container-lg">
        <br><br><br>
        <h2>Room Booking</h2>
        <h5>Welcome!. Please login to continue</h5><br>
        <div class="loginbox">
            <form action="" method="POST">
                <label for="" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" required><br>
                <label for="" class="form-label">Password</label>
                <input type="password" name="userpassword" class="form-control" required><br>
                <button type="submit" class="btn btn-primary" style="float: right;">Login</button>
                <button type="button" class="btn btn-secondary" style="float: right; margin-right: 5px;" onclick="window.location='signup'">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>