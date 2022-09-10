@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Blocks List</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('allblock')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Blocks Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">All Blocks</li>
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
									<header>All Blocks</header>
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
												<!-- <a href="add_block.html" id="addRow"
													class="btn btn-info">
													Add New Block <i class="fa fa-plus"></i>
												</a> -->
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
													<th> School Name </th>
													<th> Block Name </th>
													<th> Head Master/Misstress </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody>
												@foreach($allblock as $allblock1)
												<tr class="odd gradeX">
													<td class="patient-img">
														<img src="public/img/prof/prof1.jpg"
															alt="">
													</td>
													<td>{{$allblock1->school_name}}</td>
													<td class="left">{{$allblock1->block_name}}</td>
													<?php 
														$head_master_name=DB::table('users')
											   				->where('id', '=', $allblock1->head_master)
											            	->first();
													?>
													@if($head_master_name)
													<td class="left">{{$head_master_name->first_name." ".$head_master_name->last_name}}</td>
													@else
													<td class="left"></td>
													@endif
													<td>
														<form method="post" action="{{route('editblock')}}" enctype="multipart/form-data">
															@csrf
														<input type="hidden" name="block_id" value="{{$allblock1->block_id}}">
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