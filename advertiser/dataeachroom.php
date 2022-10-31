<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../config.php');

session_start();
$email = 'amirulasrix@gmail.com';
$_SESSION['useremail'] = $email;
if (isset($_GET['room']) && isset($_SESSION['useremail'])) {
    $roomsqlstmt2 = $conn->prepare("SELECT `adname`, `addesc`, `adlocation`, `adprice`, `pricetype` FROM `advertisement` WHERE `users` = ? AND `adid` = ?");
    $roomsqlstmt2->bind_param("si", $_SESSION['useremail'], $_GET['room']);
    $roomsqlstmt2->execute();
    $roomsqlstmt2->store_result();
    if ($roomsqlstmt2->num_rows < 1) {
        http_response_code(400);
        die();
    }
    $roomsqlstmt2->bind_result($adname, $addesc, $adlocation, $adprice, $pricetype);
    $roomsqlstmt2->fetch();
    
    $roomsqlstmt = $conn->prepare("SELECT `advertisement`.`adname`, `advertisement`.`addesc`, `advertisement`.`adlocation`, `advertisement`.`adprice`, `advertisement`.`pricetype`, `payment`.`users`, `payment`.`payamount`, `payment`.`countdate`  FROM `advertisement` INNER JOIN `payment` ON `payment`.`adid` = `advertisement`.`adid` WHERE `advertisement`.`adid`= ?");
    $roomsqlstmt->bind_param("i", $_GET['room']);
    $roomsqlstmt->execute();
    $roomresult = $roomsqlstmt->get_result();
    if ($roomresult->num_rows < 1) {
        http_response_code(400);
        die();
    }

    //GET ALL TOTAL
    $totalpaymentroom = $conn->prepare("SELECT SUM(`payamount`) AS `totalpay` FROM `payment` WHERE `users` = ?");
    $totalpaymentroom->bind_param("s", $_SESSION['useremail']);
    $totalpaymentroom->execute();
    $totalpaymentroom->store_result();
    $totalpaymentroom->bind_result($totalallpayment);
    $totalpaymentroom->fetch();
} else {
    http_response_code(401);
    die();
}
?>

<h2 style="padding-left: 30px; padding-top: 30px;">Room/hall: <?php echo $adname ?></h2>
<p style="padding-left: 30px;">View all booked room by users</p>
<div class="row" style="padding-left: 30px; padding-top: 30px;">
    <div class="col-sm-8">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td colspan="2">Finance</td>
                </tr>
            </thead>
            <tbody class="table-primary">
                <?php
                while($data = $roomresult->fetch_array()){
                ?>
                <tr>
                    <td><?php echo $data[5] ?></td>
                    <td>RM <?php echo $data[6] ?></td>
                </tr>
                <?php } ?>
                <tr class="table-dark">
                    <th colspan="2">TOTAL OVERALL: RM <?php echo $totalallpayment ?></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>