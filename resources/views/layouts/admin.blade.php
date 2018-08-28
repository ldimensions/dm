<?php use App\Http\Controllers\CommonController;?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ asset('admin/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{ asset('admin/metisMenu/metisMenu.min.css')}}" rel="stylesheet">
        <link href="{{ asset('admin/dist/css/sb-admin-2.css')}}" rel="stylesheet">
        <link href="{{ asset('admin/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/datatables-plugins/dataTables.bootstrap.css')}}" rel="stylesheet">
        <link href="{{ asset('admin/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">   
        <link href="{{ asset('admin/datetimepicker/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">   

        <script src="{{ asset('admin/jquery/jquery.min.js')}}"></script>
        <script src="{{ asset('admin/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset('admin/metisMenu/metisMenu.min.js')}}"></script>
        <script src="{{ asset('admin/dist/js/sb-admin-2.js')}}"></script>      
        
        <script src="{{ asset('admin/datatables/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('admin/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
        <script src="{{ asset('admin/datatables-responsive/dataTables.responsive.js')}}"></script>
        <script src="{{ asset('admin/moment/moment-with-locales.js')}}"></script>
        <script src="{{ asset('admin/datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>

        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->        

        
    </head>
    <body>
        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">CDO Forcast</a>
                </div>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul  class="dropdown-menu dropdown-messages" id="suggessionNotification"></ul>
                        <!-- /.dropdown-messages -->
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" ><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">

                            <li>
                                <a href="{{ url('/admin/dashboard') }}"> Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/grocery') }}"> Grocery</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/restaurant') }}"> Restarunt</a>
                            </li> 
                            <li>
                                <a href="{{ url('/admin/religion') }}"> Religion</a>
                            </li>  
                            <li>
                                <a href="{{ url('/admin/events') }}"> Events</a>
                            </li>
                            <li>
                                <a href="#"> Movies<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ url('/admin/movies') }}">Movies</a>
                                    </li>                                       
                                    <li>
                                        <a href="{{ url('/admin/theatre') }}">Theatre</a>
                                    </li>                                
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>                                                                                                                                          
                            <li>
                                <a href="#"> Messages<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ url('/admin/suggession_for_edit') }}">Suggessions for edit (<span id="suggessionForEditCount"></span>)</a>
                                    </li>                                
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>
            @yield('content')
        </div> 
        <script>
            /*---------- Image Slider End----------*/
            $( document ).ready(function() {
                $.get("<?php echo URL::to('/');?>/getSuggessionNotification", function(data, status){
                    //if(status=="success"){
                        document.getElementById("suggessionNotification").innerHTML = data.message;
                        document.getElementById("suggessionForEditCount").innerHTML = data.count;
                    //}
                });
            });
            
        </script>               
    </body>
</html>

