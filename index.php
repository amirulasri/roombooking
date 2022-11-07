<?php
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
                        <a class="nav-link" href="#featured">Featured</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#newest">Newest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#lowerprice">Lower Price</a>
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
    <div class="container-xl" style="max-width: 1600px;">
        <h2 id="featured" style="padding-top: 70px;">Featured</h2><br>
        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel" style="height: 480px; border-radius: 8px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
            <div class="carousel-indicators">
                <?php
                $featuredroomsql = $conn->query("SELECT `adid`, `adname`, `addesc`, `adlocation`, `adprice`, `pricetype`, `users` FROM `advertisement` ORDER BY `adname` ASC LIMIT 8");
                if ($featuredroomsql->num_rows > 0) {
                    $countfeatured = 0;
                    while ($featureddata = $featuredroomsql->fetch_array()) {
                        if ($countfeatured == 0) {
                            $activestate = 'class="active" aria-current="true"';
                        } else {
                            $activestate = "";
                        }
                ?>
                        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="<?php echo $countfeatured; ?>" <?php echo $activestate ?> aria-label="Slide 1"></button>
                <?php
                        $countfeatured++;
                    }
                } ?>
            </div>
            <div class="carousel-inner">
                <?php
                $featuredroomsql = $conn->query("SELECT `adid`, `adname`, `addesc`, `adlocation`, `adprice`, `pricetype`, `users` FROM `advertisement` ORDER BY `adid` ASC LIMIT 8");
                if ($featuredroomsql->num_rows > 0) {
                    $countfeatured = 0;
                    while ($featureddata = $featuredroomsql->fetch_array()) {
                        if ($countfeatured == 0) {
                            $activestate = "active";
                        } else {
                            $activestate = "";
                        }
                        $countfeatured++;

                        $firstFile = scandir("roomimages/" . $featureddata[0])[2];
                ?>
                        <div class="carousel-item <?php echo $activestate ?>" data-bs-interval="5000" style="cursor: pointer;" onclick="window.location='room?d=<?php echo $featureddata[0] ?>'">
                            <img src="roomimages/<?php echo $featureddata[0] ?>/<?php echo $firstFile ?>" class="d-block w-100" alt="..." style="height: 480px; object-fit: cover; border-radius: 9px;">
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?php echo $featureddata[1] ?></h5>
                                <p><?php echo $featureddata[3] ?></p>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <h2 id="newest" style="padding-top: 70px;">Newest</h2><br>
        <div class="row">
            <?php
            $roomsql = $conn->query("SELECT `adid`, `adname`, `addesc`, `adlocation`, `adprice`, `pricetype`, `users` FROM `advertisement` ORDER BY `adid` DESC LIMIT 8");
            if ($roomsql->num_rows > 0) {
                $count = 0;
                while ($data = $roomsql->fetch_array()) {
                    if ($count == 0) {
                        $activestate = "active";
                    } else {
                        $activestate = "";
                    }
                    $count++;

                    $firstFile = scandir("roomimages/" . $data[0])[2];
            ?>
                    <div class="col-sm-2">
                        <div class="catelogbox" onclick="window.location='room?d=<?php echo $data[0] ?>'">
                            <img src="roomimages/<?php echo $data[0] ?>/<?php echo $firstFile ?>" class="d-block w-100" alt="<?php echo $data[1] ?> Image" style="height: 180px; object-fit: cover; border-radius: 9px;">
                            <br>
                            <h5><?php echo $data[1] ?></h5>
                            <p><?php echo $data[3] ?></p>
                            <h6>RM <?php echo $data[4] ?> <?php echo $data[5] ?></h6>
                            <br>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
        <h2 id="lowerprice" style="padding-top: 70px;">Lower price</h2><br>
        <div class="row">
            <?php
            $roomsql = $conn->query("SELECT `adid`, `adname`, `addesc`, `adlocation`, `adprice`, `pricetype`, `users` FROM `advertisement` ORDER BY `adprice` ASC LIMIT 8");
            if ($roomsql->num_rows > 0) {
                $count = 0;
                while ($data = $roomsql->fetch_array()) {
                    if ($count == 0) {
                        $activestate = "active";
                    } else {
                        $activestate = "";
                    }
                    $count++;

                    $firstFile = scandir("roomimages/" . $data[0])[2];
            ?>
                    <div class="col-sm-2">
                        <div class="catelogbox" onclick="window.location='room?d=<?php echo $data[0] ?>'">
                            <img src="roomimages/<?php echo $data[0] ?>/<?php echo $firstFile ?>" class="d-block w-100" alt="<?php echo $data[1] ?> Image" style="height: 180px; object-fit: cover; border-radius: 9px;">
                            <br>
                            <h5><?php echo $data[1] ?></h5>
                            <p><?php echo $data[3] ?></p>
                            <h6>RM <?php echo $data[4] ?> <?php echo $data[5] ?></h6>
                            <br>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
    </div><br><br><br><br><br>
    <div class="roombookingfooter">
        <h5>Room Booking 2022 (&copy; Amirul Asri)</h5>
        <p>Easiest platform for booking a rooms and halls</p>
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