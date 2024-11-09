<?php
    session_start();
    session_unset(); // Usuwa wszystkie zmienne sesji
    session_destroy(); // Zniszczenie sesji

    header('Location: index3.php'); // Przekierowanie na stronę logowania lub inną stronę
    exit();
?>
