<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

try {
    $conn = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_POST['step'])) {
    $step = $_POST['step'];

    if ($step === 'upload_csv') {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            die("Erreur lors du téléchargement du fichier.");
        }

        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $tmpName = $_FILES['csv_file']['tmp_name'];
        $fileName = time() . '_' . $_FILES['csv_file']['name'];
        $destination = $uploadDir . $fileName;

        if (!move_uploaded_file($tmpName, $destination)) {
            die("Impossible de sauvegarder le fichier importé.");
        }

        if (($handle = fopen($destination, 'r')) !== false) {
            $csvHeaders = fgetcsv($handle, 1000, ";");
            fclose($handle);
        } else {
            die("Impossible de lire le fichier pour extraire les colonnes.");
        }
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Choisir les colonnes</title>
        </head>
        <body>
        <h1>Sélectionnez les colonnes à exporter</h1>
        <form method="POST">
            <input type="hidden" name="csv_file_path" value="<?php echo htmlspecialchars($destination); ?>">
            <input type="hidden" name="step" value="export_json">
            <?php
            foreach ($csvHeaders as $header) {
                echo '<label>';
                echo '<input type="checkbox" name="selectedColumns[]" value="' . htmlspecialchars($header) . '"> ';
                echo htmlspecialchars($header);
                echo '</label><br>';
            }
            ?>
            <button type="submit">Exporter en JSON</button>
        </form>
        </body>
        </html>
        <?php
        exit;
    }

    if ($step === 'export_json') {
        if (!isset($_POST['csv_file_path']) || !isset($_POST['selectedColumns'])) {
            die("Données manquantes pour l'export JSON.");
        }

        $csvFilePath     = $_POST['csv_file_path'];
        $selectedColumns = $_POST['selectedColumns'];

        if (!file_exists($csvFilePath)) {
            die("Fichier CSV introuvable.");
        }
        if (($handle = fopen($csvFilePath, 'r')) === false) {
            die("Impossible de lire le fichier CSV.");
        }

        $csvHeaders = fgetcsv($handle, 1000, ";");
        $selectedIndexes = [];
        foreach ($selectedColumns as $col) {
            $index = array_search($col, $csvHeaders);
            if ($index !== false) {
                $selectedIndexes[] = $index;
            }
        }

        $jsonData = [];
        while (($data = fgetcsv($handle, 1000, ";")) !== false) {
            $row = [];
            foreach ($selectedIndexes as $idx) {
                $colName = $csvHeaders[$idx];
                $row[$colName] = isset($data[$idx]) ? $data[$idx] : null;
            }
            $jsonData[] = $row;
        }
        fclose($handle);

        $fileName = 'export_' . time() . '.json';
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo json_encode($jsonData, JSON_PRETTY_PRINT);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Importer un fichier CSV</title>
</head>
<body>
<h1>Importer un fichier CSV</h1>
<form method="POST" enctype="multipart/form-data">
    <label for="csv_file">Choisir un fichier CSV :</label>
    <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
    <input type="hidden" name="step" value="upload_csv">
    <button type="submit">Envoyer</button>
</form>
</body>
</html>
