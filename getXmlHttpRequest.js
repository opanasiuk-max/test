/**
 * Created by Максим on 30.11.2016.
 */
function getXMLHttpRequest(){
    if (window.XMLHttpRequest)
    {
        try {
            return new XMLHttpRequest();
        }
        catch (e) {console.log(e.message)}

    }
else if (window.ActiveXObject) {
        try {
            return new ActiveXObject('Msxml2.XMLHTTP');
        }
        catch (e) {}
        try {
            return new ActiveXObject('Microsoft.XMLHTTP');
        }
        catch (e) {}
    }
    return null;
}