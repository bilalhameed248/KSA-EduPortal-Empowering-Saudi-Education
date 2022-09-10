@extends('superadmin.app')
@section('content')
<div class="page-bar">
	<div class="page-title-breadcrumb">
		<div class=" pull-left">
			<div class="page-title">Add User</div>
		</div>
		<ol class="breadcrumb page-breadcrumb pull-right">
			<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
					href="{{ url('alluser') }}">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li><a class="parent-item" href="">User Management</a>&nbsp;<i class="fa fa-angle-right"></i>
			</li>
			<li class="active">Add User</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="card-head">
				<header>User Details</header>
			</div>
			<form method="POST" action="{{route('add_user')}}" enctype="multipart/form-data">
			@csrf
			<div class="card-body row">
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input required name="first_name" class="mdl-textfield__input" type="text" id="first_name">
						<label class="mdl-textfield__label">First Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input required name="last_name" class="mdl-textfield__input" type="text" id="last_name">
						<label class="mdl-textfield__label">Last Name</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label txt-full-width">
						<input required name="password" class="mdl-textfield__input" type="password" id="password">
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
						<input required name="conform_password" class="mdl-textfield__input" type="password" id="conform_password">
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
						<input required name="email" class="mdl-textfield__input" type="email" id="email">
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
						<input name="phone" class="mdl-textfield__input" type="text" id="phone">
						<label class="mdl-textfield__label">Phone</label>
					</div>
				</div>
				<div class="col-lg-6 p-t-20">
					<div
						class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
						<select name="status" class="form-control" required>
					        <option label="Select Status" value=""></option>
					        <option value="Active">Active</option>
					        <option value="In-Active">In-Active</option>
					    </select>
					</div>
				</div>
				
				
				<div class="col-lg-12 p-t-20 text-center">
					<button type="submit"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-pink" id="adduserbtn">Submit</button>
					<button type="button"
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-default" onclick="location.href='{{ url('alluser') }}'">Cancel</button>
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
        	$('#adduserbtn').attr('disabled', true);
        	$('#passwordlength').html('Password Must Be 8 Cheracter Long').css('color', 'red');
        } 
        else
        {
        	$('#adduserbtn').attr('disabled', false);
            $('#passwordlength').html('').css('color', 'green');
        }
    });
	$('#conform_password').on('keyup', function () {
        if ($('#password').val() == $('#conform_password').val()) 
        {
        	$('#adduserbtn').attr('disabled', true);
            $('#message').html('Matching').css('color', 'green');
        } 
        else
        {
        	$('#adduserbtn').attr('disabled', false);
            $('#message').html('Not Matching').css('color', 'red');
        }
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
                	$('#adduserbtn').attr('disabled', true);
                	$('#emailcheck').html('Email Already Exist').css('color', 'red');
                }
                else
                {
                	$('#adduserbtn').attr('disabled', false);
                	$('#emailcheck').html('').css('color', 'green');
                }
            }
        });
    });
</script>
@endsection