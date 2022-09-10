@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Edit Student Grade</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('addgrade')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Student Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Edit Grade</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>School Details</header>
			</div>
			<form method="POST" action="{{route('update_std_marks')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input required class="mdl-textfield__input" type="text" name="participationmarks" id="participationmarks" value="{{$Specific_std_rec->student_participation}}">

						<label class="mdl-textfield__label">Participation Marks</label>

						<input type="hidden" name="student_grades_id" value="{{$Specific_std_rec->student_grades_id}}">
					</div>
				</div>
			
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input required class="mdl-textfield__input" type="text" name="midtermmarks" id="midtermmarks" value="{{$Specific_std_rec->student_midterm}}">
						<label class="mdl-textfield__label">Mid Term Marks</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input required class="mdl-textfield__input" type="text" name="finaltermmarks" id="finaltermmarks" value="{{$Specific_std_rec->student_final}}">
						<label class="mdl-textfield__label">Final Term</label>
					</div>
				</div>
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" type="text" id="total_marks" value="{{$Specific_std_rec->student_term}}" readonly>
						<label class="mdl-textfield__label">Total</label>
					</div>
				</div>
				
				<input type="hidden" id="participation1" >
				<input type="hidden" id="midterm" >
				<input type="hidden" id="finalterm" >
				
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default">Cancel</button>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$('#participationmarks').on('keyup', function (event) 
	{
        var participation = $(this).val();
        $("#participation1").val(participation);
        calculateResult();
    });
    $('#midtermmarks').on('keyup', function (event) 
	{
        var midterm = $(this).val();
        $("#midterm").val(midterm);
        calculateResult();
    });
    $('#finaltermmarks').on('keyup', function (event) 
	{
        var finalterm = $(this).val();
        $("#finalterm").val(finalterm);
        calculateResult();
    });
    function calculateResult()
    {
    	var participation=$("#participation1").val();
    	var participation = parseInt(participation);
		var midterm=$("#midterm").val();
		var midterm = parseInt(midterm);
		var finalterm=$("#finalterm").val();
		var finalterm = parseInt(finalterm);
		if(participation!='' && midterm !='' && finalterm!='')
		{
			var total_marks= participation + midterm + finalterm;
			$("#total_marks").val(total_marks);
		}
    }
</script>
@endsection