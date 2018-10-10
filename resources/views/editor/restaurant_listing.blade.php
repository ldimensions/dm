@extends('layouts.admin')

@section('content')

  <div id="page-wrapper">
    <div style="position: relative;height:30px;"></div>
      <!-- /.row -->
      @if (session('status'))
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session('status') }}
            </div>
        @endif
      <div class="row">
          <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Restaurant</li>
            </ol>
              <div class="panel panel-default"> 
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Restaurant
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                Actions
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li><a href="{{ url('/editor/restaurant_add') }}">Add</a>
                                </li>                                   
                            </ul>
                        </div>
                    </div>
                </div>                                                                
                  <!-- /.panel-heading -->
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#pending" data-toggle="tab">Pending</a> </li>
                        <li><a href="#approved" data-toggle="tab">Approved</a> </li>                        
                    </ul>   
                    <div class="tab-content"><br/>
                    <div class="tab-pane fade in active" id="pending">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-pending">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th data-order='desc' data-field="premium">Status</th>
                                    <th>Ethnicity</th>
                                    <th>City</th>
                                    <th data-sortable="false">Link</th>
                                    <th>Last Updated</th>
                                    <th>Updated By</th>                                        
                                    <th data-sortable="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($restaurant_pending as $key => $rel)
                                    @if ($rel['status'] == 4)
                                        <tr class="odd gradeX danger">
                                    @else
                                        <tr class="odd gradeX">
                                    @endif    
                                            <td>{{$rel['name']}}</td>
                                            <td width="95px;">
                                                @if ($rel['status'] == 2)
                                                    Submitted
                                                @elseif($rel['status'] == 3)
                                                    Approved
                                                @elseif($rel['status'] == 4)
                                                    Rejected  
                                                @else                                                  
                                                    Contact Admin
                                                @endif 
                                            </td>
                                            <td width="100px;">{{ $rel['ethnicName'] }}</td>
                                            <td width="145px;">{{ $rel['city'] }}</td>
                                            <td width="50px;">
                                                <a href="{{ URL::to('/') }}/review/restaurant/{{ $rel['urlName'] }}" target="_blank" class="title">Link</a>    
                                            </td>
                                            <td width="150px;">{{ $rel['updated_at'] }}</td>
                                            <td width="150px;">{{ $rel['updatedBy'] }}</td>
                                            <td width="75px;">
                                                <a href="{{ url('/editor/restaurant_add') }}/{{$rel['restaurantId']}}"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></button></a>
                                                <button type="button" class="btn btn-default btn-sm" onClick="deleteRestaurant({{$rel['restaurantId']}})"><i class="fa fa-trash-o"></i></button>
                                            </td>                                         
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>                    
                    <div class="tab-pane fade" id="approved" >
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-approved">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th data-order='desc' data-field="premium">Premium</th>
                                    <th>Ethnicity</th>
                                    <th>City</th>
                                    <th data-sortable="false">Link</th>
                                    <th>Last Updated</th>
                                    <th>Updated By</th>                                        
                                    <th data-sortable="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($restaurant as $key => $rel)
                                    @if ($rel['is_disabled'] !=0)
                                        <tr class="odd gradeX danger">
                                    @else
                                        <tr class="odd gradeX">
                                    @endif    
                                            <td>{{$rel['name']}}</td>
                                            <td width="95px;">{{ $rel['premium'] }}</td>
                                            <td width="100px;">{{ $rel['ethnicName'] }}</td>
                                            <td width="145px;">{{ $rel['city'] }}</td>
                                            <td width="50px;">
                                                <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" target="_blank" class="title">Link</a>    
                                            </td>
                                            <td width="150px;">{{ $rel['updated_at'] }}</td>
                                            <td width="150px;">{{ $rel['updatedBy'] }}</td>
                                            <td width="75px;">
                                                @if (!$rel['referenceId'])   
                                                    <a href="{{ url('/editor/restaurant_add_duplicate') }}/{{$rel['restaurantId']}}"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></button></a>
                                                @endif
                                            </td>                                         
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    </div>
                      <!-- /.table-responsive -->
                  </div>
                  <!-- /.panel-body -->
              </div>
              <!-- /.panel -->
          </div>
          <!-- /.col-lg-12 -->
      </div>
      <!-- /.row -->
  </div>
  <script>
    $(document).ready(function() {
        $('#dataTables-approved').DataTable({
            responsive: true,
            order: [[ 1, 'desc' ]]
        });
        $('#dataTables-pending').DataTable({
            responsive: true,
            order: [[ 1, 'desc' ]]
        });        
    });
    function deleteRestaurant(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/editor/restaurant/delete/"+id;
        }
    }      
    </script> 
@endsection
  

