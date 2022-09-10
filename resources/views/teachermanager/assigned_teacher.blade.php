@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Assign Teacher</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('allassignedteachers')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Teachers Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Assign Teacher</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Teacher Details</header>
			</div>
			<form method="POST" action="{{route('assigned_teacher')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listBranch" value="" readonly
							tabIndex="-1">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select Teacher</label>
						<ul data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
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
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listVP" value="" readonly
							tabIndex="-1">
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Select ClassRoom</label>
						<ul data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($all_classroom as $all_classroom1)
							<li class="mdl-menu__item classroom" value="{{$all_classroom1->classroom_id}}">
								{{$all_classroom1->classroom_name}}
							</li>
							@endforeach
							<input type="hidden" id="classroom" name="classroom">
						</ul>
					</div>
					@if ($errors->has('classroom'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('classroom') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('allassignedteachers') }}'">Cancel</button>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".teacher").click(function()
	{
		var a = $(this).val();
		$("#teacher").val(a);
	});
	$(".classroom").click(function()
	{
		var a = $(this).val();
		$("#classroom").val(a);
	});
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
	@if(Session::has('message'))
    	show_alert();
    @endif
function show_alert()
{	
	Swal.fire({
	  icon: 'error',
	  title: '{{Session::get('key')}}',
	  text: '{{ Session::get('message') }}',
	  footer: '<a href>Why do I have this issue?</a>'
	});
}
</script>
@endsection