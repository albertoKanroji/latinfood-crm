<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>K&D Latin Food Inc</title>
    <link rel="icon" type="image/x-icon" href="assets/img/aa.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/structure.css') }}" rel="stylesheet" type="text/css" class="structure" />
    <link href="{{ asset('assets/css/authentication/form-1.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/switches.css') }}">
</head>
<body class="form">


    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                    <img 
                   src="https://firebasestorage.googleapis.com/v0/b/latin-food-8635c.appspot.com/o/splash%2FlogoAnimadoNaranjaLoop.gif?alt=media&token=0f2cb2ee-718b-492c-8448-359705b01923"
                    width="400"
     height="341" autoplay="true" loop="true">
                        <h3 style="color:#FF5100"  class="text-center"><span class="brand-name1"><b>K&D Latin Food ,Inc</b></span></h3>   
                        <h6 class="text-center"><span class="brand-name">Department Of Technology V.1.0</span></h6>                           
                        <form class="text-left mt-5" action="{{ route('login') }}" method="POST">
                              @csrf
                            <div class="form" >

                                <div id="username-field" class="field-wrapper input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div id="password-field" class="field-wrapper input mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
                                     @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <!--
                                <div class="d-flex align-items-center"> -->
                                   <!--
                                    <div class="field-wrapper toggle-pass">
                                        <p class="d-inline-block">Show Password</p>
                                        <label class="switch s-primary">
                                            <input type="checkbox" id="toggle-password" class="d-none">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                -->
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-dark btn-block" value="">Login</button>
                                </div>

                           <!--  </div> -->
<!--
                                <div class="field-wrapper text-center keep-logged-in">
                                    <div class="n-chk new-checkbox checkbox-outline-primary">
                                        <label class="new-control new-checkbox checkbox-outline-primary">
                                          <input type="checkbox" class="new-control-input">
                                          <span class="new-control-indicator"></span>Keep me logged in
                                        </label>
                                    </div>
                                </div>

                                <div class="field-wrapper">
                                    <a href="auth_pass_recovery.html" class="forgot-pass-link">Forgot Password?</a>
                                </div>
                            -->
                        </div>
                    </form>                        
                    <p class="terms-conditions text-center">© 2023 All Rights Reserved. <a href="#" target="_blank"><br>Design by OyarceGroup.com</a> <br> </p>

                </div>                    
            </div>
        </div>
    </div>
    <div class="form-image">
        <a href="#" target="_blank">
        <div class="l-image">
        </div>
    </a>
    </div>
</div>


</body>
</html>