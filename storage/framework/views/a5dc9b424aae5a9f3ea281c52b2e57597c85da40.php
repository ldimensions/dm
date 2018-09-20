 
<?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(isset($type) && $type == 'grocery' && $key == 0): ?>
        <div class="related title">Related Groceries</div>
    <?php elseif(isset($type) && $type == 'restaurant' && $key == 0): ?>
        <div class="related title">Related Restaurants</div>
    <?php elseif(isset($type) && $type == 'religion' && $key == 0): ?>
        <div class="related title">Related Religions</div>
    <?php endif; ?> 
    <div class="col-md-12 block1">
        <?php if(isset($type) && $type == 'grocery'): ?>
            <div class="smallImage">
                <?php if(isset($rel['photoName']) && $rel['photoName']): ?>
                    <img src="<?php echo e(URL::to('/')); ?>/image/grocery/<?php echo e($rel['id']); ?>/<?php echo e($rel['photoName']); ?>" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%">
                <?php else: ?>
                    <img src="<?php echo e(URL::to('/')); ?>/image/noimage.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%">
                <?php endif; ?>  
            </div> 
            <a href="../<?php echo e(config('app.defaultBaseURL.grocery-store-details')); ?>/<?php echo e($rel['urlName']); ?>" title="<?php echo e($rel['name']); ?>" ><h3 class="content1 colorh1"><?php echo e($rel['name']); ?></h3></a>
            <?php if($rel['latitude'] && $rel['longitude']): ?>
                <a href="https://www.google.com/maps/dir/<?php echo e($rel['latitude']); ?>,<?php echo e($rel['longitude']); ?>" target="_blank" class="mapicon"><img src="<?php echo e(URL::to('/')); ?>/image/map1.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>"/></a>
            <?php endif; ?>  
            <div class="content2"><?php echo e($rel['address1']); ?> <?php echo e($rel['address2']); ?>, <?php echo e($rel['city']); ?>, <?php echo e($rel['state']); ?> <?php echo e($rel['zip']); ?></div>
            <a href="tel:<?php echo e($rel['phone1']); ?>" class="content3 h21"><?php echo e($rel['phone1']); ?></a>
            <!-- <div class="content2">Closed - Open 7 AM</div> -->
        <?php endif; ?> 

        <?php if(isset($type) && $type == 'restaurant'): ?>
            <div class="smallImage">
                <?php if(isset($rel['photoName']) && $rel['photoName']): ?>
                    <img src="<?php echo e(URL::to('/')); ?>/image/restaurant/<?php echo e($rel['id']); ?>/<?php echo e($rel['photoName']); ?>" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%">
                <?php else: ?>
                    <img src="<?php echo e(URL::to('/')); ?>/image/noimage.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%">
                <?php endif; ?>   
            </div>              
            <a href="../<?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant')); ?>/<?php echo e($rel['urlName']); ?>" title="<?php echo e($rel['name']); ?>" ><h3 class="content1 colorh1"><?php echo e($rel['name']); ?></h3></a>
            <?php if($rel['latitude'] && $rel['longitude']): ?>
                <a href="https://www.google.com/maps/dir/<?php echo e($rel['latitude']); ?>,<?php echo e($rel['longitude']); ?>" title="<?php echo e($rel['name']); ?>" target="_blank" class="mapicon"><img src="<?php echo e(URL::to('/')); ?>/image/map1.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>"/></a>
            <?php endif; ?> 
            <div class="content2"><?php echo e($rel['address1']); ?> <?php echo e($rel['address2']); ?>, <?php echo e($rel['city']); ?>, <?php echo e($rel['state']); ?> <?php echo e($rel['zip']); ?></div>
            <a href="tel:<?php echo e($rel['phone1']); ?>" class="content3 h21"><?php echo e($rel['phone1']); ?></a>           
        <?php endif; ?> 
        
        <?php if(isset($type) && $type == 'religion'): ?>
            <div class="smallImage">
                <?php if(isset($rel['photoName']) && $rel['photoName']): ?>
                    <img src="<?php echo e(URL::to('/')); ?>/image/religion/<?php echo e($rel['id']); ?>/<?php echo e($rel['photoName']); ?>" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%">
                <?php else: ?>
                    <img src="<?php echo e(URL::to('/')); ?>/image/noimage.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%"></div>
                <?php endif; ?> 
            </div>                
                <div  class="content1">
                    <?php if($rel['religionName'] == 'Christianity'): ?>
                        <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-christian-church')); ?>/<?php echo e($rel['urlName']); ?>" title="<?php echo e($rel['name']); ?>" ><h3 class="religionTrim colorh1"><?php echo e($rel['name']); ?><h3></a>
                    <?php elseif($rel['religionName'] == 'Hinduism'): ?>
                    <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-hindu-temple')); ?>/<?php echo e($rel['urlName']); ?>" title="<?php echo e($rel['name']); ?>"><h3 class="religionTrim colorh1"><?php echo e($rel['name']); ?><h3></a>
                    <?php elseif($rel['religionName'] == 'Judaism'): ?>
                        <!-- <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-malayali-temple')); ?>/<?php echo e($rel['urlName']); ?>" ><h3 class="religionTrim colorh1"><?php echo e($rel['name']); ?><h3></a>-->
                    <?php elseif($rel['religionName'] == 'Buddhism'): ?>
                        <!-- <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-malayali-temple')); ?>/<?php echo e($rel['urlName']); ?>" ><h3 class="religionTrim colorh1"><?php echo e($rel['name']); ?><h3></a>-->
                    <?php elseif($rel['religionName'] == 'Islam'): ?>
                    <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-islan-mosque')); ?>/<?php echo e($rel['urlName']); ?>" title="<?php echo e($rel['name']); ?>" ><h3 class="religionTrim colorh1"><?php echo e($rel['name']); ?><h3> </a>                                                       
                    <?php endif; ?>
                </div>    
                <?php if($rel['latitude'] && $rel['longitude']): ?>
                    <a href="https://www.google.com/maps/dir/<?php echo e($rel['latitude']); ?>,<?php echo e($rel['longitude']); ?>" title="<?php echo e($rel['name']); ?>" target="_blank" class="mapicon"><img src="<?php echo e(URL::to('/')); ?>/image/map1.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>"/></a>
                <?php endif; ?> 
                <div class="content2"><?php echo e($rel['address1']); ?> <?php echo e($rel['address2']); ?>, <?php echo e($rel['city']); ?>, <?php echo e($rel['state']); ?> <?php echo e($rel['zip']); ?></span> </div>
                <a href="tel:<?php echo e($rel['phone1']); ?>" class="content3 h21"><?php echo e($rel['phone1']); ?></a>              
        <?php endif; ?>    
    </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


