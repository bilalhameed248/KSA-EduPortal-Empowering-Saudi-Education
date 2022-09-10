@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Add Branch</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="http://school.admk.xyz/home">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Branch Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Add Branch</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Branch Details</header>	
			</div>
			<form method="POST" action="{{route('add_branch')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input name="branchname" required class="mdl-textfield__input" type="text" id="branchname">
						<label class="mdl-textfield__label">Branch Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input name="branchcity" required class="mdl-textfield__input" type="text" id="branchcity">
						<label class="mdl-textfield__label">Branch City</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input required class="mdl-textfield__input" type="text" id="list2" value="" readonly
							tabIndex="-1">
						<label for="list2" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="list2" class="mdl-textfield__label">Branch Principle</label>
						<ul data-mdl-for="list2" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($branchprinciple as $branchprinciple1)
							<li class="mdl-menu__item branchprinciple" value="{{$branchprinciple1->id}}">
								{{$branchprinciple1->first_name." ".$branchprinciple1->last_name}}
							</li>
							@endforeach
							<input type="hidden" id="branchprinciple" name="branchprinciple">
						</ul>
					</div>
					@if ($errors->any())
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="Submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('allbranch') }}'">Cancel</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".branchprinciple").click(function()
	{
		var a = $(this).val();
		$("#branchprinciple").val(a);
	});
</script>
@endsection