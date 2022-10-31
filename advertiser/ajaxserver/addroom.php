<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../../config.php');
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

if (isset($_SESSION['useremail']) && isset($_POST['roomname']) && isset($_POST['roomdesc']) && isset($_POST['roomlocation']) && isset($_POST['roomprice']) && isset($_POST['pricetype'])) {
    //PREPARE SQL FOR ADDING ROOM
    $roomsqlstmt = $conn->prepare("INSERT INTO `advertisement`(`adid`, `adname`, `addesc`, `adlocation`, `adprice`, `pricetype`, `users`) VALUES (NULL,?,?,?,?,?,?)");
    $roomsqlstmt->bind_param("sssdss", $_POST['roomname'], $_POST['roomdesc'], $_POST['roomlocation'], $_POST['roomprice'], $_POST['pricetype'], $_SESSION['useremail']);
    $roomsqlstmt->execute();
    $last_id = $conn->insert_id;

    try {
        //CREATE DIRECTORY (UBUNTU ONLY)
        if (!file_exists('../../roomimages')) {
            mkdir('../../roomimages', 0777, true);
        }
        if (!file_exists('../../roomimages/' . $last_id)) {
            mkdir('../../roomimages/' . $last_id, 0777, true);
        }
        //UPLOAD ALL FILES TO FOLDER
        $totalfiles = count($_FILES['roomimages']['name']);
        // Loop through each file
        for ($i = 0; $i < $totalfiles; $i++) {

            //Get the temp file path
            $tmpFilePath = $_FILES['roomimages']['tmp_name'][$i];

            //Make sure we have a file path
            if ($tmpFilePath != "") {
                //Setup our new file path
                $newFilePath = "../../roomimages/" . $last_id . '/' . $_FILES['roomimages']['name'][$i];
                //Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                } else {
                    $roomsqlstmt = $conn->prepare("DELETE FROM `advertisement` WHERE `adid` = ?");
                    $roomsqlstmt->bind_param("i", $last_id);
                    $roomsqlstmt->execute();
                    echo json_encode(array("success" => false, "desc" => 'Images upload error, changes reverted'));
                    die();
                }
            }
        }
        
        echo json_encode(array("success"=>true));
    } catch (\Throwable $th) {
        $roomsqlstmt = $conn->prepare("DELETE FROM `advertisement` WHERE `adid` = ?");
        $roomsqlstmt->bind_param("i", $last_id);
        $roomsqlstmt->execute();
        echo json_encode(array("success"=>false, "desc" => 'Images upload error, changes reverted'));
    }
} else {
    echo json_encode(array("success"=>false, "desc" => 'No Post DATA'));
}
