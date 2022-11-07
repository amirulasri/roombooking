<?php
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

function delete_files($target)
{
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK);

        foreach ($files as $file) {
            delete_files($file);
        }

        rmdir($target);
    } elseif (is_file($target)) {
        unlink($target);
    }
}

if (isset($_SESSION['useremail']) && isset($_SESSION['TEMPADID']) && isset($_POST['roomname']) && isset($_POST['roomdesc']) && isset($_POST['roomlocation']) && isset($_POST['roomprice']) && isset($_POST['pricetype'])) {
    //PREPARE SQL FOR MODIFY ROOM
    $roomsqlstmt = $conn->prepare("UPDATE `advertisement` SET `adname`=?,`addesc`=?,`adlocation`=?,`adprice`=?,`pricetype`=? WHERE `users`=? AND `adid`=?");
    $roomsqlstmt->bind_param("sssdssi", $_POST['roomname'], $_POST['roomdesc'], $_POST['roomlocation'], $_POST['roomprice'], $_POST['pricetype'], $_SESSION['useremail'], $_SESSION['TEMPADID']);
    $roomsqlstmt->execute();

    try {
        if (isset($_FILES["roomimages"]) && !empty($_FILES["roomimages"])) {
            $totalfiles = count($_FILES['roomimages']['name']);
        } else {
            $totalfiles = 0;
        }
    } catch (\Throwable $th) {
        $totalfiles = 0;
    }

    if ($totalfiles > 0) {
        try {
            //IF OLD EXISTS, DELETE IT
            try {
                $pathfile = '../../roomimages/' . $_SESSION['TEMPADID'] . '/';
                if (file_exists($pathfile)) {
                    delete_files($pathfile);
                }
            } catch (Exception $e) {
                echo json_encode(array("success" => false, "desc" => 'Failed to delete old images, process aborted'));
                die();
            }

            //CREATE DIRECTORY
            if (!file_exists('../../roomimages')) {
                mkdir('../../roomimages', 0777, true);
            }
            if (!file_exists('../../roomimages/' . $_SESSION['TEMPADID'])) {
                mkdir('../../roomimages/' . $_SESSION['TEMPADID'], 0777, true);
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
                    $newFilePath = "../../roomimages/" . $_SESSION['TEMPADID'] . '/' . $_FILES['roomimages']['name'][$i];
                    //Upload the file into the temp dir
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    } else {
                        echo json_encode(array("success" => false, "desc" => 'Images upload error, but new data updated successfully'));
                        die();
                    }
                }
            }

            echo json_encode(array("success" => true));
            die();
        } catch (\Throwable $th) {
            echo json_encode(array("success" => false, "desc" => 'Images upload error, but new data updated successfully'));
            die();
        }
    }
    echo json_encode(array("success" => true));
    die();
} else {
    echo json_encode(array("success" => false, "desc" => 'No Post DATA'));
}
