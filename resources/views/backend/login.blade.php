@extends('backend/layouts.login')

@section('title', 'Login')

@section('content')
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" id="login_form">
                <span class="login100-form-logo" style="background-image: url('{{asset('backend/images/users/default.jpg')}}');">
						<i class="zmdi zmdi-landscape"></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						<i class="fa fa-lock"></i>&nbsp;Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" value="" placeholder="Username" autocomplete="off">
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" value="" placeholder="Password" autocomplete="off">
					</div>

					<div class="contact100-form-checkbox">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
						<label class="label-checkbox100" for="ckb1">
							Remember me
						</label>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Login
						</button>
					</div>

					<div class="text-center p-t-90">
                        <a class="txt1" href="{{ URL::to('admin/forgot-password') }}">
							Forgot Password?
						</a>
					</div>
				</form>
			</div>
		</div>
    </div>
    <input type="hidden" id="base_url" value="{{ URL::to('/') }}" />
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#login_form').on('submit', (function (e) {
                loginSubmitForm(this);
                return false;
            }));
        });

        function loginSubmitForm(this_form) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    var formData = new FormData(this_form);
                    // formData.append('some_key', 'Back');
                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/login',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total == 1) {
                                // saveSuccess();
                                // setTimeout(function () {
                                //     window.history.back();
                                    window.location = $('#base_url').val() + '/admin/match';
                                // }, 2000);
                            } else {
                                showWarning('Warning!', response.message);
                            }
                        },
                        error: function(response) {
                            showRequestWarning(response);
                        }
                    });
                }
            });
            return false;
        }
    </script>
@stop