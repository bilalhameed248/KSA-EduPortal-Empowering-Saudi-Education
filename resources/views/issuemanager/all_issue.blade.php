@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Issues List</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('home')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Issues Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Issues List</li>
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
									<header>All Issues</header>
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
												<a href="{{url('addissue')}}" id="addRow"
													class="btn btn-info">
													Add New Issue <i class="fa fa-plus"></i>
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
													<!-- <th> Description </th>
													<th> Type </th>
													<th> Related </th>
													<th> Date </th>
													<th> Creator </th>
													<th> Status </th>
													<th> Action </th> -->


													<th> Date </th>
													<th> From/Creator </th>
													<th> Description </th>
													<th> Type </th>
													<th> Status </th>
													<th> Remarks </th>
													<th> To Role </th>
													<th> To Person </th>
													<th> Action </th>
												</tr>
											</thead>
											<tbody>
												@foreach($myissues as $myissue1)
												<tr class="odd gradeX">
													<td class="left">{{$myissue1->issue_date}}</td>
													<td class="left">{{$myissue1->first_name." ".$myissue1->last_name}}</td>
													<td>{{$myissue1->issue_text}}</td>
													<td class="left">{{$myissue1->issue_type}}</td>
													<td class="left">{{$myissue1->issue_status}}</td>
													<td class="left">{{$myissue1->issue_remarks}}</td>

													<?php 
														$issue_related_p=DB::table('users')
											   				->where('id', '=', $myissue1->issue_related)
											            	->first();
													?>
													@if($issue_related_p)
													<td class="left">{{$issue_related_p->user_type}}</td>
													<td class="left">{{$issue_related_p->first_name." ".$issue_related_p->last_name}}</td>
													@else
													<td class="left"></td>
													<td class="left"></td>
													@endif

													@if($myissue1->issue_creator==Auth::user()->id)
													<td class="left"></td>
													@else
													<td>
														<a href="{{url('editIssue/'.$myissue1->issue_id)}}"
															class="btn btn-primary btn-xs">
															<i class="fa fa-pencil"></i>
														</a>
													</td>
													@endif
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