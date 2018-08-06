$(function(){
    $('#searchKeyword').keyup(function()
    {
        var yourInput = $(this).val();
        re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
        var isSplChar = re.test(yourInput);
        if(isSplChar){
            var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
            $(this).val(no_spl_char);
        }
    });
});


if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
} else {
    console.log("Geolocation is not supported by this browser.");
}
function showPosition(position) {
    console.log("Latitude: " + position.coords.latitude +"Longitude: " + position.coords.longitude);
    setCookie(position.coords.latitude, position.coords.longitude);
}
function setCookie(lat, long, value) {
    var d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*1);
    document.cookie = "lat=" + lat + ";path=/;expires=" + d.toGMTString();
    document.cookie = "long=" + long + ";path=/;expires=" + d.toGMTString();
}   