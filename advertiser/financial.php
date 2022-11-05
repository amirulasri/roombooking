    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include('../config.php');
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
        header('location: login');
        die();
    }
    ?>

    <h2 style="padding-left: 30px; padding-top: 30px;">Financial</h2>
    <p style="padding-left: 30px;">View all booked room by users</p>
    <div class="row" style="padding-left: 30px; padding-top: 30px;">
        <div class="col-sm-8">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th colspan="5">Rooms / Halls</th>
                    </tr>
                </thead>
                <tbody class="table-primary">
                    <?php
                    $adsqlstmt = $conn->prepare("SELECT `adid`, `adname`, `addesc`, `adlocation`, `adprice`, `pricetype` FROM `advertisement` WHERE `users` = ?");
                    $adsqlstmt->bind_param("s", $_SESSION['useremail']);
                    $adsqlstmt->execute();
                    $adresult = $adsqlstmt->get_result();
                    if ($adresult->num_rows > 0) {
                        while ($addata = $adresult->fetch_array()) {
                            $firstFile = scandir("../roomimages/".$addata[0])[2];
                    ?>
                            <tr>
                                <td class="imgadasizetable"><img class="adfirstimage" src="../roomimages/<?php echo $addata[0].'/'.$firstFile ?>" alt=""></td>
                                <td><?php echo $addata[1] ?></td>
                                <td><?php echo nl2br($addata[2]) ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="dataroomdashboard('<?php echo $addata[0] ?>')">
                                        View
                                    </button>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>