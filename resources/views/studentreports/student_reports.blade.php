@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Students Report</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Reports</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Students Report</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Selection Criteria</header>
			</div>
			<form method="POST" action="{{route('get_report')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allbranch" class="form-control">
							<option selected disbaled>Select Branch</option>
							@foreach($allbranch as $allbranch1)
					        <option value="{{$allbranch1->branch_id}}">{{$allbranch1->branch_name}}</option>
					        @endforeach
					    </select>
					    <input type="hidden" id="to_branch_id" name="branch_id">
					</div>
				</div>
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allschool" class="form-control">
					        
					    </select>
					    <input type="hidden" id="to_school_id" name="school_id">
					</div>
				</div>
			
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allblock" class="form-control">
					        
					    </select>
					    <input type="hidden" id="to_block_id" name="block_id">
					</div>
				</div>
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allgrade" class="form-control">
					        
					    </select>
					    <input  type="hidden" id="to_grade_id" name="grade_id">
					</div>
				</div>
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allclass" class="form-control">
					        
					    </select>
					    <input type="hidden" id="to_class_id" name="class_id">
					</div>
				</div>
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allsubjects" class="form-control">
					        
					    </select>
					    <input type="hidden" id="to_subject_id" name="subject_id">
					</div>
				</div>
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allstudents" class="form-control">
					        
					    </select>
					    <input type="hidden" id="to_student_id" name="student_id">
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select required id="allstudents" name="grade_type" class="form-control">
					        <option value="All">All</option>
					        <option value="Mid Term">Mid Term</option>
					        <option value="Final Term">Final Term</option>
					    </select>
					</div>
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">View</button>
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
	$('#allbranch').on('change', function() {
		var branch_id = $(this).val();
		$("#to_branch_id").val(branch_id);
		$.ajax({
            type: 'POST',
            url: '{{ route('get_schools') }}',
            data: {
                _token: "{{ csrf_token() }}",
                branch_id: branch_id
            },
            success: function(response) 
            {
            	var len = response.length;
            	$("#allschool").empty();
            	var output='<option selected disbaled>Select School</option><option value="allschool">All</option>';
                for (var i = 0; i < len; i++) 
                {
                    var school_name = response[i]['school_name'];
                    var school_id = response[i]['school_id'];
                   output+='<option value="'+school_id+'">'+school_name+'</option>';
                }
                $("#allschool").html(output);
        	}
        });
    });
    $('#allschool').on('change', function() {
		var school_id = $(this).val();
		// alert(schiool_id);
		$("#to_school_id").val(school_id);
		$.ajax({
            type: 'POST',
            url: '{{ route('get_block') }}',
            data: {
                _token: "{{ csrf_token() }}",
                school_id: school_id
            },
            success: function(response) 
            {
            	var len = response.length;
            	$("#allblock").empty();
                var output='<option selected disbaled>Select Block</option>';
                for (var i = 0; i < len; i++) 
                {
                    var block_name = response[i]['block_name'];
                    var block_id = response[i]['block_id'];
                   output+='<option value="'+block_id+'">'+block_name+'</option>';
                }
                $("#allblock").html(output);
        	}
        });
    });
    $('#allblock').on('change', function() {
		var block_id = $(this).val();
		$("#to_block_id").val(block_id);
		$.ajax({
            type: 'POST',
            url: '{{ route('get_grade') }}',
            data: {
                _token: "{{ csrf_token() }}",
                block_id: block_id
            },
            success: function(response) 
            {
            	var len = response.length;
            	$("#allgrade").empty();
                var output='<option selected disbaled>Select Grade</option>';
                for (var i = 0; i < len; i++) 
                {
                    var grade_name = response[i]['grade_name'];
                    var grade_id = response[i]['grade_id'];
                   output+='<option value="'+grade_id+'">'+grade_name+'</option>';
                }
                $("#allgrade").html(output);
        	}
        });
    });
    $('#allgrade').on('change', function() {
		var grade_id = $(this).val();
		$("#to_grade_id").val(grade_id);
		$.ajax({
            type: 'POST',
            url: '{{ route('get_class') }}',
            data: {
                _token: "{{ csrf_token() }}",
                grade_id: grade_id
            },
            success: function(response) 
            {
            	var len = response.length;
            	$("#allclass").empty();
                var output='<option selected disbaled>Select Class</option>';
                for (var i = 0; i < len; i++) 
                {
                    var classroom_name = response[i]['classroom_name'];
                    var classroom_id = response[i]['classroom_id'];
                   output+='<option value="'+classroom_id+'">'+classroom_name+'</option>';
                }
                $("#allclass").html(output);
        	}
        });
    });
    $('#allclass').on('change', function() {
		var class_id = $(this).val();
		$("#to_class_id").val(class_id);
		$.ajax({
            type: 'POST',
            url: '{{ route('get_subjects') }}',
            data: {
                _token: "{{ csrf_token() }}",
                class_id: class_id
            },
            success: function(response) 
            {
            	// alert(response);
            	var len = response.length;
            	$("#allsubjects").empty();
                var output='<option selected disbaled>Select Subjects</option>';
                for (var i = 0; i < len; i++) 
                {
                    var subject_name = response[i]['subject_name'];
                    var subject_id = response[i]['subject_id'];
                   output+='<option value="'+subject_id+'">'+subject_name+'</option>';
                }
                $("#allsubjects").html(output);
        	}
        });
    });
    $('#allsubjects').on('change', function() {
		var subject_id = $(this).val();
		$("#to_subject_id").val(subject_id);
		$.ajax({
            type: 'POST',
            url: '{{ route('get_student') }}',
            data: {
                _token: "{{ csrf_token() }}",
                subject_id: subject_id
            },
            success: function(response) 
            {
            	var len = response.length;
            	$("#allstudents").empty();
                var output='<option selected disbaled>Select Student</option>';
                for (var i = 0; i < len; i++) 
                {
                    var first_name = response[i]['first_name'];
                    var last_name = response[i]['last_name'];
                    var full_name= first_name+" "+last_name;
                    var student_id = response[i]['student_id'];
                   output+='<option value="'+student_id+'">'+full_name+'</option>';
                }
                $("#allstudents").html(output);
        	}
        });
    });
    $('#allstudents').on('change', function() {
		var student_id = $(this).val();
		$("#to_student_id").val(student_id);
    });
</script>
@endsection