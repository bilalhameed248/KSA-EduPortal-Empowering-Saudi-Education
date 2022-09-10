@extends('superadmin.loginlayout')
@section('content')
<div class="limiter">
	<div class="container-login100 page-background">
		<div class="wrap-login100">
			@if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form class="login100-form validate-form" method="POST" action="{{ route('password.email') }}">
                @csrf
				<span class="login100-form-logo">
					<img alt="" src="public/img/logo-2.png">
				</span>
				<!-- <span class="login100-form-title  p-t-27">
					Forgot Your Password?
				</span> -->
				<p class="text-center txt-small-heading">
					Forgot Your Password? Let Us Help You.
				</p>
				<div class="wrap-input100 validate-input" data-validate="Enter username">
					<input id="email" type="email" class="input100" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
						placeholder="Enter Your Register Email Address">

					<span class="focus-input100" data-placeholder="&#xf207;"></span>
					@error('email')
	                    <span class="invalid-feedback" role="alert">
	                        <strong>{{ $message }}</strong>
	                    </span>
	                @enderror
				</div>
				<div class="container-login100-form-btn">
					<button class="login100-form-btn" type="submit">
						Send
					</button>
				</div>
				<div class="text-center p-t-27">
					<a class="txt1" href="{{ url('/') }}">
						Login?
					</a>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection