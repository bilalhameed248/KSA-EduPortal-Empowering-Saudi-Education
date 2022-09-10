@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Assigned Teachers List</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('allassignedteachers')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Teacher Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">All Assigned Teachers</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="tabbable-line">
			<!-- <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab1" data-toggle="tab"> List View </a>
                </li>
                <li>
                    <a href="#tab2" data-toggle="tab"> Grid View </a>
                </li>
            </ul> -->
			
			<div class="tab-content">
				<div class="tab-pane active fontawesome-demo" id="tab1">
					<div class="row">
						<div class="col-md-12">
							<div class="card card-box">
								<div class="card-head">
									<header>All Assigned Teachers</header>
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
												<a href="{{url('assignteacher')}}" id="addRow"
													class="btn btn-info">
													Assign New Teacher <i class="fa fa-plus"></i>
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
													<th> Teacher Name </th>
													<th> Subject Name </th>
													<th> ClassRoom Name </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody>
												@foreach($all_assigned_teachers as $teachers1)
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof1.jpg"
															alt="">
													</td>
													<td>{{$teachers1->first_name." ".$teachers1->last_name}}</td>
													<td class="left">{{$teachers1->subject_name}}</td>
													<td class="left">{{$teachers1->classroom_name}}</td>
													<td>
														<form method="post" action="{{route('edit_assigned_teacher')}}" enctype="multipart/form-data">
															@csrf
														<input type="hidden" name="teacher_classroom_id" value="{{$teachers1->teacher_classroom_id}}">
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