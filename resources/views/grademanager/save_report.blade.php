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
									@foreach($get_data as $get_data1)
									<tr class="odd gradeX">
										<td class="patient-img">
											<img src="public/img/prof/prof1.jpg"
												alt="">
										</td>
										<td>{{$get_data1->first_name}}</td>
										<td class="left">{{$get_data1->last_name}}</td>
										<td class="left">{{$get_data1->subject_name}}</td>
										<td class="left">{{$get_data1->student_participation}}</td>
										<td class="left">{{$get_data1->student_midterm}}</td>
										<td class="left">{{$get_data1->student_final}}</td>
										<td class="left">{{$get_data1->student_term}}</td>
										<td class="left">{{$get_data1->grade}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="button" data-toggle="modal" data-target="#student_report_name_model"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Save</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('teacherreport') }}'">Cancel</button>
				</div>
			</div>
		</div>
	</div>					
</div>


<div class="modal fade" style="margin-top: 10%;" id="student_report_name_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter name Of File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('saveFileInDB_hm')}}" enctype="multipart/form-data">
      	@csrf
      <div class="modal-body">
        <input type="text" required class="form-control-plaintext" name="student_report_name" id="student_report_name" placeholder="Enter File Name:">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection