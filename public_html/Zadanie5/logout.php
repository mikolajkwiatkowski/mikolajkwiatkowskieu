<?php
    session_start();
    session_unset(); // Usuwa wszystkie zmienne sesji
    session_destroy(); // Zniszczenie sesji

    header('Location: Zadanie5.php'); // Przekierowanie na stronę logowania lub inną stronę
    exit();
?>