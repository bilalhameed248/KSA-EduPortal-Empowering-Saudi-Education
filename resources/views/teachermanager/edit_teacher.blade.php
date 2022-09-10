@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Edit Teacher</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('allteachers')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Teachers Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Edit Teacher</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Teacher Details</header>
			</div>
			<form method="POST" action="{{route('update_teacher')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listBranch" value="{{$specific_teachers->subject_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="subject_teacher_id" value="{{$specific_teachers->subject_teacher_id}}">
						<input type="hidden" name="already_subject_id" value="{{$specific_teachers->subject_id}}">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select Subject</label>
						<ul data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($allsubjects as $allsubjects1)
							<li class="mdl-menu__item subject" value="{{$allsubjects1->subject_id}}">
								{{$allsubjects1->subject_name}}
							</li>
							@endforeach
							<input type="hidden" id="subject" name="subject">
						</ul>
					</div>
					@if ($errors->has('subject'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('subject') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listVP" value="{{$specific_teachers->first_name.' '.$specific_teachers->last_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="already_teacher_id" value="{{$specific_teachers->teacher_id}}">
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Select Teacher</label>
						<ul data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($allteachers as $allteachers1)
							<li class="mdl-menu__item teacher" value="{{$allteachers1->id}}">
								{{$allteachers1->first_name." ".$allteachers1->last_name}}
							</li>
							@endforeach
							<input type="hidden" id="teacher" name="teacher">
						</ul>
					</div>
					@if ($errors->has('teacher'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('teacher') }}</li>
					        </ul>
					    </div>
					@endif
				</div>

				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" click="location.href='{{ url('allteachers') }}'">Cancel</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".subject").click(function()
	{
		var a = $(this).val();
		$("#subject").val(a);
	});
	$(".teacher").click(function()
	{
		var a = $(this).val();
		$("#teacher").val(a);
	});
</script>
@endsection