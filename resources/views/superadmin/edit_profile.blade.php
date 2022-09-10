@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Edit profile</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{ url('home') }}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Edit Profile</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>Edit Profile</header>
			</div>
			<form method="POST" action="{{route('edit_profile_save')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input type="hidden" name="id" value="{{$single_user->id}}">
						<input class="mdl-textfield__input" name="first_name" type="text" id="first_name" value="{{$single_user->first_name}}">
						<label class="mdl-textfield__label">First Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" name="last_name" type="text" id="last_name" value="{{$single_user->last_name}}">
						<label class="mdl-textfield__label">Last Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" name="password" type="password" id="password" value="{{$single_user->password}}">
						<label class="mdl-textfield__label">Password</label>
					</div>
					<span id='passwordlength'></span>
                    @error('password')
                    	<span class="errorMessage">{{ $message }}</span>
                    @enderror
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" type="password" id="conform_password" name="conform_password" value="{{$single_user->password}}">
						<label class="mdl-textfield__label">Confirm Password</label>
					</div>
					<span id='message'></span>
                    @error('password')
                    	<span class="errorMessage">{{ $message }}</span>
                    @enderror
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" name="email" type="email" id="email" value="{{$single_user->email}}">
						<label class="mdl-textfield__label">Email Address</label>
					</div>
					<span id='emailcheck'></span>
					@error('email')
                    	<span class="errorMessage">{{ $message }}</span>
                    @enderror
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input class="mdl-textfield__input" name="phone" type="text" id="phone" value="{{$single_user->phone}}">
						<label class="mdl-textfield__label">Phone</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<input class="mdl-textfield__input" type="text" id="list2" value="{{$single_user->status}}" readonly
							tabIndex="-1">
						<label for="list2" class="pull-right margin-0">
							<i class="mdl-icon-toggle__label material-icons">keyboard_arrow_down</i>
						</label>
						<label for="list2" class="mdl-textfield__label">Status</label>
						<ul data-mdl-for="list2" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
							<!-- <li class="mdl-menu__item" value="1" id="actived" data-val="DE">Active</li>
							<li class="mdl-menu__item" value="2" id="inactive" data-val="BY">In-Active</li> -->
							<!-- <input type="hidden" id="statusval" name="status"> -->
						</ul>
					</div>
				</div>
				
				
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('home') }}'">Cancel</button>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	// $("#actived").click(function(){
	// 	var a = $("#actived").val();
	// 	$("#statusval").val("Active");
	// });
	// $("#inactive").click(function(){
	// 	var b = $("#actived").val();
	// 	$("#statusval").val("In-Active");
	// });
	//Password Authentication
	$('#password').on('keyup', function () 
	{
		var password=$("#password").val();
		var len = password.length;
        if(len<8) 
        {
        	$('#passwordlength').html('Password Must Be 8 Cheracter Long').css('color', 'red');
        } 
        else
            $('#passwordlength').html('').css('color', 'green');
    });
	$('#conform_password').on('keyup', function () {
        if ($('#password').val() == $('#conform_password').val()) 
        {
            $('#message').html('Matching').css('color', 'green');
        } 
        else
            $('#message').html('Not Matching').css('color', 'red');
    });
    //Email varification
    $('#email').on('keyup', function () 
    {
    	var email=$("#email").val();
    	$.ajax({
            type: 'POST',
            url: '{{ route('check_email') }}',
            data: {
                _token: "{{ csrf_token() }}",
                email: email
            },
            success: function(response) 
            {
                if(response=='1')
                {
                	alert
                	$('#emailcheck').html('Email Already Exist').css('color', 'red');
                }
                else
                {
                	$('#emailcheck').html('').css('color', 'green');
                }
            }
        });
    });
</script>
@endsection