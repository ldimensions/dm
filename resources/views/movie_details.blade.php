@extends('layouts.app')
@section('content')
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="paggination"><a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-grocery-store')}}" class="subcontent2 h21">Grocery</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title">name</span></div>
            <div class="block2">
                <div class="gro_title toparea space">
                    <table class="fullWidth">
                        <tr>
                        <td><h1 class="titleblock">Name</h1></td>
                        </tr>
                        <tr>
                            <td><div class="titleblock white smaextra">address1</div></td>
                        </tr>
                        <tr>
                            <td><a href="" class="titleblock white smaextra extra">phone1</a></td>
                        </tr>
                        <tr>
                            <td class="smaextra">Working Time : </td>
                        </tr>                         
                    </table> 
                </div>
                <div class="content">
                    <table class="fullWidth">
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="description" style="overflow: hidden;">description</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Website</td>
                        </tr>
                        <tr>
                            <td colspan="2"><a href="" target="_blank"><h2 class="h21" >website</h2></a></td>
                        </tr> 
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Located In</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h3>city</h3></td>
                        </tr>
                        
                    </table>
                    <div class="suggestionblock">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
                    </div>                                         
                </div>
            </div>
            <div class="block22">
            <div class="white_t1 space">
                <h2 class="titleh2 graycolor1">Location</h2>
                <a href="" title="" target="_blank" class="mapicon12"><img src="{{ URL::to('/') }}/image/map1.svg" alt=""/></a>
                </div>
                <div id="map" class="map"></div>
            </div>
            <div class="blockk1">
                <div class="block23">
                    <div class="white_Photo space"><h2 class="titleh2 graycolor">Photos</h2></div>
                </div>
                <div class="block231">
                    <div class="topdetail slideshow-container">
                        <ul id="lightSlider">
                                <li data-thumb="{{ URL::to('/') }}/image/shadow_bottom.gif">
                                    <img src="" alt="" style="width:100%;height:100%" class="bottomarea">
                                </li>
                        </ul>            
                    </div>        
                </div>
            </div>    
            <div class="row" id="related"></div>
        </div>
        <div class="col-md-3 rightcontainer nopadding">
        <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="{{ URL::to('/') }}/image/sideBanner.svg"/></div>
        </div>
    </div>
</div>
  

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titleh2 " id="exampleModalLabel">Suggest an edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" id="formGrpErrName">
                    <label id="name1" class="col-form-label labelfont">Name:</label>
                    <input type="text" class="form-control nup" id="name" name="name" maxLength="40">
                    <div id="nameError"></div>
                </div>
                <div class="form-group">
                    <label id="email1" class="col-form-label labelfont">Email:</label>
                    <input type="text" class="form-control nup" id="email" name="email" maxLength="50">
                    <div id="emailError"></div>
                </div>   
                <div class="form-group">
                    <label id="phone1" class="col-form-label labelfont">Phone:</label>
                    <input type="text" class="form-control nup" id="phone" name="phone" maxLength="20">
                </div>                       
                <div class="form-group" id="formGrpErrSuggession">
                    <label id="suggession1" class="col-form-label labelfont">Suggestion:</label>
                    <textarea class="form-control nup" id="suggession" name="suggession"></textarea>
                    <div id="sugessionError"></div>                        
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control nup" id="type" name="type" value="1">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="suggessionBtn">Submit</button>
            </div>            
        </div>
    </div>
</div>
<div class="loading-overlay">
    <div class="spin-loader"></div>
</div>
<script src="{{ asset('js/lightslider.js') }}"></script>
<script>

 
    /*---------- Image Slider ----------*/
    $('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        slideMargin: 0,
        thumbItem: 9
    });
    
    /*---------- Image Slider End----------*/

    document.querySelector('#readMore').addEventListener('click', function() {
        document.querySelector('#description').style.height= 'auto';
        this.style.display= 'none';
    });      
    
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQJp0CkLijcKXd44Pyn6QWX0Da0PwPKtc&callback=initMap">
</script>
@endsection