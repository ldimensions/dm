<?php $__env->startSection('content'); ?>
<?php use App\Http\Controllers\CommonController;?>
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="paggination"><a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-indian-grocery-store')); ?>" class="subcontent2 h21">Grocery</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span class="title"><?php echo e($grocery['name']); ?></span></div>
            <div class="block2">
                <div class="gro_title toparea space">
                    <table class="fullWidth">
                        <tr>
                        <td><h1 class="titleblock"><?php echo e($grocery['name']); ?></h1></td>
                        </tr>
                        <tr>
                            <td><div class="titleblock white smaextra"><?php echo e($grocery['address1']); ?> <?php echo e($grocery['address2']); ?>, <?php echo e($grocery['city']); ?>, <?php echo e($grocery['state']); ?>, <?php echo e($grocery['zip']); ?></div></td>
                        </tr>
                        <tr>
                            <td><a href="tel:<?php echo e($grocery['phone1']); ?>" class="titleblock white smaextra extra"><?php echo e($grocery['phone1']); ?></a></td>
                        </tr>
                        <?php if($todaysWorkingTime): ?>
                            <tr>
                                <td class="smaextra">Working Time : <?php echo e($todaysWorkingTime); ?></td>
                            </tr>                         
                        <?php endif; ?>
                        <div class="share">
                            <a href="#" class="dropdown" data-toggle="dropdown"><img src="<?php echo e(URL::to('/')); ?>/image/share_icon.svg"/></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Facebook</a></li>
                                <li><a href="#">Google +</a></li>
                                <li><a href="#">Twitter</a></li>
                                <li><a href="#">whatsapp</a></li>
                            </ul>
                        </div>
                    </table> 
                </div>
                <div class="content">
                    <table class="fullWidth">
                        <?php if($grocery['description']): ?> 
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="description" style="overflow: hidden; height: <?php echo e($descriptionHeight); ?>px;"><?php echo nl2br($grocery['description']); ?></div>
                                    <?php if(strlen($grocery['description']) >= '220'): ?> 
                                    <a id="readMore" class="read h21">Read more...</a>
                                    <?php else: ?>
                                        <span id="readMore"></span>
                                    <?php endif; ?> 
                                </td>
                            </tr>
                        <?php endif; ?>   
                        <?php if(isset($grocery['website']) && $grocery['website']): ?>
                            <tr>
                                <td colspan="2" class="smallfont tdtoppadd1">Website</td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="http://<?php echo e($grocery['website']); ?>" target="_blank"><h2 class="h21" ><?php echo e($grocery['website']); ?></h2></a></td>
                            </tr> 
                        <?php endif; ?> 
                        <?php if($grocery['ethnicName']): ?> 
                            <tr>
                                <td colspan="2" class="smallfont tdtoppadd1 topspace">Ethnicity</td>
                            </tr> 
                            <tr>
                                <td colspan="2"><h3><?php echo e($grocery['ethnicName']); ?></h3></td>
                            </tr>                
                        <?php endif; ?>            
                        <tr>
                            <td colspan="2" class="smallfont tdtoppadd1">Located In</td>
                        </tr>
                        <tr>
                            <td colspan="2"><h3><?php echo e($grocery['city']); ?></h3></td>
                        </tr>
                        <?php if(isset($distance) && $distance): ?>
                            <tr>
                                <td colspan="2" class="smallfont tdtoppadd1">Distance</td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo e($distance); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                    <?php if($workingTimes): ?>
                        <?php $__currentLoopData = $workingTimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wtKey => $wtArr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($wtKey == "default"): ?>
                                <table>
                                    <tr>
                                        <td colspan="3" class="smallfont tdtoppadd1">Working Time</td>
                                    </tr>
                                    <?php $__currentLoopData = $wtArr[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wtArrKey => $wtRs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if( !empty ( $wtRs ) ): ?>
                                        <tr>
                                            <?php if( $wtArrKey == $today ): ?>
                                                <td class="activeweekdays daysWith"><?php echo e(CommonController::getDaysShort($wtArrKey)); ?></td>
                                            <?php else: ?>
                                                <td class="inactiveweekdays daysWith"><?php echo e(CommonController::getDaysShort($wtArrKey)); ?></td>
                                            <?php endif; ?>    
                                            <?php $__currentLoopData = $wtRs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $wt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__currentLoopData = $wt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wtTimeKey => $wtTime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if( $wtArrKey == $today ): ?>
                                                        <td class="activeweekdays"><?php echo e($wtTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>&nbsp;-&nbsp;<?php endif; ?></td>
                                                    <?php else: ?>
                                                        <td class="inactiveweekdays"><?php echo e($wtTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>&nbsp;-&nbsp;<?php endif; ?></td>
                                                    <?php endif; ?>                                              
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                        
                                        <?php endif; ?>                           
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                                    
                                </table>   
                            <?php endif; ?>                   
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                    <?php endif; ?>
                    <div class="suggestionblock">
                        <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
                    </div>                                         
                </div>
            </div>
            <div class="block22">
            <div class="white_t1 space">
                <h2 class="titleh2 graycolor1"><?php echo e($grocery['name']); ?> Location</h2>
                <a href="https://www.google.com/maps/dir//<?php echo e($grocery['urlName']); ?>/%40<?php echo e($grocery['latitude']); ?>,<?php echo e($grocery['longitude']); ?>,12z" title="<?php echo e($grocery['name']); ?>" target="_blank" class="mapicon12"><img src="<?php echo e(URL::to('/')); ?>/image/map1.svg" alt="<?php echo e($grocery['name']); ?>"/></a>
                </div>
                <div id="map" class="map"></div>
            </div>
            <?php if($photos): ?>
            <div class="blockk1">
                <div class="block23">
                    <div class="white_Photo space"><h2 class="titleh2 graycolor"><?php echo e($grocery['name']); ?> Photos</h2></div>
                </div>
                <div class="block231">
                    <div class="topdetail slideshow-container">
                        <ul id="lightSlider">
                            <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li data-thumb="<?php echo e(URL::to('/')); ?>/image/shadow_bottom.gif">
                                    <img src="<?php echo e(URL::to('/')); ?>/image/grocery/<?php echo e($grocery['id']); ?>/<?php echo e($photo['photoName']); ?>" alt="<?php echo e($loop->index); ?><?php echo e($grocery['name']); ?>" style="width:100%;height:100%" class="bottomarea">
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>            
                    </div>        
                </div>
            </div>    
            <?php endif; ?>
            <div class="row" id="related"></div>
        </div>
        <div class="col-md-3 rightcontainer nopadding">
        <div class="ad250x250"><img alt="ad"  width="100%" height="100%" src="<?php echo e(URL::to('/')); ?>/image/sideBanner.svg"/></div>
        </div>
    </div>
</div>
  

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
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
<script src="<?php echo e(asset('js/lightslider.js')); ?>"></script>
<script>

    /*---------- Google Map ----------*/
    
    function initMap() {
        var lat = parseFloat("<?php echo e($grocery['latitude']); ?>");
        var long = parseFloat("<?php echo e($grocery['longitude']); ?>");
        console.log(lat+'#'+long);
        var label = "<?php echo e($grocery['name']); ?>";
        var myLatLng = {lat: lat, lng: long};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 11,
            center: myLatLng
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: label
        });
    }
    /*---------- Google Map End----------*/
    /*---------- Image Slider ----------*/
    $('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        slideMargin: 0,
        thumbItem: 9
    });
    
    /*---------- Image Slider End----------*/
    $( document ).ready(function() {
        $.get("<?php echo URL::to('/');?>/grocery-related/<?php echo $grocery['ethnicId'];?>/<?php echo $grocery['id'];?>", function(data, status){
            if(status=="success"){
                document.getElementById("related").innerHTML = data;
            }
        });
    });

    document.querySelector('#readMore').addEventListener('click', function() {
        document.querySelector('#description').style.height= 'auto';
        this.style.display= 'none';
    });      
    
</script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQJp0CkLijcKXd44Pyn6QWX0Da0PwPKtc&callback=initMap">
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>