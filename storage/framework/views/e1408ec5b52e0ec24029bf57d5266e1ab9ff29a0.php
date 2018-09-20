<?php $__env->startSection('content'); ?>
<div class="mcontainer">
<div class="maincontainer">
<div class="leftcontainer">
    <div class="paggination">
        <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-indian-religion')); ?>" class="subcontent2 h21">Religions</a>&nbsp;&nbsp;>&nbsp;&nbsp;
        <span class="title">Details</span>
    </div>
    <div class="block2">
        <div class="relig_title toparea space">
            <table class="fullWidth">
            <tr>
                <td><h1 class="religin_txt titleblock"><?php echo e($religion['name']); ?></h1></td>
                </tr> 
            <tr>
                    <td><div class="titleblock smaextra_reli"><?php echo e($religion['address1']); ?> <?php echo e($religion['address2']); ?>, <?php echo e($religion['city']); ?>, <?php echo e($religion['state']); ?>, <?php echo e($religion['zip']); ?></div></td>
                </tr>
            <tr>
                    <td><a href="tel:<?php echo e($religion['phone1']); ?>"  class="titleblock smaextra_reli extra_reli"><?php echo e($religion['phone1']); ?></a></td>
                </tr>  
                <tr>
                    <?php if($todaysMassTime || $todaysConfessionTime || $todaysAdorationTime): ?> 
                        <tr>
                            <td>
                                <?php if($todaysMassTime): ?><div class="titleblock smaextra_reli  smaextra_reli">Mass: <?php echo e($todaysMassTime); ?></div><?php endif; ?>   
                                <?php if($todaysConfessionTime): ?><div class="titleblock smaextra_reli  smaextra_reli">Confession: <?php echo e($todaysConfessionTime); ?></div><?php endif; ?>     
                                <?php if($todaysAdorationTime): ?><div class="titleblock smaextra_reli  smaextra_reli">Adoration:<?php echo e($todaysAdorationTime); ?></div><?php endif; ?>     
                            </td>
                        </tr>   
                    <?php endif; ?>  
                    </td>
                </tr>                
            </table>    
        </div>          
        <div class="content">
            <table class="fullWidth">
            <?php if($religion['description']): ?>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <div id="description" style="overflow: hidden; height: <?php echo e($descriptionHeight); ?>px;"><?php echo nl2br($religion['description']); ?></div>
                         <?php if(strlen($religion['description']) >= '220'): ?> 
                         <a id="readMore" class="read h21">Read more...</a>
                        <?php else: ?>
                            <span id="readMore"></span>
                        <?php endif; ?> 
                    </td>
                </tr>
            <?php endif; ?>  
                <?php if(isset($religion['website']) && $religion['website']): ?>
                    <tr>
                        <td class="smallfont tdtoppadd1">Website:</td>
                    </tr>
                    <tr>
                        <td><a href="http://<?php echo e($religion['website']); ?>" target="_blank"><h2 class="h21"><?php echo e($religion['website']); ?></h2></a></td>
                    </tr>   
                <?php endif; ?>                          
                <tr>
                    <td class="smallfont tdtoppadd1">Located In:</td>
                </tr>
                <tr>
                    <td><h3><?php echo e($religion['city']); ?></h3></td>
                </tr>
                <?php if(isset($distance) && $distance): ?>
                    <tr>
                        <td class="smallfont tdtoppadd1">Distance:</td>
                    </tr>
                    <tr>
                        <td><?php echo e($distance); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if($workingTimes): ?>
                <?php if($religion['religionName'] == 'Christianity'): ?>
                    <?php $__currentLoopData = $workingTimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wtKey => $wtArr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($wtKey == "Mass" && count($wtArr) >0): ?>
                            <table>
                                <tr>
                                    <td colspan="3" class="smallfont tdtoppadd1">Mass:</td>
                                </tr>
                                <?php $__currentLoopData = $wtArr[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wtMassArrKey => $wtMass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if( !empty ( $wtMass ) ): ?>
                                    <tr>
                                        <?php if( $wtMassArrKey == $today ): ?>
                                            <td class="activeweekdays_reli daysWith"><?php echo e($wtMassArrKey); ?></td>
                                        <?php else: ?>
                                            <td class="inactiveweekdays daysWith"><?php echo e($wtMassArrKey); ?></td>
                                        <?php endif; ?>    
                                        <?php $__currentLoopData = $wtMass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $massKey => $mass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $__currentLoopData = $mass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $massTimeKey => $massTime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if( $wtMassArrKey == $today ): ?>
                                                    <td class="activeweekdays_reli"><?php echo e($massTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>,&nbsp;<?php endif; ?></td>
                                                <?php else: ?>
                                                    <td class="inactiveweekdays"><?php echo e($massTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>,&nbsp;<?php endif; ?></td>
                                                <?php endif; ?>                                              
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                    <?php endif; ?>                           
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                            </table>                     
                        <?php elseif($wtKey == "Confession"  && count($wtArr) >0): ?>
                            <table>
                                <tr>
                                    <td colspan="3" class="smallfont tdtoppadd1">Confession:</td>
                                </tr>
                                <?php $__currentLoopData = $wtArr[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wtConfArrKey => $wtConf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if( !empty ( $wtConf ) ): ?>
                                        <tr>
                                            <?php if( $wtConfArrKey == $today ): ?>
                                                <td class="activeweekdays_reli daysWith"><?php echo e($wtConfArrKey); ?></td>
                                            <?php else: ?>
                                                <td class="inactiveweekdays daysWith"><?php echo e($wtConfArrKey); ?></td>
                                            <?php endif; ?>                                             
                                            <?php $__currentLoopData = $wtConf; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $confession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__currentLoopData = $confession; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $confessionTimeKey => $confessionTime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if( $wtConfArrKey == $today ): ?>
                                                        <td class="activeweekdays_reli"><?php echo e($confessionTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>,&nbsp;<?php endif; ?></td>
                                                    <?php else: ?>
                                                        <td class="inactiveweekdays"><?php echo e($confessionTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>,&nbsp;<?php endif; ?></td>
                                                    <?php endif; ?>  
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                    <?php endif; ?>                           
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>                                 
                        <?php elseif($wtKey == "Adoration"  && count($wtArr) >0): ?>
                            <table>
                                <tr>
                                    <td colspan="3" class="smallfont tdtoppadd1">Adoration:</td>
                                </tr>
                                <?php $__currentLoopData = $wtArr[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wtAdoArrKey => $wtAdo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if( !empty ( $wtAdo ) ): ?>
                                        <tr>
                                            <?php if( $wtAdoArrKey == $today ): ?>
                                                <td class="activeweekdays_reli daysWith"><?php echo e($wtAdoArrKey); ?></td>
                                            <?php else: ?>
                                                <td class="inactiveweekdays daysWith"><?php echo e($wtAdoArrKey); ?></td>
                                            <?php endif; ?>                                          
                                            <?php $__currentLoopData = $wtAdo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adoration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__currentLoopData = $adoration; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adorationTimeKey => $adorationTime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if( $wtAdoArrKey == $today ): ?>
                                                        <td class="activeweekdays_reli"><?php echo e($adorationTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>,&nbsp;<?php endif; ?></td>
                                                    <?php else: ?>
                                                        <td class="inactiveweekdays"><?php echo e($adorationTime); ?><?php if($loop->parent->index+1 != $loop->parent->count): ?>,&nbsp;<?php endif; ?></td>
                                                    <?php endif; ?> 
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>                           
                                    <?php endif; ?>                           
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>     
                            </table>                                             
                        <?php endif; ?>                       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                               
                <?php elseif($religion['religionName'] == 'Hinduism'): ?>
                    I have multiple records!
                <?php elseif($religion['religionName'] == 'Islam'): ?>
                    I have multiple records!
                <?php elseif($religion['religionName'] == 'Judaism'): ?>
                    I have multiple records!
                <?php elseif($religion['religionName'] == 'Buddhism'): ?>
                    I have multiple records!                
                <?php else: ?>
                
                <?php endif; ?>
            <?php endif; ?>       

            </table>
            <div class="suggestionblock">
                <a href="#" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" class="subcontent22">Suggest an edit</a>   
            </div>   
        </div>
    </div>
    <div class="block22">
        <div class="white_t space"><h2 class="titleh2 graycolor"><?php echo e($religion['name']); ?> Location</h2></div>
        <div id="map" class="map"></div>
    </div>
    <?php if($photos): ?>
        <div class="blockk1">
        <div class="block23">
            <div class="white_Photo space"><h2 class="titleh2 graycolor"><?php echo e($religion['name']); ?> Photos</h2></div>
        </div>
        <div class="block231">
            <div class="topdetail slideshow-container">
                <ul id="lightSlider">
                <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li data-thumb="<?php echo e(URL::to('/')); ?>/image/shadow_bottom.gif">
                            <img src="<?php echo e(URL::to('/')); ?>/image/religion/<?php echo e($religion['id']); ?>/<?php echo e($photo['photoName']); ?>" alt="<?php echo e($loop->index); ?><?php echo e($religion['name']); ?>" style="width:100%;height:100%" class="bottomarea">
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>            
            </div>        
        </div>
        </div> 
    <?php endif; ?>
    <div class="row" id="related"></div>
</div>
<div class="rightcontainer">
    <div class="ad300x600">ADVERTISE HERE</div>
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
                    <label for="recipient-name" class="col-form-label labelfont">Name:</label>
                    <input type="text" class="form-control nup" id="name" name="name" maxLength="40">
                    <div id="nameError"></div>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label labelfont">Email:</label>
                    <input type="text" class="form-control nup" id="email" name="email" maxLength="50">
                    <div id="emailError"></div>
                </div>   
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label labelfont">Phone:</label>
                    <input type="text" class="form-control nup" id="phone" name="phone" maxLength="20">
                </div>                       
                <div class="form-group" id="formGrpErrSuggession">
                    <label for="message-text" class="col-form-label labelfont">Suggestion:</label>
                    <textarea class="form-control nup" id="suggession" name="suggession"></textarea>
                    <div id="sugessionError"></div>                        
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control nup" id="type" name="type" value="3">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="suggessionBtn">Submit</button>
            </div>            
        </div>
    </div>
</div>
<div class="loading-overlay">
    <div class="spin-loader"></div>
</div>
<script>
    /*---------- Google Map ----------*/
    
    function initMap() {
        var lat = parseFloat("<?php echo e($religion['latitude']); ?>");
        var long = parseFloat("<?php echo e($religion['longitude']); ?>");
        console.log(lat+'#'+long);
        var label = "<?php echo e($religion['name']); ?>";
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
        $.get("<?php echo URL::to('/');?>/religion-related/<?php echo $religion['denominationName'];?>/<?php echo $religion['id'];?>", function(data, status){
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