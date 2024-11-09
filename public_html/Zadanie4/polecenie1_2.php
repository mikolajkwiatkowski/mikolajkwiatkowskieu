<?php
    session_start(); // zapewnia dostęp do zmiennych sesyjnych
    if (!isset($_SESSION['loggedin'])) {
        header('Location: Zaloguj.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kwiatkowski Mikołaj</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="css/styles.css" rel="stylesheet" />

    <!-- jQuery (wymagane do AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Funkcja do pobierania danych z index1.php
        function loadIndexData() {
            $.ajax({
                url: 'tabela_hostow.php', // Ładuje dane z index1.php
                method: 'GET',
                success: function(data) {
                    $('#indexDataContainer').html(data); // Wyświetla wyniki w divie
                }
            });
        }

        // Inicjalizacja odświeżania co 10 sekund
        $(document).ready(function() {
            loadIndexData(); // Pierwsze pobranie danych
            setInterval(loadIndexData, 10000); // Odświeżanie co 10 sekund
        });
    </script>
</head>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Zadanie 4</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="logout.php"><i class="fa fa-sign-out"></i>Wyloguj się</a></li>
                        <li class="nav-item"><a class="nav-link active" aria-current="page"><?php echo $_SESSION['login'] ?></li>
                        <a href="panelZalogowany.php"><img src="<?php echo $_SESSION['avatar']; ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;"></a>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Polecenia</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="polecenie1_1.php">test.php</a></li>
                                <li><a class="dropdown-item" href="polecenie1_2.php">Tabela hostów</a></li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li><a class="dropdown-item" href="polecenie2_1.php">Dodaj hosta</a></li>
                                <li><a class="dropdown-item" href="polecenie2_2.php">Usun hosta</li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li><a class="dropdown-item" href="staff.php">staff</a></li>
                            </ul>
                        </li>

                        
                    </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="text-center mt-5">
            <div id="indexDataContainer"></div>
        </div>
    </div>
      
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
