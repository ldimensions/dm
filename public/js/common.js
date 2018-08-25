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

function validate() {
    var errors = true;
    if (document.getElementById("name").value.length === 0) {
        document.getElementById("name").style.border = "1px solid red";
        document.getElementById("name").style.backgroundColor = "#FFCCCC";
        document.getElementById("formGrpErrName").style.color   =   "red";
        document.getElementById("nameError").innerHTML ="Please enter the name";
        errors  =   false;
    }
    if (document.getElementById("suggession").value.length === 0) {
        document.getElementById("suggession").style.border = "1px solid red";
        document.getElementById("suggession").style.backgroundColor = "#FFCCCC";
        document.getElementById("formGrpErrSuggession").style.color   =   "red";
        document.getElementById("sugessionError").innerHTML ="Please enter the suggession";
        errors  =   false;
    }   
    if (errors) {
        var name                        =   document.getElementById("name").value;
        var email                       =   document.getElementById("email").value;
        var phone                       =   document.getElementById("phone").value;
        var suggession                  =   document.getElementById("suggession").value;
        var type                        =   document.getElementById("type").value;        
        var url                         =   window.location.href;
        var token                       =   document.getElementById("token").value;
        $.ajax({
            url: "/suggessionForEdit",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {name: name, email: email, phone: phone, suggession: suggession, url: url, type: type},
            cache: false,
            success: function (response) {
                $('#exampleModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
               },
               error: function (response) {
                $('#exampleModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
               }
        });                              
    }       
}

$( document ).ready(function() {
    $('#suggessionBtn').click(function(){

        var errors = true;
        if (document.getElementById("name").value.length === 0) {
            document.getElementById("name").style.border = "1px solid red";
            document.getElementById("name").style.backgroundColor = "#FFCCCC";
            document.getElementById("formGrpErrName").style.color   =   "red";
            document.getElementById("nameError").innerHTML ="Please enter the name";
            errors  =   false;
        }
        if (document.getElementById("suggession").value.length === 0) {
            document.getElementById("suggession").style.border = "1px solid red";
            document.getElementById("suggession").style.backgroundColor = "#FFCCCC";
            document.getElementById("formGrpErrSuggession").style.color   =   "red";
            document.getElementById("sugessionError").innerHTML ="Please enter the suggession";
            errors  =   false;
        }   
        if (errors) {
            $('.loading-overlay').show();            
            var name                        =   document.getElementById("name").value;
            var email                       =   document.getElementById("email").value;
            var phone                       =   document.getElementById("phone").value;
            var suggession                  =   document.getElementById("suggession").value;
            var type                        =   document.getElementById("type").value;        
            var url                         =   window.location.href;
            var token                       =   document.getElementById("token").value;
            $.ajax({
                url: "/suggessionForEdit",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {name: name, email: email, phone: phone, suggession: suggession, url: url, type: type},
                cache: false,
                success: function (response) {
                    $('#exampleModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    $('.loading-overlay').hide();
                    location.reload();
                   },
                   error: function (response) {
                    $('#exampleModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    $('.loading-overlay').hide();
                    location.reload();
                   }
            });                              
        }  

    });        
});