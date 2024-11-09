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
<style>
    th {
        border: 1px solid black;
        padding: 10px;
        text-align: left;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px 0;
    }

    tr {
        background-color: #f2f2f2;
        border: 1px solid #ddd;
    }
</style>

<body>
    <?php
    session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
    if (!isset($_SESSION['loggedin'])) {
        header('Location: Zaloguj.php');
        exit();
    }

    // Połączenie z bazą danych
    $link = mysqli_connect("localhost", "serwer305998_z3", "Zadankonr3!", "serwer305998_z3");
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
        exit();
    }

    mysqli_query($link, "SET NAMES 'utf8'");

    // Funkcja do pobierania danych o lokalizacji na podstawie adresu IP
    function ip_details($ip) {
        $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
        $details = json_decode($json);
        return $details;
    }

    // Pobranie danych z tabeli goscieportalu
    $query = "SELECT * FROM goscieportalu";
    $result = mysqli_query($link, $query);
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="panelZalogowany.php">Zadanie 3 - Geolokalizacja</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="logout.php"><i class="fa fa-sign-out"></i>Wyloguj się</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="logout.php"><?php echo htmlspecialchars($_SESSION['login']); ?></a></li>
                    <img src="<?php echo htmlspecialchars($_SESSION['avatar']); ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Polecenia</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="polecenie1_1.php">Polecenie1_1</a></li>
                            <li><a class="dropdown-item" href="polecenie1_2.php">Polecenie1_2</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="polecenie2_1.php">Polecenie2_1</a></li>
                            <li><a class="dropdown-item" href="polecenie2_2.php">Polecenie2_2</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
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
            <table>
                <tr>
                    <th>Adres IP</th>
                    <th>Data</th>
                    <th>Liczba wejść</th>
                    <th>Przeglądarka</th>
                    <th>Rozdzielczość Ekranu</th>
                    <th>Rozdzielczość Okna</th>
                    <th>Ilość kolorów</th>
                    <th>Ciasteczka</th>
                    <th>Java</th>
                    <th>Język</th>
                    <th>Lokalizacja</th>
                </tr>
                <?php
                // Sprawdź, czy są jakieś dane do wyświetlenia
                if (mysqli_num_rows($result) > 0) {
                    // Wyświetl każdy wiersz danych
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['AdresIP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DataICzas']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['LiczbaWejsc']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Przeglądarka']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['RozdzielczoscEkranu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['RozdzielczoscOkna']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['IloscKolorow']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Ciasteczka']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Java']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Język']) . "</td>";

                        // Pobierz lokalizację na podstawie adresu IP
                        $ip = htmlspecialchars($row['AdresIP']);
                        $details = ip_details($ip);
                        $loc = $details->loc; // koordynaty w formacie "szerokość,długość"
                        
                        // Tworzenie linku do Google Maps na podstawie koordynatów
                        $lokalizacja = "<a href='https://www.google.pl/maps/place/$loc'>LINK</a>";
                        echo "<td>" . $lokalizacja . "</td>"; // Dodaj link do lokalizacji
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Brak danych do wyświetlenia.</td></tr>";
                }
                // Zamknij połączenie z bazą danych
                mysqli_close($link);
                ?>
            </table>
        </div>
    </div>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>
