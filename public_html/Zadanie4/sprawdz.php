<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>

<BODY>
    <?php
    session_start();
    $login = htmlentities ($_POST['login'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
    $pass = htmlentities ($_POST['password'], ENT_QUOTES, "UTF-8");
    $link=mysqli_connect("localhost","serwer305998_z4","Zadankonr4!","serwer305998_z4");
    if (!$link) {
        echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
    } // obsługa błędu połączenia z BD
    mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków
    $result = mysqli_query($link, "SELECT * FROM users WHERE login='$login'"); 
    $rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
    if (!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
    {   
        echo "<p>Nie ma takiego użytkownika!</p>";
        mysqli_close($link); // zamknięcie połączenia z BD
        echo "<a href='Zaloguj.php'>Sprobuj ponownie</a>";
        exit(); // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
    } else { // jeśli $rekord istnieje
        if ($rekord['password'] == $pass) // czy hasło zgadza się z BD
        {
            $_SESSION['loggedin'] = true;
            $_SESSION['login'] = $login; // Przypisanie loginu do sesji
            $_SESSION['avatar'] = $rekord['avatar']; // Pobranie ścieżki do avatara z bazy danych   
            $_SESSION['user_id'] = $rekord['id'];    // Przypisanie user_id do sesji
            header("Location: panelZalogowany.php");
            exit(); 
            
        } else {
            
            mysqli_close($link);
            header("Location: Zaloguj.php");
            exit(); // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
        }
    }
    ?>
</BODY>

</HTML>