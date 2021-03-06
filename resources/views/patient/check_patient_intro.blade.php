@extends('template.main')

@section('title', $title)

@section('content_title',"Check Patient")
@section('content_description',"Check Patient here and update history from here !")
@section('breadcrumbs')

<ol class="breadcrumb">
    <li><a href="{{route('dash')}}"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
    <li class="active">Here</li>
</ol>
@endsection
@section('main_content')


<script>
    $(document).ready(function () {
  $("#appNum").focus();
});

function validateNum(appNum){
    var data=new FormData;
    data.append('number',appNum);
    data.append('_token','{{csrf_token()}}');
    
    $.ajax({
    type: "POST",
    url: "{{route('validateAppNum')}}",
    processData: false,
    contentType: false,
    cache: false,
    data:data,
    error: function(data){
        console.log(data);
    },
    success: function (appointment) {
        if(appointment.exist){
          $("#btn_submit").removeAttr("disabled");
          $("#btn_submit").focus();
          $("#details").fadeIn();
          $("#p_name").text(appointment.name);
          $("#pnum").val(appointment.pNum)
          $("#appt_num").text(appointment.appNum);
          $("#appt_num_1").val(appointment.appNum);
        }else{
          $("#validation").text("Invalid Appointment Number Or Patient Number. Check Again...");
          $("#appNum").focus();
        }
    }
});
}

</script>

<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Check Patient</h3>
            </div>
            <div class="box-body mt-0">
                <form class="pl-5 pr-5 pb-5" method="post" action="{{route('checkPatient')}}">
                    @csrf
                    <h3>Enter Appointment Number Or Patient Number To Begin</h3>
                    <input id="appNum" class="form-control input-lg" type="number"
                        onchange="validateNum(this.value)" placeholder="Appointment Number Or Patient Number">
                    <input disabled id="btn_submit" type="submit" class="btn btn-primary btn-lg mt-3 text-center"
                        value="Check Patient">
                    <input name="pid" type="hidden" id="pnum">
                    <input name="appNum" type="hidden" id="appt_num_1">
                    <p id="validation" class="mt-2 text-danger"></p>
                    <div style="display:none" id="details">
                        <h4>Patient Name : <span id="p_name"></span></h4>
                        <h4>Appointment &nbsp;: <span id="appt_num"></span></h4>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>

</div>

@endsection