@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Add ClassRoom</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="index.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Classes Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Add ClassRoom</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>ClassRoom Details</header>
			</div>
			<form method="POST" action="{{route('add_classroom')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input readonly class="mdl-textfield__input" type="text" id="listBranch" value="{{$leader_grade->grade_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="grade_id" value="{{$leader_grade->grade_id}}">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select Grade</label>
						<ul data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<!-- <li class="mdl-menu__item" data-val="DE">4th Grade</li>
							<li class="mdl-menu__item" data-val="BY">5th Grade</li> -->
						</ul>
					</div>
				</div>
			
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input required class="mdl-textfield__input" type="text" id="txtCourseName" name="classroom_name">
						<label class="mdl-textfield__label">ClassRoom Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<!-- <input class="mdl-textfield__input" type="text" id="listVP" value="" readonly
							tabIndex="-1">
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Class Supervisor</label>
						<ul data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu"> -->
							<!-- @foreach($class_supervisor as $class_supervisor1)
							<li class="mdl-menu__item classsupervisor" value="{{$class_supervisor1->id}}">
								{{$class_supervisor1->first_name." ".$class_supervisor1->last_name}}
							</li>
							@endforeach -->
							<select required id="class_supervisor_select" class="form-control">
								<option selected disbaled>Select Class Supervisor</option>
								@foreach($class_supervisor as $class_supervisor1)
						        <option value="{{$class_supervisor1->id}}">
						        	{{$class_supervisor1->first_name." ".$class_supervisor1->last_name}}
						        </option>
						        @endforeach
						    </select>
							<input type="hidden" id="classsupervisor" name="classsupervisor">
						</ul>
					</div>
					@if ($errors->has('classsupervisor'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('classsupervisor') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="button" data-toggle="modal" data-target="#addStudentToClass"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('allclassroom') }}'">Cancel</button>
				</div>
			</div>
		
		</div>
	</div>

	<div class="modal fade" style="margin-top: 10%;" id="addStudentToClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Select Student, Press Ctrl To Select Multiple</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <select required multiple="" name="users[]" class="form-control" id="allstudents">
		        
		      </select>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>

	</form>
</div>
<!-- <div class="row">
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
								<div class="card-body ">
									<div class="row">
										<div class="col-md-6 col-sm-6 col-6">
											<div class="btn-group">
												<a href="add_user.html" id="addRow"
													class="btn btn-info">
													Add New Student <i class="fa fa-plus"></i>
												</a>
											</div>
										</div>
									</div>
									<div class="table-scrollable">
										<table
											class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
											id="example4">
											<thead>
												<tr>
													<th></th>
													<th> First Name </th>
													<th> Last Name </th>
													<th> Role </th>
													<th> Phone </th>
													<th> Status </th>
													<th> Email </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody>
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof1.jpg"
															alt="">
													</td>
													<td>Rajesh</td>
													<td class="left">Computer</td>
													<td class="left">Male</td>
													<td class="left">M.Com, B.Ed</td>
													<td><a href="tel:4444565756">
															4444565756 </a></td>
													<td><a href="mailto:shuxer@gmail.com">
															rajesh@gmail.com </a></td>
													<td class="left">22 Feb 2000</td>
													<td>
														<button class="btn btn-danger btn-xs">
															<i class="fa fa-trash-o "></i>
														</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
										
			</div>
		</div>
	</div>
</div> -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!-- <script type="text/javascript">
	$(".classsupervisor").click(function()
	{
		var a = $(this).val();
		$("#classsupervisor").val(a);
	});
</script> -->
<script type="text/javascript">
	$('#class_supervisor_select').on('change', function() {
		var classsupervisor_id = $(this).val();
		$("#classsupervisor").val(classsupervisor_id);
		$.ajax({
            type: 'POST',
            url: '{{ route('get_remaning_students') }}',
            data: {
                _token: "{{ csrf_token() }}",
                classsupervisor_id: classsupervisor_id
            },
            success: function(response) 
            {
            	var len = response.length;
            	$("#allstudents").empty();
            	var output='';
                for (var i = 0; i < len; i++) 
                {
                    var first_name = response[i]['first_name'];
                    var last_name = response[i]['last_name'];
                    var full_name=first_name+" "+last_name;
                    var id = response[i]['id'];
                   output+='<option value="'+id+'">'+full_name+'</option>';
                }
                $("#allstudents").html(output);
        	}
        });
    });
</script>
@endsection