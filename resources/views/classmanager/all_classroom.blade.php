@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">ClassRooms List</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="index.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Classes Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">All ClassRooms</li>
		</ol>
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
									<header>All ClassRooms</header>
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
												<a href="{{url('addclassroom')}}" id="addRow"
													class="btn btn-info">
													Add New ClassRoom <i class="fa fa-plus"></i>
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
													<th> Grade Name </th>
													<th> Classroom Name </th>
													<th> ClassRoom Supervisor </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody>
												@foreach($allclassroom as $allclassroom1)
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof1.jpg"
															alt="">
													</td>
													<td>{{$allclassroom1->grade_name}}</td>
													<td class="left">{{$allclassroom1->classroom_name}}</td>
													<?php 
														$supervisor_name=DB::table('users')
											   				->where('id', '=', $allclassroom1->supervisor)
											            	->first();
													?>
													@if($supervisor_name)
													<td class="left">{{$supervisor_name->first_name." ".$supervisor_name->last_name}}</td>
													@else
													<td class="left"></td>
													@endif
													<td>

														<form method="post" action="{{route('editclassroom')}}" enctype="multipart/form-data">
															@csrf
														<input type="hidden" name="classroom_id" value="{{$allclassroom1->classroom_id}}">
														<button type="submit" class="btn btn-primary btn-xs">
															<i class="fa fa-pencil"></i>
														</button>
														</form>
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