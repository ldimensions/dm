@extends('layouts.admin')

@section('content')
    <div id="page-wrapper">
        <div style="position: relative;height:30px;"></div>
        <div class="row">
            <div class="col-lg-4">
                <!-- /.panel-heading -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-bell fa-fw"></i> Items Submitted
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <a href="#" class="list-group-item">
                                <i class="fa fa-shopping-cart fa-fw"></i> 
                                @if ($grocerySubmitted > 0)
                                    <span style="color:#337ab;font-weight: bold;">{{$grocerySubmitted}}</span>
                                @else                                                  
                                    <span>{{$grocerySubmitted}}</span>
                                @endif                            
                                Grocery Submitted                               
                            </a>  
                            <a href="{{ url('/admin/restaurant') }}" class="list-group-item">
                                <i class="fa fa-shopping-cart fa-fw"></i>
                                @if ($restaurantSubmitted > 0)
                                    <span style="color:#337ab;font-weight: bold;">{{$restaurantSubmitted}}</span>
                                @else                                                  
                                    <span>{{$restaurantSubmitted}}</span>
                                @endif
                                Restaurant Submitted    
                            </a>    
                            <a href="{{ url('/admin/religion') }}" class="list-group-item">
                                <i class="fa fa-shopping-cart fa-fw"></i> 
                                @if ($religionSubmitted > 0)
                                    <span style="color:#337ab;font-weight: bold;">{{$religionSubmitted}}</span>
                                @else                                                  
                                    <span>{{$religionSubmitted}}</span>
                                @endif
                                 Religion Submitted    
                            </a>                                                     
                        </div>                       
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- /.panel-heading -->
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <i class="fa fa-bell fa-fw"></i> Items Rejected
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            <a href="#" class="list-group-item">
                                <i class="fa fa-warning fa-fw"></i>
                                @if ($groceryRejected > 0)
                                    <span style="color:red;font-weight: bold;">{{$groceryRejected}}</span>
                                @else                                                  
                                    <span>{{$groceryRejected}}</span>
                                @endif
                                Grocery Rejected                             
                            </a>  
                            <a href="{{ url('/admin/restaurant') }}" class="list-group-item">
                                <i class="fa fa-warning fa-fw"></i>
                                @if ($restaurantRejected > 0)
                                    <span style="color:red;font-weight: bold;">{{$restaurantRejected}}</span>
                                @else                                                  
                                    <span>{{$restaurantRejected}}</span>
                                @endif
                                Restaurant Rejected
                            </a>   
                            <a href="{{ url('/admin/religion') }}" class="list-group-item">
                                <i class="fa fa-warning fa-fw"></i>
                                @if ($religionRejected > 0)
                                    <span style="color:red;font-weight: bold;">{{$religionRejected}}</span>
                                @else                                                  
                                    <span>{{$religionRejected}}</span>
                                @endif
                                Religion Rejected
                            </a>                                                      
                        </div>                       
                    </div>
                </div>
            </div>            
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Actions
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="{{ url('/admin/sitemap') }}">SiteMap</a>
                                    </li>  
                                     <li><a href="">DB Backup</a>
                                    </li>                                                                       
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p class="text-success">Run the <a href="{{ URL::to('/sitemap.xml') }}" target="_blank" class="title">SiteMap</a></p>
                        <p class="text-success">Run the Database backup.</p>
                    </div>                
                </div>
            </div>

        </div>
    </div>
@endsection
  

