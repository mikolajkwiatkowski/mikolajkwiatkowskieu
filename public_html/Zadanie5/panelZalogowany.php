<?php
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin'])) {
    header('Location: Zaloguj.php');
    exit();
}
$userDir = $_SESSION['userDir'];

// Określenie bieżącego katalogu (jeśli użytkownik przeszedł do podkatalogu)
$currentDir = $userDir;
if (isset($_GET['dir'])) {
    // Jeśli parametr "dir" jest ustawiony, zmień bieżący katalog na podkatalog
    $currentDir = $userDir . '/' . $_GET['dir'];
}

// Pobieramy pliki i podkatalogi w bieżącym katalogu
$files = array_diff(scandir($currentDir), array('.', '..'));

// Funkcja do usuwania pliku lub katalogu
function deleteItem($path) {
    if (is_dir($path)) {
        // Jeśli to katalog, usuwamy najpierw zawartość, a potem katalog
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            $filePath = $path . '/' . $file;
            deleteItem($filePath);  // Rekursywnie usuwamy pliki w katalogu
        }
        rmdir($path);  // Usuwamy pusty katalog
    } else {
        unlink($path);  // Usuwamy plik
    }
}

// Sprawdzamy, czy użytkownik chce usunąć jakiś plik lub katalog
if (isset($_GET['delete'])) {
    $deletePath = $currentDir . '/' . $_GET['delete'];
    if (file_exists($deletePath)) {
        deleteItem($deletePath);
        header("Location: panelZalogowany.php"); // Przekierowanie po usunięciu
    } else {
        echo "<script>alert('Błąd: Plik lub katalog nie istnieje.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="panelZalogowany.php">Zadanie 5</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="logout.php"><i class="fa fa-sign-out"></i>Wyloguj się</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="panelZalogowany.php"><?php echo $_SESSION['login'] ?></a></li>
                    <img src="<?php echo $_SESSION['avatar']; ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Polecenia</a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="polecenie1_1.php">netstat.php</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="geolokalizacja.php">Geolokalizacja</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Witaj, <?php echo $_SESSION['login']; ?>!</h1>
        <h3>Twoje pliki i katalogi:</h3>

        <?php if ($currentDir != $userDir): ?>
            <!-- Ikona powrotu do katalogu macierzystego -->
            <a href="panelZalogowany.php" class="btn btn-secondary">
                <i class="fa fa-level-up"></i> Powrót do katalogu głównego
            </a>
            <br><br>
        <?php endif; ?>

        <?php if (empty($files)): ?>
            <p>Brak plików ani podkatalogów w tym katalogu.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($files as $file): ?>
                    <li>
                        <?php 
                        $filePath = $currentDir . '/' . $file;
                        if (is_dir($filePath)) {  
                            echo "[Katalog] <a href='panelZalogowany.php?dir=" . urlencode($file) . "'>$file</a>";
                        } else {
                            echo $file;
                        }
                        ?>
                        <!-- Przycisk do usunięcia pliku lub katalogu -->
                        <a href="panelZalogowany.php?delete=<?php echo urlencode($file); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć ten element?');">
                            <i class="fa fa-trash"></i> Usuń
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="select.php">Dodaj plik</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
