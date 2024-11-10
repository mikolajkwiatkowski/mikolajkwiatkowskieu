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

// Funkcja do generowania miniaturki obrazu
function getImageThumbnail($filePath) {
    $imgInfo = getimagesize($filePath);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($imgInfo['mime'], $allowedTypes)) {
        return '<img src="' . $filePath . '" style="width: 100px; height: auto;"/>';
    }
    return '';
}

// Funkcja do generowania odtwarzacza audio
function getAudioPlayer($filePath) {
    return '<audio controls><source src="' . $filePath . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
}

// Funkcja do generowania odtwarzacza wideo
function getVideoPlayer($filePath) {
    return '<video controls><source src="' . $filePath . '" type="video/mp4">Your browser does not support the video element.</video>';
}

// Funkcja do generowania linku do pobrania
function getDownloadLink($filePath) {
    return '<a href="download.php?file=' . urlencode(basename($filePath)) . '&dir=' . urlencode($_GET['dir'] ?? '') . '" class="btn btn-success btn-sm">Pobierz</a>';
}


// Sprawdzanie, czy użytkownik wysłał żądanie utworzenia nowego folderu
if (isset($_POST['createFolder'])) {
    $folderName = trim($_POST['folderName']);
    
    // Sprawdzenie, czy nazwa folderu jest poprawna
    if (!empty($folderName)) {
        // Ścieżka do nowego folderu
        $newFolderPath = $currentDir . '/' . $folderName;
        
        // Tworzenie folderu, jeśli jeszcze nie istnieje
        if (!file_exists($newFolderPath)) {
            mkdir($newFolderPath, 0777, true);
            header("Location: panelZalogowany.php?dir=" . urlencode($_GET['dir'] ?? ''));
            exit();
        } else {
            echo "<script>alert('Folder o tej nazwie już istnieje.');</script>";
        }
    } else {
        echo "<script>alert('Proszę podać nazwę folderu.');</script>";
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
                        
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Witaj, <?php echo $_SESSION['login']; ?>!</h1>
        <h3>Twoje pliki i katalogi:</h3>

        <!-- Jeśli nie jesteś w katalogu głównym, wyświetl przycisk powrotu -->
        <?php if ($currentDir != $userDir): ?>
            <a href="panelZalogowany.php" class="btn btn-secondary mb-3">
                <i class="fa fa-level-up"></i> Powrót do katalogu głównego
            </a>
        <?php endif; ?>

        <!-- Jeśli nie ma plików -->
        <?php if (empty($files)): ?>
            <p>Brak plików ani podkatalogów w tym katalogu.</p>
        <?php else: ?>
            <!-- Wyświetlanie plików w tabeli -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Element</th>
                            <th>Podgląd</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($files as $file): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $filePath = $currentDir . '/' . $file;
                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                                    // Wyświetlanie podkatalogów
                                    if (is_dir($filePath)) {
                                        echo "[Katalog] <a href='panelZalogowany.php?dir=" . urlencode($_GET['dir'] ?? '') . '/' . urlencode($file) . "'>$file</a>";
                                    } else {
                                        echo $file . ' ';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    // Obsługa podglądu mediów
                                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                        echo getImageThumbnail($filePath);
                                    } elseif (in_array($fileExtension, ['mp3', 'wav'])) {
                                        echo getAudioPlayer($filePath);
                                    } elseif (in_array($fileExtension, ['mp4', 'mov', 'avi'])) {
                                        echo getVideoPlayer($filePath);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <!-- Przycisk usunięcia pliku lub katalogu -->
                                    <a href="panelZalogowany.php?delete=<?php echo urlencode($file); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć ten element?');">
                                        <i class="fa fa-trash"></i> Usuń
                                    </a>
                                    <!-- Link do pobrania pliku -->
                                    <?php echo getDownloadLink($filePath); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
<!-- Formularz do dodania nowego folderu -->
        <div class="mt-4">
            <form action="panelZalogowany.php" method="POST" class="d-flex align-items-center">
                <input type="text" name="folderName" placeholder="Nazwa nowego folderu" required class="form-control me-2">
                <button type="submit" name="createFolder" class="btn btn-primary">
                    <i class="fa fa-folder-plus"></i> Dodaj folder
                </button>
            </form>
        </div>

        <!-- Link do dodania pliku -->
        <a href="select.php?dir=<?php echo urlencode(isset($_GET['dir']) ? $_GET['dir'] : ''); ?>" class="btn btn-primary mt-3">
            <i class="fa fa-upload"></i> Dodaj plik
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
