@extends('superadmin.loginlayout')
@section('content')
<div class="limiter">
	<div class="container-login100 page-background">
		<div class="wrap-login100">
			<form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
            	@csrf
				<span class="login100-form-logo">
					<img alt="" src="public/img/logo-2.png">
				</span>
				<span class="login100-form-title p-b-34 p-t-27">
					Log in
				</span>
				<div class="wrap-input100 validate-input" data-validate="Enter username">

					<input class="input100 @error('email') is-invalid @enderror" id="email" type="email" name="email" placeholder="Username" value="{{ old('email') }}" required autocomplete="email" autofocus>

					<span class="focus-input100" data-placeholder="&#xf207;"></span>
					@error('email')
	                    <span class="invalid-feedback" role="alert">
	                        <strong>{{ $message }}</strong>
	                    </span>
	                @enderror
				</div>
				<div class="wrap-input100 validate-input" data-validate="Enter password">
					<input class="input100 @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password">
					<span class="focus-input100" data-placeholder="&#xf191;"></span>
					@error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
				</div>
				<div class="contact100-form-checkbox">
					<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
					<label class="label-checkbox100" for="ckb1">
						Remember me
					</label>
				</div>
				<div class="container-login100-form-btn">
					<button class="login100-form-btn" type="submit">
						{{ __('Login') }}
					</button>
				</div>
				<div class="text-center p-t-30">
					<a class="txt1" href="{{url('/forgetpasswordSA')}}">
						Forgot Password?
					</a>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    @error('email')
    	show_alert();
    @enderror
function show_alert()
{
    Swal.fire({
		  icon: 'error',
		  title: 'Invalid Credintial',
		  text: 'Invalid Email or Password.',
		  footer: '<a href>Why do I have this issue?</a>'
		});
}
</script>
@endsection