<?php use App\Http\Controllers\CommonController;?>
<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <meta name="copyright" content="dallasindianportal.com" />
        <meta name="author" content="dallasindianportal.com" />
        <meta name="allow-search" content="yes" />
        <meta name="revisit-after" content="daily" />
        <meta name="robots" content="index,follow" />
        <?php echo SEO::generate(true); ?>


        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <!-- Scripts -->
        <script src="<?php echo e(asset('js/app.js')); ?>?version=6.5" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet"> -->
        <link href="<?php echo e(asset('css/style.css')); ?>?version=6.5" rel="stylesheet">
        <link href="<?php echo e(asset('css/lightslider.css')); ?>?version=6.5" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="headerstrip"></div>
                <div class="headerh nopadding">
                    <div class="headercontainer">
                        <div class="header"> 
                            <?php if( Auth::guest() ): ?>
                                <!-- <a href="<?php echo e(url('/login')); ?>"  class="signinbutton">Login</a> 
                                <a href="<?php echo e(url('/register')); ?>"  class="signinbutton">Sign Up</a>                             -->
                            <?php else: ?>
                                <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"  class="signinbutton">Logout</a> 
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo e(csrf_field()); ?></form>                            
                            <?php endif; ?>                         
                            <!-- <a href="#"  class="location"><img alt="<?php echo e(config('app.siteId')); ?>" src="<?php echo e(URL::to('/')); ?>/image/location.svg" />Dallas</a> --> 
                            <a href="<?php echo e(url('/')); ?>"><img alt="DallasIndianPortal" class="hDlOGO" src="<?php echo e(URL::to('/')); ?>/image/dallasLogo.svg"/></a>
                            <div class="headerad"><img alt="ad"  width="100%" height="100%" src="<?php echo e(URL::to('/')); ?>/image/topBanner.svg"/></div>

                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>                        
                                </button>
                                
                        </div>
                    </div>    
                    <div class="menumain" id="myNavbar">
                        <div class="menu" > 
                            <a href="<?php echo e(url('/')); ?>" class="<?php echo e((CommonController::activeMenu('home')) ? 'activemenu' : 'inactivemenu'); ?>">Home</a> 
                            <a href="<?php echo e(route('grocery')); ?>" class="<?php echo e((CommonController::activeMenu('grocery')) ? 'activemenu' : 'inactivemenu'); ?>">Groceries</a>                         
                            <a href="<?php echo e(route('restaurant')); ?>" class="<?php echo e((CommonController::activeMenu('restaurant')) ? 'activemenu' : 'inactivemenu'); ?>">Restaurants </a> 
                            <a href="<?php echo e(route('religion')); ?>" class="<?php echo e((CommonController::activeMenu('religion')) ? 'activemenu' : 'inactivemenu'); ?>">Religions </a> 
                            <!-- <a href="#" class="<?php echo e((CommonController::activeMenu('travels')) ? 'activemenu' : 'inactivemenu'); ?>">Travels </a> 
                            <a href="#" class="<?php echo e((CommonController::activeMenu('')) ? 'activemenu' : 'inactivemenu'); ?>">Auto </a>     
                            <a href="#" class="<?php echo e((CommonController::activeMenu('')) ? 'activemenu' : 'inactivemenu'); ?>">Events </a>
                            <a href="<?php echo e(route('movies')); ?>" class="<?php echo e((CommonController::activeMenu('movies')) ? 'activemenu' : 'inactivemenu'); ?>" class="<?php echo e((CommonController::activeMenu('')) ? 'activemenu' : 'inactivemenu'); ?>">Movies </a>-->                    
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
         <div class="footerh nopadding"> Copyright 2018 LDimensions. All rights reserved.</div>

         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
         <script src="<?php echo e(asset('js/common.js')); ?>" defer="defer"></script> 

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-126021205-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-126021205-1');
        </script>

    </body>
</html>