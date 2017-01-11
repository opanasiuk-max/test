/**
 * Created by Максим on 30.11.2016.
 */

function showRSS(){

  //  url=$(this).val();
    var urls=$(this).val();
    xhttp = getXMLHttpRequest();
    xhttp.open('GET', urls, false);
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4) {
            if(xhttp.status == 200) {
                alert(xhttp.responseText);
            }
        }
    };//  xhttp.setRequestHeader()
    xhttp.send(null);
    alert(xhttp.responseText);
}
/*

xmlhttp.send(null);*/