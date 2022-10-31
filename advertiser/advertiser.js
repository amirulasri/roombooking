function alertmessage(msgtext) {
    document.getElementById("alertmsg").innerHTML = msgtext;
    const toastLive = document.getElementById('liveToast')
    const toast = new bootstrap.Toast(toastLive)
    toast.show()
}

function navactivechange(idlink) {
    var elem = document.getElementsByClassName('nav-link');
    for (var i = 0; i < elem.length; i++) {
        elem[i].classList.remove("active");
    }
    document.getElementById(idlink).className += " active";
}

function dashboardpage() {
    var xmlhttp = new XMLHttpRequest();
    navactivechange('linkdashboard');
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pagecontent").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "dashboard.php", true);
    xmlhttp.send();
}

function financialpage() {
    var xmlhttp = new XMLHttpRequest();
    navactivechange('linkfinancial');
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pagecontent").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "financial.php", true);
    xmlhttp.send();
}

function advertisementpage() {
    document.getElementById("pagecontent").innerHTML = '<div class="spinner-border m-5" role="status"><span class="visually-hidden">Loading...</span></div>';
    var xmlhttp = new XMLHttpRequest();
    navactivechange('linkadvertise');
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pagecontent").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "advertise.php", true);
    xmlhttp.send();
}

function addroom() {
    var roomname = document.getElementById("addroomname").value;
    var roomdesc = document.getElementById("addroomdesc").value;
    var roomlocation = document.getElementById("addroomlocation").value;
    var roomprice = document.getElementById("addroomprice").value;
    var pricetype = document.querySelector('input[name="addpricetype"]:checked').value;
    var roomimages = document.getElementById("roomimages").files;

    const formData = new FormData();
    formData.append('roomname', roomname);
    formData.append('roomdesc', roomdesc);
    formData.append('roomlocation', roomlocation);
    formData.append('roomprice', roomprice);
    formData.append('pricetype', pricetype);
    for (let i = 0; i < roomimages.length; i++) {
        const eachfile = roomimages[i];
        formData.append('roomimages[]', eachfile);
    }

    $.ajax({
        url: 'ajaxserver/addroom.php',
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function (data) {
            var jsondata = JSON.parse(data);
            if (jsondata["success"]) {
                $('#modaladdad').modal('hide');
                alertmessage('Successfully add room (ad published)');
                setTimeout(advertisementpage, 1000);
            } else {
                alertmessage('ERROR: ' + jsondata['desc']);
            }
        }
    });

    return false;
}

function loadmodaleditroom(roomid) {
    document.getElementById("editroomform").innerHTML = '<div class="spinner-border m-5" role="status"><span class="visually-hidden">Loading...</span></div>';
    var xmlhttp = new XMLHttpRequest();
    navactivechange('linkadvertise');
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("editroomform").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "ajaxserver/modalmodifyroom.php?room=" + roomid, true);
    xmlhttp.send();
}

function modifyroom() {
    var roomname = document.getElementById("editroomname").value;
    var roomdesc = document.getElementById("editroomdesc").value;
    var roomlocation = document.getElementById("editroomlocation").value;
    var roomprice = document.getElementById("editroomprice").value;
    var pricetype = document.querySelector('input[name="editpricetype"]:checked').value;
    var roomimages = document.getElementById("editroomimages").files;

    const formData = new FormData();
    formData.append('roomname', roomname);
    formData.append('roomdesc', roomdesc);
    formData.append('roomlocation', roomlocation);
    formData.append('roomprice', roomprice);
    formData.append('pricetype', pricetype);
    if (roomimages.length > 0) {
        for (let i = 0; i < roomimages.length; i++) {
            const eachfile = roomimages[i];
            formData.append('roomimages[]', eachfile);
        }
    } else {
        console.log(roomimages);
        formData.append('roomimages[]', '');
    }

    $.ajax({
        url: 'ajaxserver/modifyroom.php',
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function (data) {
            var jsondata = JSON.parse(data);
            if (jsondata["success"]) {
                $('#modaleditad').modal('hide');
                alertmessage('Successfully modify room (ad updated)');
                setTimeout(advertisementpage, 1000);
            } else {
                alertmessage('ERROR: ' + jsondata['desc']);
            }
        }
    });

    return false;
}

function dataroomdashboard(roomid) {
    document.getElementById("pagecontent").innerHTML = '<div class="spinner-border m-5" role="status"><span class="visually-hidden">Loading...</span></div>';
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pagecontent").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "dataeachroom.php?room="+roomid, true);
    xmlhttp.send();
}

function deleteroom(roomid) {
    $.ajax({
        url: 'ajaxserver/deleteroom.php?room='+roomid,
        type: 'GET',
        success: function (data) {
            var jsondata = JSON.parse(data);
            if (jsondata["success"]) {
                alertmessage('Successfully delete room (ad removed)');
                setTimeout(advertisementpage, 1000);
            } else {
                alertmessage('ERROR: ' + jsondata['desc']);
            }
        }
    });
}

function searchroom() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchroomipt");
    filter = input.value.toUpperCase();
    table = document.getElementById("roomtable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[1];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }       
    }
  }