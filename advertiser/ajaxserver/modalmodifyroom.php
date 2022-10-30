<?php
include('../../config.php');
session_start();
$email = 'amirulasrix@gmail.com';
$_SESSION['useremail'] = $email;
if (isset($_GET['room']) && isset($_SESSION['useremail'])) {
    $roomsqlstmt = $conn->prepare("SELECT `adname`, `addesc`, `adlocation`, `adprice`, `pricetype` FROM `advertisement` WHERE `users` = ? AND `adid` = ?");
    $roomsqlstmt->bind_param("si", $_SESSION['useremail'], $_GET['room']);
    $roomsqlstmt->execute();
    $roomsqlstmt->store_result();
    if ($roomsqlstmt->num_rows < 1) {
        http_response_code(400);
        die();
    }
    $roomsqlstmt->bind_result($adname, $addesc, $adlocation, $adprice, $pricetype);
    $roomsqlstmt->fetch();
    $_SESSION['TEMPADID'] = $_GET['room'];
} else {
    http_response_code(401);
    die();
}
?>

<div class="row">
    <div class="col-sm-8">
        <label class="form-label" for="">Room name</label>
        <input type="text" class="form-control" id="editroomname" value="<?php echo $adname ?>" required><br>
    </div>
    <div class="col-sm-12">
        <label class="form-label" for="">Description</label>
        <textarea type="text" class="form-control" placeholder="10x6FT, 2 Floor, etc..." rows="9" id="editroomdesc" required><?php echo $addesc ?></textarea><br>
    </div>
    <div class="col-sm-12">
        <label class="form-label" for="">Location</label>
        <input type="text" class="form-control" placeholder="Where?" value="<?php echo $adlocation ?>" id="editroomlocation" required><br>
    </div>
    <div class="col-sm-4">
        <label class="form-label" for="">Price</label>
        <input type="number" value="<?php echo $adprice ?>" class="form-control" min="0" placeholder="0.00" step="0.01" pattern="^\d*(\.\d{0,2})?$" id="editroomprice" required>
    </div>
    <div class="col-sm-8">
        <label class="form-label" for="">Price type</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="editpricetype" id="perdayopt" value="perday" <?php if ($pricetype == 'perday') {
                                                                                                                echo 'checked';
                                                                                                            } ?> required>
            <label class="form-check-label" for="perdayopt">Per Day</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="editpricetype" id="permonthopt" value="permonth" <?php if ($pricetype == 'permonth') {
                                                                                                                    echo 'checked';
                                                                                                                } ?>>
            <label class="form-check-label" for="permonthopt">Per Month</label>
        </div>
    </div>
    <div class="col-sm-12"><br>
    <label class="form-label" for="">Attach image (Leave blank to keep old image)</label>
        <div class="input-group mb-3">
            <input type="file" class="form-control" id="editroomimages" multiple>
        </div>
    </div>
</div>