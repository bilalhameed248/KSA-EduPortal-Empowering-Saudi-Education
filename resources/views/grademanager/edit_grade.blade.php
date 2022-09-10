@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Edit Grade</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('allgrade')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Grades Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Edit Grade</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Grade Details</header>
			</div>
			<form method="POST" action="{{route('update_grade')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listBranch" value="{{$specific_grade->block_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="grade_id" value="{{$specific_grade->grade_id}}">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select Block</label>
						<ul data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<!-- <li class="mdl-menu__item" data-val="DE">XYZ</li>
							<li class="mdl-menu__item" data-val="BY">ABC</li> -->
							
						</ul>
					</div>
				</div>
			
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" type="text" id="txtCourseName" value="{{$specific_grade->grade_name}}" readonly>
						<label class="mdl-textfield__label">Grade Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						@if($leader_name)
						<input class="mdl-textfield__input" type="text" id="listVP" value="{{$leader_name->first_name.' '.$leader_name->last_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="already_leader" value="{{$leader_name->id}}">
						@elseif($leader_name=="")
						<input class="mdl-textfield__input" type="text" id="listVP" value="" readonly
							tabIndex="-1">
						@endif
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Grade Leader</label>
						<ul data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($leader as $leader1)
							<li class="mdl-menu__item leader" value="{{$leader1->id}}">
								{{$leader1->first_name." ".$leader1->last_name}}
							</li>
							@endforeach
							<input type="hidden" id="leader" name="leader">
						</ul>
					</div>
					@if ($errors->has('leader'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('leader') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('allgrade') }}'">Cancel</button>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".leader").click(function()
	{
		var a = $(this).val();
		$("#leader").val(a);
	});
</script>
@endsection