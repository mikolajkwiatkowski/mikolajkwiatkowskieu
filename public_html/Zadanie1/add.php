    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

    <HEAD>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </HEAD>

    <BODY>
        <?php
        session_start();
        $user = htmlentities($_POST['user'], ENT_QUOTES, "UTF-8");
        $pass = htmlentities($_POST['pass'], ENT_QUOTES, "UTF-8");
        $pass2 = htmlentities($_POST['pass2'], ENT_QUOTES, "UTF-8");

        $link = mysqli_connect("localhost", "serwer305998_z1", "NIEwiadomko123!", "serwer305998_z1");

        if (!$link) {
            echo "Błąd: " . mysqli_connect_errno() . " " . mysqli_connect_error();
            exit();
        }

        mysqli_query($link, "SET NAMES 'utf8'");

        if ($pass != $pass2) {
            echo "Hasła różnią się od siebie! Spróbuj jeszcze raz<br>";
            echo "<a href='rejestruj.php'>Wróć</a>";
            mysqli_close($link);
            exit();
        }

        // Użycie zapytania przygotowanego, aby bezpiecznie dodać dane
        $stmt = mysqli_prepare($link, "INSERT INTO users (username, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $user, $pass);

        if (mysqli_stmt_execute($stmt)) {
            echo "Użytkownik dodany pomyślnie!";
            echo "<br><a href='index3.php'>Zaloguj się</a>";
        } else {
            echo "Błąd: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
        ?>

    </BODY>

    </HTML>