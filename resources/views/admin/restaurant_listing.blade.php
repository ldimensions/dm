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
              <div class="panel panel-default">
                  <div class="panel-heading" style="position:relative;width:80%;float:left;">
                    Restaurant
                  </div>
                  <div class="panel-heading" style="position:relative;width:20%;float:left;">
                      <div style="position:relative;float:right">
                        <a href="{{ url('/admin/restaurant_add') }}"><button type="button" class="btn btn-primary btn-xs">Add</button></a>
                      </div>
                  </div>                  
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th data-order='desc' data-field="premium">Premium</th>
                                  <th>Ethnicity</th>
                                  <th>City</th>
                                  <th data-sortable="false">Link</th>
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
                                        <td>{{ $rel['ethnicName'] }}</td>
                                        <td class="ceter">{{ $rel['city'] }}</td>
                                        <td class="ceter">
                                            <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-indian-restaurant')}}/{{ $rel['urlName'] }}" target="_blank" class="title">Link</a>    
                                        <td width="130px;">
                                            <a href="{{ url('/admin/restaurant_add') }}/{{$rel['restaurantId']}}"><button type="button" class="btn btn-primary">Edit</button></a>
                                            <button type="button" class="btn btn-danger" onClick="deleteRestaurant({{$rel['restaurantId']}})">Delete</button>
                                        </td>
                                    </tr>
                              @endforeach
                          </tbody>
                      </table>
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
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    function deleteRestaurant(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/restaurant/delete/"+id;
        }
    }      
    </script> 
@endsection
  

