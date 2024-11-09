<?php
session_start();

$AdresIP = $_SERVER["REMOTE_ADDR"];
$DataLogowania = date("Y-m-d H:i:s");  // Bieżąca data i czas dla próby logowania

$login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
$pass = htmlentities($_POST['password'], ENT_QUOTES, "UTF-8");

$link = mysqli_connect("localhost", "serwer305998_z5", "Zadankonr5!", "serwer305998_z5");
if (!$link) {
    echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    exit();
}

mysqli_query($link, "SET NAMES 'utf8'");

// Sprawdzenie użytkownika w tabeli
$result1 = mysqli_query($link, "SELECT * FROM users WHERE login='$login'");
$rekord1 = mysqli_fetch_array($result1);

$SukcesLogowania = 0;  // Domyślnie ustawiamy na "0" (nieudane logowanie)

if (!$rekord1) {
    // Logowanie nieudane: użytkownik nie istnieje
    echo "<p>Nie ma takiego użytkownika!</p>";
    mysqli_close($link);
    echo "<a href='Zaloguj.php'>Spróbuj ponownie</a>";
} else {
    if ($rekord1['password'] == $pass) {
        // Logowanie udane
        $_SESSION['loggedin'] = true;
        $_SESSION['login'] = $login;
        $_SESSION['avatar'] = $rekord1['avatar'];
        $_SESSION['userDir'] = 'usersCatalogs/' . $login;


        $SukcesLogowania = 1;  // Logowanie udane
    } else {
        // Logowanie nieudane: błędne hasło
        echo "<p>Nieprawidłowe hasło!</p>";
    }
}

// Zarejestruj próbę logowania w tabeli goscieportalu
$query = "INSERT INTO goscieportalu (AdresIP, SukcesLogowania, DataLogowania) 
          VALUES (?, ?, ?)";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'sis', $AdresIP, $SukcesLogowania, $DataLogowania);
mysqli_stmt_execute($stmt);

// Zamknięcie połączenia i przekierowanie
mysqli_stmt_close($stmt);
mysqli_close($link);

if ($SukcesLogowania) {
    header("Location: panelZalogowany.php");
} else {
    header("Location: Zaloguj.php");
}
exit();
?>
