<!doctype html>
<html lang="en">
<head>
        
        <meta charset="utf-8" />
        <title>Login || Travexpo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Travexpo Admin" name="description" />
        <meta content="Travexpo" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('theme/admin/assets/images/favicon.png')}}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('theme/admin/assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('theme/admin/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('theme/admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('theme/admin/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body class="auth-body-bg">
        <div>
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-lg-4">
                        <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                            <div class="w-100">
                                <div class="row justify-content-center">
                                    <div class="col-lg-9">
                                        <div>
                                            <div class="text-center">
                                                <div>
                                                    <a href="" class="">
                                                        <img src="{{asset('theme/admin/assets/images/logo.png')}}" alt="" height="100" class="auth-logo logo-dark mx-auto">
                                                        <img src="{{asset('theme/admin/assets/images/logo.png')}}" alt="" height="100" class="auth-logo logo-light mx-auto">
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="adminlogin p-2">
                                                <div class="contentWrapper">
                                                   
                                                  
                                                    <div class="content active" id="agent">
                                                        <form method="POST" action="{{ route('login') }}">
                                                            @csrf
                                                            <h4> Login </h4>
                                                            <div class="mb-3 mt-2 auth-form-group-custom mb-4">
    <i class="ri-user-2-line auti-custom-input-icon"></i>
    <label for="username">Username</label>
    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
    @error('username')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
                                            
                                                            <div class="mb-3 auth-form-group-custom mb-4">
                                                                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                                <label for="password">Password</label>
                                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                                @error('password')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
																		</span>
@enderror
</div>
<div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="customControlInline">
                                                            <label class="form-check-label" for="customControlInline">Remember me</label>
                                                        </div>
                                        
                                                        <div class="mt-4 text-center">
                                                            <button class="btn btn-primary w-lg waves-effect waves-light" type="submit">Log In</button>
                                                        </div>
														 <div class="mt-4 text-center">
                                                             <a href="{{ route('registration') }}" class="btn btn-primary w-lg waves-effect waves-light" style="background-color: darkviolet;">Agent Registration</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-5 text-center">
                                            <p>© <script>document.write(new Date().getFullYear())</script> Travexpo.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="authentication-bg">
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- JAVASCRIPT -->
    <script src="{{asset('theme/admin/assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/node-waves/waves.min.js')}}"></script>

    <script src="{{asset('theme/admin/assets/js/app.js')}}"></script>
    <script>
        const tabs = document.querySelector(".tabs");
        const tabButton = document.querySelectorAll(".navTab");
        const content = document.querySelectorAll(".content");
        
        tabs.addEventListener("click", e => {
          const id = e.target.dataset.toggle;
          if (id) {
            tabButton.forEach(navTab => {
              navTab.classList.remove("active");
            });
            e.target.classList.add("active");
          }
          content.forEach(content => {
            content.classList.remove("active");
          });
        
          const element = document.getElementById(id);
          element.classList.add("active");
        });
    </script>
</body>