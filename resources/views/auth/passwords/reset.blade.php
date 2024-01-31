@if (Auth::check())
    @if (Auth::user()->role === 1)
        @php
            $type = 'layouts.admin.default';
            $updatepassword = route('admin.updateAdminPassword');
        @endphp
     @elseif (Auth::user()->role === 2)
        @php
            $type = 'layouts.agent.default';
            $updatepassword = route('agent.updateAdminPassword');
        @endphp
     @else
        @php
            $type = 'layouts.sub.default';
            $updatepassword = route('sub.updateAdminPassword');
        @endphp 
       
    @endif
@endif
  @extends($type)
@section('content')
  
 <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
   <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Change your Account's Password?</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
						<div class="row">
							<div class="col-md-12">
								<div class="changePasswordSecs">
									<div class="changePasswordForms"> 
												  <div class="clearDiv">
												  <div class="row">
													  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group p-5 bg-white">

						  <form id="password-reset-form" method="POST" action="">
								@csrf

							<div class="form-group">
								<label for="email" class="col-form-label text-md-end">{{ __('Email Address') }}</label>

									<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $oldemail }}" required autocomplete="email" disabled autofocus>
								 <input id="userid" type="hidden" class="form-control" name="userid" value="{{ $id }}" >
									@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
							</div>
							<div class="form-group">
								<label for="password" class="col-form-label text-md-end">{{ __('Password') }}</label>
									<div class="input-group">
										<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
										<button class="btn btn-outline-secondary toggle-password" type="button" toggle="#password">
											<i class="fa fa-eye"></i>
										</button>
									</div>
									@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
							</div>

							<div class="form-group">
								<label for="password-confirm" class="col-form-label text-md-end">{{ __('Confirm Password') }}</label>
									<div class="input-group">
										<input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" onblur="checkPasswordMatch()">
										<button class="btn btn-outline-secondary toggle-password" type="button" toggle="#password-confirm">
											<i class="fa fa-eye"></i>
										</button>
									</div>
									<span id="password-match-error" class="text-danger"></span>
									@error('password_confirmation')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
									@enderror
							</div>


							<div class="form-group">
								<div class="col-md-6 offset-md-4">
								   <button id="submit-button" type="submit" class="btn btn-primary" >
										{{ __('Reset Password') }}
									</button>
								</div>
							</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
  </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var togglePassword = document.getElementsByClassName('toggle-password');
        for (var i = 0; i < togglePassword.length; i++) {
            togglePassword[i].addEventListener('click', function() {
                var input = document.querySelector(this.getAttribute('toggle'));
                if (input.getAttribute('type') === 'password') {
                    input.setAttribute('type', 'text');
                } else {
                    input.setAttribute('type', 'password');
                }
            });
        }
    });
</script>
<script>
   $(document).ready(function() {
        // Handle form submission
        $('#password-reset-form').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize(); // Serialize form data

            // Send AJAX request
            $.ajax({
                type: 'POST',
                 headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ $updatepassword }}",
                data: data,
                success: function(response) {
                    // Handle success response
                    Swal.fire('Success', response.success, 'success').then(function() {
                        window.location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        Swal.fire('Error', xhr.responseJSON.error, 'error');
                    } else {
                        Swal.fire('Error', 'An error occurred while resetting the password', 'error');
                    }
                }
            });
        });
	});
  function checkPasswordMatch() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("password-confirm").value;

    // Check if passwords match
    if (password !== confirmPassword) {
        document.getElementById("password-match-error").innerText = "Passwords do not match";
        return false;
    }

    // Check password length
    if (password.length < 8) {
        document.getElementById("password-match-error").innerText = "Password must be at least 8 characters long";
        return false;
    }

    // Check for at least one special character
    var specialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
    if (!specialChars.test(password)) {
        document.getElementById("password-match-error").innerText = "Password must contain at least one special character";
        return false;
    }

    // Clear the error message if all validation checks pass
    document.getElementById("password-match-error").innerText = "";
    return true;
}
</script>