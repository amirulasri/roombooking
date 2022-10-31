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

if (isset($_SESSION['useremail']) && isset($_GET['room'])) {
    $roomsqlstmt = $conn->prepare("DELETE FROM `advertisement` WHERE `users`=? AND `adid`=?");
    $roomsqlstmt->bind_param("si", $_SESSION['useremail'], $_GET['room']);
    $result = $roomsqlstmt->execute();
    if ($result) {
        echo json_encode(array("success" => true));
    }else{
        echo json_encode(array("success" => false, "desc"=>$conn->error));
    }
}else{
    echo json_encode(array("success" => false, "desc" => 'No Post DATA'));
}
