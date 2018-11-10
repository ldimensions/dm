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
                    <div style="position:relative;width:80%;float:left;">Suggessions</div>
                    <div style="position:relative;width:20%;float:left;">
                        <div style="position:relative;float:right;top:-5px;">
                            <a href="{{ url('/admin/religion_add') }}"><button type="button" class="btn btn-primary btn-ms">Add</button></a>
                        </div>                        
                    </div>
                  </div>               
                  <!-- /.panel-heading -->
                  <div class="panel-body tooltip-demo">
                      <table width="100%" style="border:0" class="table table-striped table-bordered table-hover" id="dataTables-example">
                          <thead>
                              <tr style="postion:relative;border:1px solid red;display:none;">
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Premium</th>
                                    <th>date</th>
                                    <th data-sortable="false">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                            @foreach ($suggessionForEdit as $key => $rel)
                               <tr class="odd gradeX">
                                    @if($rel['type'] == 1)
                                        @if($rel['is_read'] == 1)
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;border-left:3px solid gray;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Grocery">G</td>
                                        @else
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Grocery">G</td>
                                        @endif
                                    @elseif($rel['type'] == 2)
                                        @if($rel['is_read'] == 1)
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;border-left:3px solid gray;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Restarunt">R</td>
                                        @else
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Restarunt">R</td>
                                        @endif
                                    @elseif($rel['type'] == 3)
                                        @if($rel['is_read'] == 1)
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;border-left:3px solid gray;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Religion">Re</td>                                        
                                        @else    
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Religion">Re</td>                                        
                                        @endif
                                    @elseif($rel['type'] == 4)
                                        @if($rel['is_read'] == 1)
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;border-left:3px solid gray;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Movie">Re</td>                                        
                                        @else    
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Movie">M</td>                                        
                                        @endif
                                    @elseif($rel['type'] == 5)
                                        @if($rel['is_read'] == 1)
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;border-left:3px solid gray;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Theatre">Re</td>                                        
                                        @else    
                                            <td style="border:0;padding:5px;width:30px;font-weight:bold;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Theatre">T</td>                                        
                                        @endif                                                                                
                                    @endif
                                    @if($rel['is_read'] == 1)
                                        <td style="border:0;padding:5px;width:25%;font-weight:bold;"><a href="{{ url('/admin/suggession_for_edit') }}/{{$rel['id']}}" style="color: black;text-decoration:none;">{{$rel['name']}}</a></td>
                                    @else
                                    <td style="border:0;padding:5px;width:25%"><a href="{{ url('/admin/suggession_for_edit') }}/{{$rel['id']}}" style="color: black;text-decoration:none;">{{$rel['name']}}</a></td>
                                    @endif
                                    <td style="border:0;padding:5px;">{{ $rel['suggession'] }}</td>
                                    @if($rel['is_read'] == 1)
                                        <td style="border:0;padding:5px;width:100px;color: blue;">{{ $rel['created_at'] }}</td>
                                    @else
                                        <td style="border:0;padding:5px;width:150px">{{ $rel['created_at'] }}</td>
                                    @endif
                                    <td style="border:0;padding:5px;width:35px">
                                        <button type="button" onClick="deleteSuggession({{$rel['id']}})" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
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
        });
    });
    function deleteSuggession(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/suggession_for_edit/delete/"+id;
        }
    } 
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })         
    </script> 
@endsection
  

