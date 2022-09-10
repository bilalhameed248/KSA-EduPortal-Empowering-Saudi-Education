@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Edit Block</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{url('allblock')}}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">Blocks Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Edit Block</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Block Details</header>
			</div>
			<form method="POST" action="{{route('update_block')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input readonly class="mdl-textfield__input" type="text" id="listBranch" value="{{$specific_block->school_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="block_id" value="{{$specific_block->block_id}}">
						<label for="listBranch" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listBranch" class="mdl-textfield__label">Select School</label>
						<ul data-mdl-for="listBranch" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<!-- <li class="mdl-menu__item" data-val="DE">XYZ</li>
							<li class="mdl-menu__item" data-val="BY">ABC</li> -->
						</ul>
					</div>
				</div>
			
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input readonly class="mdl-textfield__input" type="text" id="txtCourseName" value="{{$specific_block->block_name}}">
						<label class="mdl-textfield__label">Block Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						@if($head_master_name)
						<input class="mdl-textfield__input" type="text" id="listVP" value="{{$head_master_name->first_name.' '.$head_master_name->last_name}}" readonly
							tabIndex="-1">
						<input type="hidden" name="already_head_master" value="{{$head_master_name->id}}">
						@elseif($head_master_name=="")
						<input class="mdl-textfield__input" type="text" id="listVP" value="" readonly
							tabIndex="-1">
						@endif
						<label for="listVP" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="listVP" class="mdl-textfield__label">Head Master/Misstress</label>
						<ul data-mdl-for="listVP" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							@foreach($head_master as $head_master1)
								<li class="mdl-menu__item headmaster" value="{{$head_master1->id}}">
									{{$head_master1->first_name." ".$head_master1->last_name}}
								</li>
							@endforeach
							<input type="hidden" id="headmaster" name="headmaster">
						</ul>
					</div>
					@if ($errors->has('headmaster'))
					    <div class="alert alert-danger">
					        <ul>
					            <li>{{ $errors->first('headmaster') }}</li>
					        </ul>
					    </div>
					@endif
				</div>
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('allblock') }}'">Cancel</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	$(".headmaster").click(function()
	{
		var a = $(this).val();
		$("#headmaster").val(a);
	});
</script>
@endsection