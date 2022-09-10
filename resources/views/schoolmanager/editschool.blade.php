@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Edit School</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{ url('home') }}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">School Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Edit School</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>School Details</header>
			</div>
			<form method="POST" action="{{route('update_school')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" name="branchname" id="listBranch" value="{{$specific_school->branch_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="school_id" value="{{$specific_school->school_id}}">
						<input type="hidden" name="alreadybranchid" value="{{$specific_school->branch_id}}">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select Branch</label>
						<ul data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($allbranch as $allbranch1)
							<li class="mdl-menu__item branchid" value="{{$allbranch1->branch_id}}">
								{{$allbranch1->branch_name}}
							</li>
							@endforeach
							<input type="hidden" id="branchid" name="branchid">
						</ul>
					</div>
					@if ($errors->has('branchid'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('branchid') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
			
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" type="text" name="schoolname" id="schoolname" value="{{$specific_school->school_name}}">
						<label class="mdl-textfield__label">School Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="listVP" value="{{$specific_school->first_name.' '.$specific_school->last_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="alreadyviceprinciple" value="{{$specific_school->id}}">
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Vice Principle</label>
						<ul data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($viceprinciple as $viceprinciple1)
							<li class="mdl-menu__item viceprinciple" value="{{$viceprinciple1->id}}">{{$viceprinciple1->first_name." ".$viceprinciple1->last_name}}
							</li>
							@endforeach
							<input type="hidden" id="viceprinciple" name="viceprinciple">
						</ul>
					</div>
					@if ($errors->has('viceprinciple'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('viceprinciple') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
				
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" name="alreadyschoolfor" id="listFor" value="{{$specific_school->school_for}}" readonly
							tabIndex="-1">
						<label for="listFor" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listFor" class="mdl-textfield__label">For</label>
						<ul data-mdl-for="listFor" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<li class="mdl-menu__item" value="1" id="boys">Boys</li>
							<li class="mdl-menu__item" value="2" id="girls">Girls</li>
							<input type="hidden" id="schoolfor" name="schoolfor">
						</ul>
					</div>
					@if ($errors->has('schoolfor'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('schoolfor') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('allschool') }}'">Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".viceprinciple").click(function()
	{
		var a = $(this).val();
		$("#viceprinciple").val(a);
	});
	$(".branchid").click(function()
	{
		var a = $(this).val();
		$("#branchid").val(a);
	});
	$("#boys").click(function(){
		$("#schoolfor").val("Boys");
	});
	$("#girls").click(function(){
		$("#schoolfor").val("Girls");
	});
</script>
@endsection