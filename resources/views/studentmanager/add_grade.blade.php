@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Add Grades</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('addgrade')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Student Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Add Grades</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Students Details</header>
			</div>
			<form method="POST" id="filterdataform" action="{{route('filterdata')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listBranch" value="" readonly
							tabIndex="-1">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select ClassRoom</label>
						<ul style="height:120px; width:25%;overflow:hidden; overflow-y:scroll;" data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($classroom_and_sub as $classroom1)
							<li class="mdl-menu__item classroom" value="{{$classroom1->classroom_id}}">
								{{$classroom1->classroom_name}}
							</li>
							@endforeach
							<input type="hidden" id="classroom" name="classroom">
						</ul>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listSubject" value="" readonly
							tabIndex="-1">
						<label for="listSubject" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listSubject" class="mdl-textfield__label">Select Subject</label>
						<ul style="height:120px; width:25%;overflow:hidden; overflow-y:scroll;" data-mdl-for="listSubject" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($classroom_and_sub as $subject1)
							<li class="mdl-menu__item subject" value="{{$subject1->subject_id}}">
								{{$subject1->subject_name}}
							</li>
							@endforeach
							<input type="hidden" id="subject" name="subject">
						</ul>
					</div>
				</div>
			</div>
			<!-- <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button> -->
		</form>
		</div>
	</div>
</div>
<div class="row" style="margin-top: 2%;">
	<div class="col-md-12">
		<div class="tabbable-line">
			<div class="tab-content">
				<div class="tab-pane active fontawesome-demo" id="tab1">
					<div class="row">
						<div class="col-md-12">
							<div class="card card-box">
								<div class="card-head">
									<header>All Students</header>
									<div class="tools">
										<a class="fa fa-repeat btn-color box-refresh"
											href="javascript:;"></a>
										<a class="t-collapse btn-color fa fa-chevron-down"
											href="javascript:;"></a>
									</div>
								</div>
								<form method="POST" action="{{route('update_marks')}}" enctype="multipart/form-data">
								@csrf
								<div class="card-body">
									<div class="table-scrollable">
										<table
											class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
											id="example4">
											<thead>
												<tr>
													<th></th>
													<th> First Name </th>
													<th> Last Name </th>
													<th> Participation </th>
													<th> Mid Term </th>
													<th> Final Term </th>
													<th> Total </th>
													<th> Performence </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody >
												@if($mystudents!=null)
												<?php $iii=1; ?>
													@foreach($mystudents as $student)
													<tr class="odd gradeX">
														<td class="patient-img">
															<img src="public/img/prof/prof1.jpg"
																alt="">
														</td>
														<td>{{$student->first_name}}</td>
														<td class="left">{{$student->last_name}}</td>

														<td class="left participation1" id="participation_<?php echo $iii; ?>" contenteditable>
															{{$student->student_participation ?? ''}}
														</td>

														<td class="left midterm" id="midterm_<?php echo $iii; ?>" contenteditable>
															{{$student->student_midterm ?? ''}}
														</td>

														<td class="left finalterm" id="<?php echo $iii; ?>" contenteditable>
															{{$student->student_final ?? ''}}
														</td>

														<td class="left totalmarks" id="total_<?php echo $iii; ?>">
															{{$student->student_term ?? ''}}
														</td>

														<td class="left">
															{{$student->grade ?? ''}}
														</td>
														@if($student)
														<td class="left">
															<a href="{{url('/editstdgrade/'.$student->student_id.'/'.$student->subject_teacher_id)}}"
																class="btn btn-primary btn-xs">
																<i class="fa fa-pencil"></i>
															</a>
														</td>
														@endif

														<input type="hidden" name="student_id[]" value="{{$student->student_id}}">
														<input type="hidden" name="subject_teacher_id[]"  value="{{$student->subject_teacher_id}}">
														<input type="hidden" id="hiddenp_<?php echo $iii; ?>" name="participation1[]">
														<input type="hidden" id="hiddenm_<?php echo $iii; ?>"  name="midterm[]">
														<input type="hidden" id="hiddenf_<?php echo $iii; ?>"  name="finalterm[]">
													</tr>
													<?php $iii++; ?>
													@endforeach
												@endif
											</tbody>
										</table>
										<input type="hidden" id="participation1" >
										<input type="hidden" id="midterm" >
										<input type="hidden" id="finalterm" >
									</div>
								</div>
							</div>
							<div class="col-lg-12 p-t-20 text-center">
								<button type="submit"
									class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
								<button type="button"
									class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('addgrade') }}'">Cancel</button>
							</div>
						</div>
					</form>
					</div>
				</div>					
			</div>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".classroom").click(function()
	{
		var a = $(this).val();
		$("#classroom").val(a);
		if($("#classroom").val()!='' && $("#subject").val()!='')
		{
			$("#filterdataform").submit();
		}
	});
	$(".subject").click(function()
	{
		var a = $(this).val();
		$("#subject").val(a);
		if($("#classroom").val()!='' && $("#subject").val()!='')
		{
			$("#filterdataform").submit();
		}
	});




	//Get Numbers
	$('.participation1').on('keyup', function (event) 
	{
        var participation = $(this).text();
        var iid=event.target.id;
        var array=iid.split("_");
        var id=array[1];
        $("#hiddenp_"+id).val(participation);
        $("#participation1").val(participation);
			calculateResult(id);
    });
    $('.midterm').on('keyup', function (event) 
	{
        var midterm = $(this).text();
        var iid=event.target.id;
        var array=iid.split("_");
        var id=array[1];
        $("#hiddenm_"+id).val(midterm);
        $("#midterm").val(midterm);
			calculateResult(id);
    });
    $('.finalterm').on('keyup', function (event) 
	{
        var finalterm = $(this).text();
        var iid=event.target.id;
        $("#hiddenf_"+iid).val(finalterm);
        $("#finalterm").val(finalterm);
			calculateResult(iid);
    });

    //Calculate Final
    function calculateResult(iid)
    {
    	var participation=$("#participation1").val();
    	if(participation=="" || isNaN(participation))
    	{
    		participation=0;
    	}
    	else
    	{
    		participation = parseFloat(participation);
    	}
		var midterm=$("#midterm").val();
		if(midterm=="" || isNaN(midterm))
    	{
    		midterm=0;
    	}
    	else
    	{
    		midterm = parseFloat(midterm);
    	}
		var finalterm=$("#finalterm").val();
		if(finalterm=="" || isNaN(finalterm))
    	{
    		finalterm=0;
    	}
    	else
    	{
    		finalterm = parseFloat(finalterm);
    	}
		var total_marks= participation + midterm + finalterm;
		$("#total_"+iid).text(total_marks);
    }
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    @if(Session::has('message'))
    	show_alert();
    @endif
function show_alert()
{
    Swal.fire(
	  '{{Session::get('key')}}',
	  '{{ Session::get('message') }}',
	  'success'
	    );
}
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
	@if(Session::has('error_message'))
    	show_alert();
    @endif
function show_alert()
{	
	Swal.fire({
	  icon: 'error',
	  title: '{{Session::get('error_key')}}',
	  text: '{{ Session::get('error_message') }}',
	  footer: '<a href>Why do I have this issue?</a>'
	});
}
</script>
@endsection