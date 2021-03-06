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
                  <div class="panel-heading" style="position:relative;width:100%;height:45px;">
                    <div style="position:relative;width:80%;float:left;">Religion</div>
                    <div style="position:relative;width:20%;float:left;">
                        <div style="position:relative;float:right;top:-5px;">
                            <a href="{{ url('/admin/religion_add') }}"><button type="button" class="btn btn-primary btn-ms">Add</button></a>
                        </div>                        
                    </div>
                  </div>               
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th data-order='desc' data-field="premium">Premium</th>
                                  <th>Type</th>
                                  <th>Denomination</th>
                                  <th>City</th>
                                  <th data-sortable="false">Link</th>
                                  <th data-sortable="false">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($religion as $key => $rel)
                                @if ($rel['is_disabled'] !=0)
                                    <tr class="odd gradeX danger">
                                @else
                                    <tr class="odd gradeX">
                                @endif    
                                        <td>{{$rel['name']}}</td>
                                        <td width="95px;">{{ $rel['premium'] }}</td>
                                        <td>{{ $rel['religionName'] }}</td>
                                        <td class="ceter">{{ $rel['denominationName'] }}</td>
                                        <td class="ceter">

                                            @if ($rel['religionName'] == 'Christianity')
                                                <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-christian-church')}}/{{ $rel['urlName'] }}" class="title">Link</a><br/>
                                            @elseif($rel['religionName'] == 'Hinduism')
                                                <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-hindu-temple')}}/{{ $rel['urlName'] }}" class="title">Link</a><br/>
                                            @elseif($rel['religionName'] == 'Judaism')
                                                <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/> -->
                                            @elseif($rel['religionName'] == 'Buddhism')
                                                <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-malayali-temple')}}/{{ $rel['urlName'] }}" class="title">{{ $rel['name'] }}</a><br/> -->
                                            @elseif($rel['religionName'] == 'Islam')
                                                <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.dallas-islan-mosque')}}/{{ $rel['urlName'] }}" class="title">Link</a><br/>                                                           
                                            @endif
                                        </td>                                        
                                        <td class="ceter">{{ $rel['city'] }}</td>
                                        <td width="130px;">
                                            <a href="{{ url('/admin/religion_add') }}/{{$rel['religionId']}}"><button type="button" class="btn btn-primary">Edit</button></a>
                                            <button type="button" class="btn btn-danger" onClick="deleteReligion({{$rel['religionId']}})">Delete</button>
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
    function deleteReligion(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/religion/delete/"+id;
        }
    }      
    </script> 
@endsection
  

