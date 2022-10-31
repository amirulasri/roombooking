<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('config.php');
session_start();
if (isset($_SESSION['useremail'])) {
    $useremail = $_SESSION['useremail'];
    $userdatasql = $conn->prepare("SELECT `email`, `name`, `phoneno`, `joineddate` FROM `users` WHERE `email` = ?");
    $userdatasql->bind_param("s", $useremail);
    $userdatasql->execute();
    $userdatasql->store_result();
    if ($userdatasql->num_rows < 1) {
        header('location: login');
        die();
    }
    $userdatasql->bind_result($dbemail, $dbuserfname, $dbphoneno, $dbjoineddate);
    $userdatasql->fetch();
} else {
    $useremail = "";
}

if (isset($_GET['d'])) {
    $roomsqlstmt = $conn->prepare("SELECT `advertisement`.`adname`, `advertisement`.`addesc`, `advertisement`.`adlocation`, `advertisement`.`adprice`, `advertisement`.`pricetype`, `advertisement`.`users`, `payment`.`payamount`, `payment`.`countdate`  FROM `advertisement` INNER JOIN `payment` ON `payment`.`adid` = `advertisement`.`adid` WHERE `payment`.`payid`= ?");
    $roomsqlstmt->bind_param("i", $_GET['d']);
    $roomsqlstmt->execute();
    $roomsqlstmt->store_result();
    if ($roomsqlstmt->num_rows < 1) {
        header('location: index');
        die();
    }
    $roomsqlstmt->bind_result($adname, $addesc, $adlocation, $adprice, $pricetype, $users, $paymentamount, $paymentcountdate);
    $roomsqlstmt->fetch();
    $_SESSION['TEMPROOMIDBOOK'] = $_GET['d'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="jquery.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="mainsection.js"></script>
    <link rel="stylesheet" href="style.css">
    <title>Room Booking</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand mb-0 h1" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" fill="currentColor" class="bi bi-house-heart" viewBox="0 0 16 16">
                    <path d="M8 6.982C9.664 5.309 13.825 8.236 8 12 2.175 8.236 6.336 5.309 8 6.982Z" />
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.707L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.646a.5.5 0 0 0 .708-.707L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
                </svg>
                Room Booking</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="advertiser">Publish Ad</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>
    <div class="container-xl">
        <div class="row">
            <div class="col-8">
                <h3>Receipt for: <?php echo $adname ?></h3>
                <h6>RM <?php echo $adprice . ' ' . $pricetype ?></h6><br>
                <h4 id="totaldisplay">Total: RM <?php echo $adprice ?></h4>
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th colspan="2">RECEIPT ID: <?php $_GET['d'] ?></th>
                        </tr>
                    </thead>
                    <tbody class="table-success">
                        <tr>
                            <td>Room/Hall</td>
                            <td><?php echo $adname ?></td>
                        </tr>
                        <tr>
                            <td>Price <?php echo $pricetype ?></td>
                            <td>RM <?php echo $adprice ?></td>
                        </tr>
                        <tr>
                            <?php
                            if ($pricetype == 'permonth') {
                            ?>
                                <td>Number of month</td>
                            <?php } else { ?>
                                <td>Number of days</td>
                            <?php } ?>
                            <td><?php echo $paymentcountdate ?></td>
                        </tr>
                        <tr>
                            <td>Total price</td>
                            <td>RM <?php echo $paymentamount ?></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-primary" onclick="window.print()">Print</button>
            </div>
        </div>
    </div><br><br><br><br><br>
    <div class="roombookingfooter" style="position: absolute; bottom: 0;">
        <h5>Room Booking 2022 (&copy; Amirul Asri)</h5>
        <p>Only for education purpose</p>
    </div>

    <!-- Modal Log In request -->
    <div class="modal fade" id="modalloginrequest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">You are not logged in</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please log in before proceed to the booking process. If you dont have an account, create new one!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="window.location='login'">Login</button>
                    <button type="button" class="btn btn-primary" onclick="window.location='signup'">Create Account</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>