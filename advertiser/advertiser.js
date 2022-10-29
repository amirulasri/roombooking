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
            if(jsondata["success"]){
                $('#modaladdad').modal('hide');
                alertmessage('Successfully add room (ad published)');
            }else{
                alertmessage('ERROR: ' + jsondata['desc']);
            }
        }
    });

    return false;
}