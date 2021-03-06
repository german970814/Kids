<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>App - @yield('title')</title>

  <!-- PLUGINS CSS STYLE -->
  @include('layouts/styles')

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="body-wrapper">
  @include('layouts/preloader')

  <div class="main-wrapper">
    @include('layouts/header')

    @yield('content')
	
    @include('layouts/footer')
  </div>
 
  @section('modals')
    @auth
      <div></div>
    @else
      <div class="modal fade customModal" id="loginModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="panel panel-default formPanel">
              <div class="panel-heading bg-color-1 border-color-1">
                <h3 class="panel-title">Login</h3>
              </div>
              <div class="panel-body">
                <form action="#" method="POST" role="form">
                  <div class="form-group formField">
                    <input type="text" class="form-control" placeholder="User name">
                  </div>
                  <div class="form-group formField">
                    <input type="password" class="form-control" placeholder="Password">
                  </div>
                  <div class="form-group formField">
                    <input type="submit" class="btn btn-primary btn-block bg-color-3 border-color-3" value="Log in">
                  </div>
                  <div class="form-group formField">
                    <p class="help-block"><a href="#">Forgot password?</a></p>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endauth
  @show

  @include('layouts/scripts')
</body>
</html>

