<?php
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
    
    $roomsqlstmt = $conn->prepare("SELECT `advertisement`.`adname`, `advertisement`.`addesc`, `advertisement`.`adlocation`, `advertisement`.`adprice`, `advertisement`.`pricetype`, `payment`.`payid`, `payment`.`users`, `payment`.`payamount`, `payment`.`countdate`  FROM `advertisement` INNER JOIN `payment` ON `payment`.`adid` = `advertisement`.`adid` WHERE `advertisement`.`adid`= ?");
    $roomsqlstmt->bind_param("i", $_GET['room']);
    $roomsqlstmt->execute();
    $roomresult = $roomsqlstmt->get_result();
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
                    <th colspan="3">Finance</th>
                </tr>
                <tr>
                    <th>Book ID</th>
                    <th>Customer</th>
                    <th>Total Payment</th>
                </tr>
            </thead>
            <tbody class="table-primary">
                <?php
                $totalallpayment = 0;
                while($data = $roomresult->fetch_array()){
                    $totalallpayment = $totalallpayment + $data[7];
                ?>
                <tr>
                    <td><?php echo $data[5] ?></td>
                    <td><?php echo $data[6] ?></td>
                    <td>RM <?php echo $data[7] ?></td>
                </tr>
                <?php } ?>
                <tr class="table-dark">
                    <th colspan="3">TOTAL OVERALL: RM <?php echo $totalallpayment ?></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>