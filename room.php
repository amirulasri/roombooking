<?php
include('config.php');
if (isset($_GET['d'])) {
    $roomsqlstmt = $conn->prepare("SELECT `adname`, `addesc`, `adlocation`, `adprice`, `pricetype`, `users` FROM `advertisement` WHERE `adid`= ?");
    $roomsqlstmt->bind_param("i", $_GET['d']);
    $roomsqlstmt->execute();
    $roomsqlstmt->store_result();
    if ($roomsqlstmt->num_rows < 1) {
        header('location: index');
        die();
    }
    $roomsqlstmt->bind_result($adname, $addesc, $adlocation, $adprice, $pricetype, $users);
    $roomsqlstmt->fetch();
    $firstFile = scandir("roomimages/" . $_GET['d'])[2];
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
    <div class="container-xl" style="max-width: 1600px;">
        <div class="row">
            <div class="col-6">
                <img src="roomimages/<?php echo $_GET['d'] ?>/<?php echo $firstFile ?>" class="d-block w-100" alt="<?php echo $adname ?> Image" style="height: 400px; object-fit: cover; border-radius: 9px;">
            </div>
            <div class="col-4">
                <br>
                <h3><?php echo $adname ?></h3><br>
                <p><?php echo nl2br($addesc) ?></p>
                <h5>RM <?php echo $adprice ?> <?php echo $pricetype ?></h5><br>
                <button class="btn btn-primary btn-lg">Book Now</button>
            </div>
        </div>
    </div><br><br><br><br><br>
    <div class="roombookingfooter" style="position: absolute; bottom: 0;">
        <h5>Room Booking 2022 (&copy; Amirul Asri)</h5>
        <p>Only for education purpose</p>
    </div>
</body>

</html>