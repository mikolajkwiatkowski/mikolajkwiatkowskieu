<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <meta name="description" content="" />
            <meta name="author" content="" />
            <title>Kwiatkowski Mikołaj</title>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
            <!-- Core theme CSS (includes Bootstrap)-->
            <link href="css/styles.css" rel="stylesheet" />
        </head>
        <body>
            <?php  
                session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
                if (!isset($_SESSION['loggedin']))
                {
                    header('Location: Zaloguj.php');
                    exit();
            
                }
                


            ?>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <a class="navbar-brand" href="#">Zadanie 2 - Bootstrap</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="logout.php"><i class="fa fa-sign-out"></i>Wyloguj się</a></li>
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="logout.php"><?php echo $_SESSION['login']?></li>
                            <img src="<?php echo $_SESSION['avatar']; ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">

                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Polecenia</a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="polecenie1_1.php">Polecenie1_1</a></li>
                                    <li><a class="dropdown-item" href="polecenie1_2.php">Polecenie1_2</a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li><a class="dropdown-item" href="polecenie2_1.php">Polecenie2_1</a></li>
                                    <li><a class="dropdown-item" href="polecenie2_2.php">Polecenie2_2</a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li><a class="dropdown-item" href="polecenie3_1.php">Polecenie3_1</a></li>
                                    <li><a class="dropdown-item" href="polecenie3_2.php">Polecenie3_2</a></li>
                                    <li><a class="dropdown-item" href="polecenie3_3.php">Polecenie3_3</a></li>
                                    
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <div class="container">
                <div class="text-center mt-5">
                    <h1>polecenie 3_1</h1>
                    
                </div>
            </div>
            <!-- Bootstrap core JS-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Core theme JS-->
            <script src="js/scripts.js"></script>
        </body>
    </html>
