@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Edit ClassRoom</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('allclassroom')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Classes Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Edit ClassRoom</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>ClassRoom Details</header>
			</div>
			<form method="POST" action="{{route('update_classroom')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listBranch" value="{{$specific_classroom->grade_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="classroom_id" value="{{$specific_classroom->classroom_id}}">
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
						<input class="mdl-textfield__input" required name="classroom_name" type="text" id="txtCourseName" value="{{$specific_classroom->classroom_name}}">
						<label class="mdl-textfield__label">ClassRoom Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listVP" value="{{$specific_classroom->first_name.' '.$specific_classroom->last_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="already_supervisor" value="{{$specific_classroom->supervisor}}">
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Class Supervisor</label>
						<ul style="height:150px; width:25%;overflow:hidden; overflow-y:scroll;" data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($alluser as $alluser1)
							<li class="mdl-menu__item classsupervisor" value="{{$alluser1->id}}">
								{{$alluser1->first_name." ".$alluser1->last_name}}
							</li>
							@endforeach
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
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('allclassroom') }}'">Cancel</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<div class="row">
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
												<a href="#" class="btn btn-info" 
												data-toggle="modal" data-target="#addStudentToClass1">
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
													<th> Email </th>
													<th> Status </th>
													<th> Date </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody>
												@foreach($student_list as $student_list1)
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof6.jpg"
															alt="">
													</td>
													<td>{{$student_list1->first_name}}</td>
													<td class="left">{{$student_list1->last_name}}</td>
													<td class="left">{{$student_list1->user_type}}</td>
													<td class="left"><a href="tel:{{$student_list1->phone}}">{{$student_list1->phone}}</a></td>
													<td><a href="mailto:{{$student_list1->email}}">
															{{$student_list1->email}}</a></td>
													<td class="left">{{$student_list1->status}}</td>
													<td class="left">{{$student_list1->updated_at}}</td>
													<td>
														<button  class="btn btn-danger btn-xs" onclick="location.href='{{ url('delete_student/'.$student_list1->id) }}'">
															<i class="fa fa-trash-o "></i>
														</button>
													</td>
												</tr>
												@endforeach
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
</div>

<div class="modal fade" style="margin-top: 10%;" id="addStudentToClass1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Select Student, Press Ctrl To Select Multiple</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('add_student_to_class')}}" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="classroom_id" value="{{$specific_classroom->classroom_id}}">
	      <div class="modal-body">
	        <select required multiple="" name="users[]" class="form-control" id="exampleSelect2">
	        	@foreach($student as $student1)
		        <option value="{{$student1->id}}">{{$student1->first_name." ".$student1->last_name}}</option>
		        @endforeach
		      </select>
	      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".classsupervisor").click(function()
	{
		var a = $(this).val();
		$("#classsupervisor").val(a);
	});
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
@endsection