<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Kwiatkowski Mikolaj</title>
</head>
<body>
    
    <?php  
        session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
        if (!isset($_SESSION['loggedin']))
        {
            header('Location: index3.php');
            exit();
    
        }
        else{
            echo "Jestes zalogowany !!!!!";
            echo '<br><a href="logout.php">Wyloguj się</a>';
        }


    ?>
</body>
</html>