<?php
include('config.php');
if(isset($_POST['fullname']) && isset($_POST['phoneno']) && isset($_POST['email']) && isset($_POST['userpassword'])){
    $registersqlstmt = $conn->prepare("INSERT INTO `users`(`email`, `password`, `name`, `phoneno`, `joineddate`) VALUES (?,?,?,?,?)");
    $registersqlstmt->bind_param("sssss", $_POST['email'], $securedpassword, $_POST['fullname'], $_POST['phoneno'], $joineddate);
    $securedpassword = password_hash($_POST['userpassword'], PASSWORD_DEFAULT);
    $joineddate = date('Y-m-d');
    $result = $registersqlstmt->execute();
    if($result) {
        echo "<script>alert('Successfully registered'); window.location='login'</script>";
    }else{
        echo "<script>alert('Failed to register'); window.location='login'</script>";
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
        <h5>Welcome!. Enter all the required field to register</h5><br>
        <div class="regbox">
            <form action="" method="POST">
                <label for="" class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-control"><br>
                <label for="" class="form-label">Phone number</label>
                <input type="text" name="phoneno" class="form-control"><br>
                <label for="" class="form-label">Email</label>
                <input type="text" name="email" class="form-control"><br>
                <label for="" class="form-label">Password</label>
                <input type="password" name="userpassword" class="form-control"><br>
                <button class="btn btn-primary" style="float: right;" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>