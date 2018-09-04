<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PHP Ajax Authorization</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
  </head>

  <body>

    <div class="container">

      <?php if (Auth::isAuthorized()): ?>
    
      

      <?php else: ?>

      <form class="form-signin ajax" method="post" action="/auth?action=ajax">
        <div class="main-error alert alert-error hide"></div>

        <h2 class="form-signin-heading">Please sign in</h2>
        <input name="username" type="text" class="input-block-level" placeholder="Username" autofocus>
        <input name="password" type="password" class="input-block-level" placeholder="Password">
        <label class="checkbox">
          <input name="remember-me" type="checkbox" value="remember-me" checked> Remember me
        </label>
        <input type="hidden" name="act" value="login">
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
    
        <div class="alert alert-info" style="margin-top:15px;">
            <p>Not have an account? <a href="/auth?action=register">Register it.</a>
        </div>
      </form>

      <?php endif; ?>

    </div> <!-- /container -->

    <script src="/assets/vendor/jquery-2.0.3.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/ajax-form.js"></script>

  </body>
</html>



