<?php $__env->startSection('content'); ?>
<div class="mcontainer">
    <div class="maincontainer">
        <div class="leftcontainer">
            <div class="col-md-12 searchbar hiddepadding">
                <a href="#" class="selocation"></a>
                <form>
                    <input name="Location" type="text" class="text locationimage" id="Location" placeholder="Location" readonly="readonly" >
                    <select name="Categories" class="select" id="Categories">
                        <option>All</option>
                        <option>Groceries</option>
                    </select>
                    <input type="text" id="Keywords" name="Keywords" placeholder="Keywords">
                    <a href="#" class="search">Search</a>
                </form>
            </div>
            <div class="col-md-4 block3">
                <div class="gro_block3">Top 3 Groceries</div>
                    <?php $__currentLoopData = $grocery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="main_block">
                            <a href="../<?php echo e(config('app.defaultBaseURL.grocery-store-details')); ?>/<?php echo e($rel['urlName']); ?>" class="block_txtblock"><?php echo e($rel['name']); ?></a>                                                          
                            <a href="https://www.google.com/maps/dir/<?php echo e($rel['latitude']); ?>,<?php echo e($rel['longitude']); ?>" target="_blank" class="mapicon1"><img alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" src="image/map.svg" /></a>
                            <div class="block_kmblock">
                            <!-- <?php if(isset($rel['distance']) && $rel['distance']): ?>
                                    <div class="gro_kmblock"><?php echo e($rel['distance']); ?></div>
                                <?php endif; ?> -->  
                                <div class="txtblock"><?php echo e($rel['city']); ?>, <?php echo e($rel['zip']); ?></div>
                                <a href="tel:<?php echo e($rel['phone1']); ?>" class="txtblock1 h21"><?php echo e($rel['phone1']); ?></a>
                            </div>
                            <div class="bottomborder"></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="gro_block3bottom"></div>
                </div>

                <div class="col-md-4 block3">
                    <div class="re_block3">Top 3 Restaurants</div>
                    <?php $__currentLoopData = $restaurants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="main_block">
                            <a href="../<?php echo e(config('app.defaultBaseURL.dallas-indian-restaurant')); ?>/<?php echo e($rel['urlName']); ?>" class="block_txtblock"><?php echo e($rel['name']); ?></a>                                                          
                            <a href="https://www.google.com/maps/dir/<?php echo e($rel['latitude']); ?>,<?php echo e($rel['longitude']); ?>" target="_blank" class="mapicon1"><img alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" src="image/map.svg" /></a>
                            <div class="block_kmblock">
                            <!-- <?php if(isset($rel['distance']) && $rel['distance']): ?>
                                    <div class="re_kmblock"><?php echo e($rel['distance']); ?></div>
                                <?php endif; ?>   -->
                                <div class="txtblock"><?php echo e($rel['city']); ?>, <?php echo e($rel['zip']); ?></div>
                                <a href="tel:<?php echo e($rel['phone1']); ?>" class="txtblock1 h21"><?php echo e($rel['phone1']); ?></a>
                            </div>
                            <div class="bottomborder"></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                
                    <div class="re_block3bottom"></div>
                </div>

                <div class="col-md-4 block3">
                    <div class="reli_block3">Top 3 Religions</div>
                    <?php $__currentLoopData = $religion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="main_block">
                            <?php if($rel['religionName'] == 'Christianity'): ?>
                                <a href="../<?php echo e(config('app.defaultBaseURL.dallas-christian-church')); ?>/<?php echo e($rel['urlName']); ?>" class="block_txtblock"><?php echo e($rel['name']); ?></a>
                            <?php elseif($rel['religionName'] == 'Hinduism'): ?>
                                <a href="../<?php echo e(config('app.defaultBaseURL.dallas-hindu-temple')); ?>/<?php echo e($rel['urlName']); ?>" class="block_txtblock"><?php echo e($rel['name']); ?></a>
                            <?php elseif($rel['religionName'] == 'Judaism'): ?>
                                <a href="../<?php echo e(config('app.defaultBaseURL.dallas-hindu-temple')); ?>/<?php echo e($rel['urlName']); ?>" class="block_txtblock"><?php echo e($rel['name']); ?></a>
                            <?php elseif($rel['religionName'] == 'Buddhism'): ?>
                                <a href="../<?php echo e(config('app.defaultBaseURL.dallas-hindu-temple')); ?>/<?php echo e($rel['urlName']); ?>" class="block_txtblock"><?php echo e($rel['name']); ?></a>
                            <?php elseif($rel['religionName'] == 'Islam'): ?>
                                <a href="../<?php echo e(config('app.defaultBaseURL.dallas-islan-mosque')); ?>/<?php echo e($rel['urlName']); ?>" class="block_txtblock"><?php echo e($rel['name']); ?></a>                                                          
                            <?php endif; ?>
                            <a href="https://www.google.com/maps/dir/<?php echo e($rel['latitude']); ?>,<?php echo e($rel['longitude']); ?>" target="_blank" class="mapicon1"><img alt="<?php echo e($loop->index); ?><?php echo e($rel['name']); ?>" src="image/map.svg" /></a>
                            <div class="block_kmblock">
                                <!-- <?php if(isset($rel['distance']) && $rel['distance']): ?>
                                    <div class="reli_kmblock"><?php echo e($rel['distance']); ?></div>
                                <?php endif; ?>    -->
                                <div class="txtblock"><?php echo e($rel['city']); ?>, <?php echo e($rel['zip']); ?></div>
                                <a href="tel:<?php echo e($rel['phone1']); ?>" class="txtblock1 h21"><?php echo e($rel['phone1']); ?></a>
                            </div>
                            <div class="bottomborder"></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="reli_block3bottom"></div>
                </div>


            </div>
            <div class="col-md-3 rightcontainer nopadding">
            <div class="ad250x250">ADVERTISE HERE</div>
        </div>
    </div>
</div>    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>