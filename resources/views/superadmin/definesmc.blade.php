@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">SMC Members List</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{ url('alluser') }}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">User Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Define SMC</li>
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
									<header>SMC Members</header>
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
												<a data-toggle="modal" data-target="#addSmcMember" href="#"
													class="btn btn-info">
													Add New Member <i class="fa fa-plus"></i>
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
												@foreach($allSmcMember as $allSmcMember1)
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof1.jpg"
															alt="">
													</td>
													<td>{{$allSmcMember1->first_name}}</td>
													<td class="left">{{$allSmcMember1->last_name}}</td>
													<td class="left">{{$allSmcMember1->user_type}}</td>
													<td class="left"><a href="tel:4444565756">{{$allSmcMember1->phone}}</a></td>
													<td>{{$allSmcMember1->status}}</td>
													<td><a href="mailto:shuxer@gmail.com">
															{{$allSmcMember1->email}} </a></td>
													<td class="left">{{$allSmcMember1->updated_at}}</td>
													<td>
														<button onclick="location.href='{{ url('/deleteSmc/'.$allSmcMember1->id) }}'" class="btn btn-danger btn-xs">
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
<div class="modal fade" style="margin-top: 10%;" id="addSmcMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Select SMC Member, Press Ctrl To Select Multiple</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('add_smc_member')}}" enctype="multipart/form-data">
      	@csrf
      <div class="modal-body">
        <select multiple="" name="users[]" class="form-control" id="exampleSelect2">
        	@foreach($alluser as $user1)
	        <option value="{{$user1->id}}">{{$user1->first_name." ".$user1->last_name}}</option>
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