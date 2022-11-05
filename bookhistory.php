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
    header('location:index');
    die();
}

$roomsqlstmt = $conn->prepare("SELECT `advertisement`.`adid`, `advertisement`.`adname`, `advertisement`.`addesc`, `advertisement`.`adlocation`, `advertisement`.`adprice`, `advertisement`.`pricetype`, `advertisement`.`users`, `payment`.`payid`, `payment`.`payamount`, `payment`.`countdate`  FROM `advertisement` INNER JOIN `payment` ON `payment`.`adid` = `advertisement`.`adid` WHERE `payment`.`users` = ?");
$roomsqlstmt->bind_param("s", $useremail);
$roomsqlstmt->execute();
$roomresult = $roomsqlstmt->get_result();
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
                    <?php
                    if ($useremail != "") {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="advertiser">Publish Ad</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="bookhistory">Book history</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalprofile">My Profile</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login">Login</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>
    <div class="container-xl">
        <div class="row">
            <div class="col-10">
                <h2>Book history</h2>
                <p>View all previous book receipt here!</p>
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th colspan="4">Book history (<?php echo $roomresult->num_rows ?>)</th>
                        </tr>
                        <tr>
                            <th>Order ID</th>
                            <th>Room</th>
                            <th colspan="2">Total price</th>
                        </tr>
                    </thead>
                    <tbody class="table-warning">
                        <?php
                        if ($roomresult->num_rows > 0) {
                            while ($bookhistorydata = $roomresult->fetch_array()) {
                        ?>
                                <tr>
                                    <td><?php echo $bookhistorydata[7] ?></td>
                                    <td><?php echo $bookhistorydata[1] ?></td>
                                    <td>RM <?php echo $bookhistorydata[8] ?></td>
                                    <td><button class="btn btn-warning btn-sm" onclick="window.location='receipt?d=<?php echo $bookhistorydata[7] ?>'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                                                <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                                                <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                                            </svg>
                                        </button></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
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

    <?php
    if ($useremail != "") {
    ?>
        <!-- Modal My Profile-->
        <div class="modal fade" id="modalprofile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">My Profile</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan="2"><?php echo $dbuserfname ?></th>
                                </tr>
                            </thead>
                            <tbody class="table-primary">
                                <tr>
                                    <td>Name</td>
                                    <td><?php echo $dbuserfname ?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo $dbemail ?></td>
                                </tr>
                                <tr>
                                    <td>Phone no</td>
                                    <td><?php echo $dbphoneno ?></td>
                                </tr>
                                <tr>
                                    <td>Joined Date</td>
                                    <td><?php echo $dbjoineddate ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><button class="btn btn-danger" style="width: 100%;" onclick="window.location='logout'">Log out</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</body>

</html>