@extends('superadmin.app')
@section('content')
<div class="tab-content mt-5">
	<div class="tab-pane active fontawesome-demo" id="tab1">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-box">
					<div class="card-head">
						<header>Grades</header>
						<div class="tools">
							<a class="fa fa-repeat btn-color box-refresh"
								href="javascript:;"></a>
							<a class="t-collapse btn-color fa fa-chevron-down"
								href="javascript:;"></a>
						</div>
					</div>
					<div class="card-body ">
						<div class="table-scrollable">
							<table
								class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
								id="example4">
								<thead>
									<tr>
										<th></th>
										<th> First Name </th>
										<th> Last Name </th>
										<th> Subject </th>
										<th> Participation </th>
										<th> Mid Term </th>
										<th> Final Term </th>
										<th> Total </th>
										<th> Performance </th>
									</tr>
								</thead>
								<tbody>
									@if($get_data!=null)
									<tr class="odd gradeX">
										<td class="patient-img">
											<img src="public/img/prof/prof1.jpg"
												alt="">
										</td>
										<td>{{$get_data->first_name}}</td>
										<td class="left">{{$get_data->last_name}}</td>
										<td class="left">{{$get_data->subject_name}}</td>
										<td class="left">{{$get_data->student_participation}}</td>
										<td class="left">{{$get_data->student_midterm}}</td>
										<td class="left">{{$get_data->student_final}}</td>
										<td class="left">{{$get_data->student_term}}</td>
										<td class="left">{{$get_data->student_participation}}</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="button" onclick="window.print();"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Save</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('studentreport') }}'">Cancel</button>
				</div>
			</div>
		</div>
	</div>					
</div>
@endsection