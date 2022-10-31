function changeroomimage(imagename) {
    document.getElementById("roommainimage").setAttribute("src", 'roomimages/' + imagename);
}

function calcprice(price){
    var numberselected = document.getElementById("countdate").value;
    var result = price * numberselected;
    document.getElementById("totaldisplay").innerHTML = "Total: RM " + result;
}