<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../config.php');
session_start();
$_SESSION['useremail'] = 'amirulasrix@gmail.com';
?>

<h2 style="padding-left: 30px; padding-top: 30px;">Advertisement</h2>
<p style="padding-left: 30px;">Manage your room/hall advertisement easily</p>
<div class="row" style="padding-left: 30px; padding-top: 30px;">
    <div class="col-sm-8">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th colspan="5">Rooms / Halls <button class="btn btn-primary btn-sm" style="float: right;" data-bs-toggle="modal" data-bs-target="#modaladdad">New Ad</button></th>
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
                                <button type="button" class="btn btn-primary btn-sm" onclick="loadmodaleditroom('<?php echo $addata[0] ?>')" data-bs-toggle="modal" data-bs-target="#modaleditad">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                    </svg>
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

<!-- Modal add room -->
<div class="modal fade" id="modaladdad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add new Rooms / Hall</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addroomform" onsubmit="return addroom()">
                    <div class="row">
                        <div class="col-sm-8">
                            <label class="form-label" for="">Room name</label>
                            <input type="text" class="form-control" id="addroomname" required><br>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="">Description</label>
                            <textarea type="text" class="form-control" placeholder="10x6FT, 2 Floor, etc..." rows="9" id="addroomdesc" required></textarea><br>
                        </div>
                        <div class="col-sm-12">
                            <label class="form-label" for="">Location</label>
                            <input type="text" class="form-control" placeholder="Where?" id="addroomlocation" required><br>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="">Price</label>
                            <input type="number" class="form-control" min="0" placeholder="0.00" step="0.01" pattern="^\d*(\.\d{0,2})?$" id="addroomprice" required>
                        </div>
                        <div class="col-sm-8">
                            <label class="form-label" for="">Price type</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="addpricetype" id="perdayopt" value="perday" required>
                                <label class="form-check-label" for="perdayopt">Per Day</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="addpricetype" id="permonthopt" value="permonth">
                                <label class="form-check-label" for="permonthopt">Per Month</label>
                            </div>
                        </div>
                        <div class="col-sm-12"><br>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="roomimages" require multiple>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" form="addroomform" value="Add">
            </div>
        </div>
    </div>
</div>

<!-- Modal edit room -->
<div class="modal fade" id="modaleditad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add new Rooms / Hall</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="editroomform" onsubmit="return modifyroom()">
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" form="editroomform" onclick="modifyroom()" value="Save">
            </div>
        </div>
    </div>
</div>