@extends('layouts.admin')

@section('content')
  <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12" style="position:relative;top:33px;">
            <div class="panel panel-default" >
                <div class="panel-heading">
                    @if($suggessionForEdit['type'] == 1)
                        Grocery
                    @elseif($suggessionForEdit['type'] == 2)
                        Restaurant
                    @elseif($suggessionForEdit['type'] == 3)
                        Religion
                    @endif
                    
                </div>
                <div class="panel-body">
                    <p>
                        <strong>Name: </strong><em>{{$suggessionForEdit['name']}}</em>
                        <span class="mailbox-read-time pull-right">{{ date("F j, Y, g:i a",strtotime($suggessionForEdit['created_at'])) }}</span>
                    </p>
                    <p>
                        <strong>Email: </strong><em>{{$suggessionForEdit['email']}}</em>
                    </p>
                    <p>
                        <strong>Phone: </strong><em>{{$suggessionForEdit['phone']}}</em>
                    </p>
                    <p>
                        <strong>Url: </strong><em><a target="_blank" href="{{$suggessionForEdit['url']}}">Click</a></em>
                    </p>  
                                      
                    <p>
                        <em>{!!nl2br($suggessionForEdit['suggession'])!!}</em>
                    </p>
                </div>
                <div class="panel-body">
                    <a href="{{ url('/admin/suggession_for_edit') }}"><button type="button" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Back</button></a>
                    <button type="button" class="btn btn-default" onClick="deleteSuggession({{$suggessionForEdit['id']}})"><i class="fa fa-trash-o"></i> Delete</button>
                    <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                </div>
                <!-- /.panel-body -->
            </div>
    <!-- /.panel -->
    </div>
        </div>  
  
  </div>
  <script>
    function deleteSuggession(id) {
        var txt;
        var r = confirm("Are you sure to delete");
        if (r == true) {
            window.location.href = "/admin/suggession_for_edit/delete/"+id;
        }
    }      
    </script>  
@endsection
  

