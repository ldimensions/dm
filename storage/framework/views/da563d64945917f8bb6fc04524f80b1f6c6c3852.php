<?php $__env->startSection('content'); ?>
<div class="mcontainer">
<div class="maincontainer">
    <div class="leftcontainer">
        <div class="col-md-12 searchbar hiddepadding"> <a href="#" class="selocation"></a>
            <form>
                <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
                <select name="type" class="select" id="type">
                    <option 
                        value="<?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant')); ?>-2"
                        <?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant').'-2' == $type ? 'selected="selected"' : ''); ?>>
                        All
                    </option>
                    <option 
                        value="<?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant')); ?>-2"
                        <?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant').'-2' == $type ? 'selected="selected"' : ''); ?>>
                        Indian
                    </option>
                    <option 
                        value="<?php echo e(config('app.defaultBaseURL.dallas-kerala-restaurant')); ?>-1"
                        <?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant').'-1' == $type ? 'selected="selected"' : ''); ?>>
                        Kerala
                    </option>
                    <option 
                        value="<?php echo e(config('app.defaultBaseURL.dallas-tamil-restaurant')); ?>-3"
                        <?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant').'-3' == $type ? 'selected="selected"' : ''); ?>>
                        Tamil
                    </option>
                </select>               
                <select name="city" class="select" id="city">
                    <option value="all">All</option>
                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option 
                            value="<?php echo e($city['value']); ?>-<?php echo e($city['cityId']); ?>"
                            <?php echo e($city['cityId'] == $cityVal ? 'selected="selected"' : ''); ?>>
                            <?php echo e($city['city']); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="text" id="searchKeyword" value="<?php echo e($keyword); ?>" name="searchKeyword" placeholder="Keywords" class="text1" maxlength="50" pattern="(1[0-2]|0[1-9])\/(1[5-9]|2\d)">
                <a href="JavaScript:void(0)" class="search" onclick="restaurantSearch()">Search</a>
            </form>
        </div>
        <!-- <div class="col-md-12 paggination">
            <div class="count">1 to xx of xx Groceries</div>
            <div class="pagecount">Page: 1 of 1</div>
            <div class="pagecount">&nbsp;</div>
        </div> -->
        <?php if(count($restaurant) == 0): ?>
            <div class="col-md-12 block1">
                Suggestions for improving the results:<br/>
                Try a different location.<br/>
                Check the spelling or try alternate spellings.<br/>
            </div>
        <?php endif; ?>         
        <?php $__currentLoopData = $restaurant; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-12 block1">
                    <div class="smallImage">
                    <?php if(isset($rel['photoName']) && $rel['photoName']): ?>
                        <img src="<?php echo e(URL::to('/')); ?>/image/restaurant/<?php echo e($rel['id']); ?>/<?php echo e($rel['photoName']); ?>" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%"></div>
                    <?php else: ?>
                        <img src="<?php echo e(URL::to('/')); ?>/image/noimage.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" style="width:100%;height:100%"></div>
                    <?php endif; ?>                 
                    <a href="<?php echo e(URL::to('/')); ?>/<?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant')); ?>/<?php echo e($rel['urlName']); ?>" ><h2 class="content1 colorh1"> <?php echo e($rel['name']); ?></h2></a>                                                           
                    <?php if($rel['latitude'] && $rel['longitude']): ?>
                        <a href="https://www.google.com/maps/dir/<?php echo e($rel['latitude']); ?>,<?php echo e($rel['longitude']); ?>" target="_blank" class="mapicon"><img src="<?php echo e(URL::to('/')); ?>/image/map1.svg" alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>"/></a>
                    <?php endif; ?>  
                    <div class="content2"><?php echo e($rel['address1']); ?> <?php echo e($rel['address2']); ?>, <?php echo e($rel['city']); ?>, <?php echo e($rel['state']); ?> <?php echo e($rel['zip']); ?></div>
                    <a href="tel:<?php echo e($rel['phone1']); ?>" class="content3 h21"><?php echo e($rel['phone1']); ?></a>
                    <?php if(isset($rel['distance']) && $rel['distance']): ?>
                        <div class="res_kmblock_list"><?php echo e($rel['distance']); ?></div>
                    <?php endif; ?>                 
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>


    <div class="rightcontainer">
        <div class="ad250x250">ADVERTISE HERE</div>
    </div>
</div>
</div>
    <script>

        function restaurantSearch() {
            var type        =   document.getElementById("type").value;
            var city        =   document.getElementById("city").value;
            var keyword     =   document.getElementById("searchKeyword").value;
            var urlParm     =   '';

            if(city && city != 'all'){
                if(type == "<?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant')); ?>-2"){
                    city        =   "<?php echo e(config('app.defaultBaseURL.indian-restaurant-in')); ?>"+city;                    
                }else if(type == "<?php echo e(config('app.defaultBaseURL.dallas-kerala-restaurant')); ?>-1"){
                    city        =   "<?php echo e(config('app.defaultBaseURL.kerala-restaurant-in')); ?>"+city;                    
                }else if(type == "<?php echo e(config('app.defaultBaseURL.dallas-tamil-restaurant')); ?>-3"){
                    city        =   "<?php echo e(config('app.defaultBaseURL.tamil-restaurant-in')); ?>"+city;                    
                }else{
                    city        =   'all';
                }
            }else{
                city        =   'all';
            }
            
            urlParm = "<?php echo e(URL::to('/')); ?>/restaurant-search/"+type+"/"+city+"/"+keyword;
            window.location.href = urlParm;

        }     
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>