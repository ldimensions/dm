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
                    <div style="position:relative;width:80%;float:left;">Movie</div>
                    <div style="position:relative;width:20%;float:left;">
                        <div style="position:relative;float:right;top:-5px;">
                            <a href="{{ url('/admin/movie_add') }}"><button type="button" class="btn btn-primary btn-ms">Add</button></a>
                        </div>                        
                    </div>
                  </div>                                  
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>Language</th>
                                  <th>City</th>
                                  <th data-sortable="false" width="100px;">Link</th>
                                  <th data-sortable="false" width="75px;">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($movies as $key => $rel)
                                @if ($rel['is_disabled'] !=0)
                                    <tr class="odd gradeX danger">
                                @else
                                    <tr class="odd gradeX">
                                @endif    
                                        <td>{{$rel['name']}}</td>
                                        <td>--</td>
                                        <td class="ceter"></td>
                                        <td class="ceter" width="100px;">
                                            <!-- <a href="{{ URL::to('/') }}/{{config('app.defaultBaseURL.movie-store-details')}}/{{ $rel['urlName'] }}" target="_blank" class="title"> -->
                                                Link
                                            <!-- </a>     -->
                                        <td width="75px;">
                                            <a href="{{ url('/admin/movie_add') }}/{{$rel['movieId']}}"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></button></a>
                                            <button type="button" class="btn btn-default btn-sm" onClick="deleteMovie({{$rel['movieId']}})"><i class="fa fa-trash-o"></i></button>
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
            responsive: true,
            'bSort': false,
            'aoColumns': [
                    //{ sWidth: "15%", bSearchable: true, bSortable: false },
                    { bSearchable: true, bSortable: false },
                    { bSearchable: true, bSortable: false },
                    { bSearchable: true, bSortable: false },
                    { bSearchable: true, bSortable: false },
                    { bSearchable: true, bSortable: false },
            ],
            //"scrollY":        "200px",
            //"scrollCollapse": false,
            //"info":           true,
            "paging":         true
        });
    });
    function deleteMovie(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/movie/delete/"+id;
        }
    }      
    </script> 
@endsection
  

