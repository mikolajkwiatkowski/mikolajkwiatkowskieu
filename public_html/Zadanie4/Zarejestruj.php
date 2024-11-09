<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head><script src="../assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Kwiatkowski Mikołaj</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/checkout/">

    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }

      .bd-mode-toggle {
        z-index: 1500;
      }

      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="css/checkout.css" rel="stylesheet">
  </head>
  

    

    
<div class="container">
  <main>
   

    <div class="row g-5">
      
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3 mt-5">Zarejestruj się</h4>
        <form class="needs-validation" method="post" action="dodaj.php" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-12">
              <label for="username" class="form-label">Login</label>
              <div class="input-group has-validation">
                <input type="text" class="form-control" name="login" placeholder="Login" required>
              </div>
            </div>
            <div class="col-12">
              <label for="username" class="form-label">Haslo</label>
              <div class="input-group has-validation">
                <input type="password" class="form-control" name="password1" placeholder="Hasło" required>
                <input type="password" class="form-control" name="password2" placeholder="Powtórz hasło" required>
              </div>
            </div>  
            <div class="col-12">
              <label for="username" class="form-label">Avatar</label>
              <div class="input-group has-validation">
                <input type="file" class="form-control" name="avatar" placeholder="Avatar" >
              </div>
            </div>     
            <div class="col-12 mt-3">
              <input type="checkbox" class="form-check-input" id="isWorker" name="isWorker">
              <label class="form-check-label" for="isWorker">Jestem pracownikiem</label>
            </div>     
          </div>  
        </div>
          
          <button class="w-100 btn btn-primary btn-lg" type="submit">Zarejestruj</button>
          <p class="mt-5 mb-3 text-body-secondary">Masz już konto? <a href="Zaloguj.php">Zaloguj sie</a></p>

        </form>
      </div>
    </div>
  </main>

  
</div>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script src="checkout.js"></script></body>
</html>
