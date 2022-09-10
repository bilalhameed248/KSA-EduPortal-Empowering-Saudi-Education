@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Users List</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{ url('alluser') }}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">User Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Users List</li>
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
									<header>All Users</header>
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
												<a href="{{ url('adduser') }}" id="addRow"
													class="btn btn-info">
													Add New <i class="fa fa-plus"></i>
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
													<th> Date </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody>
												@foreach($alluser as $user1)
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof1.jpg"
															alt="">
													</td>
													<td>{{$user1->first_name}}</td>
													<td class="left">{{$user1->last_name}}</td>
													<td class="left">{{$user1->user_type}}</td>
													<td class="left"><a href="tel:{{$user1->phone}}">{{$user1->phone}}</a></td>
													<td>{{$user1->status}}</td>
													<td><a href="mailto:{{$user1->email}}">
															{{$user1->email}} </a></td>
													<td class="left">{{$user1->created_at}}</td>
													<td>
														<form method="post" action="{{route('edituser')}}" enctype="multipart/form-data">
															@csrf
														<input type="hidden" name="user_id" value="{{$user1->id}}">
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