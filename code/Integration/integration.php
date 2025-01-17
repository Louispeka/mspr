<?php
/*****************************************************
 *         Étape 0 : Chargement du .env (optionnel)
 *****************************************************/
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

// Charger les variables d'environnement depuis le fichier .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Connexion à la base de données avec les variables d'environnement
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

/*****************************************************
 *         Gestion des différentes étapes
 *****************************************************/

if (isset($_POST['step'])) {
    $step = $_POST['step'];

    // -------------------------------------------------------------------
    // Step 1 : Traitement du fichier uploadé => on stocke le fichier
    // -------------------------------------------------------------------
    if ($step === 'upload_csv') {
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            die("Erreur lors du téléchargement du fichier.");
        }

        // Créer le dossier uploads s'il n'existe pas
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $tmpName = $_FILES['csv_file']['tmp_name'];
        $fileName = time() . '_' . $_FILES['csv_file']['name']; // nom unique
        $destination = $uploadDir . $fileName;

        // Déplacer le fichier dans le dossier uploads
        if (!move_uploaded_file($tmpName, $destination)) {
            die("Impossible de sauvegarder le fichier importé.");
        }

        // Lire la première ligne pour récupérer les headers
        if (($handle = fopen($destination, 'r')) !== false) {
            $csvHeaders = fgetcsv($handle, 1000, ";");
            fclose($handle);
        } else {
            die("Impossible de lire le fichier pour extraire les colonnes.");
        }

        // Affichage : on va à l'étape 2 (sélection des colonnes)
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Étape 2 : Sélection des colonnes</title>
        </head>
        <body>
            <h1>Étape 2 : Sélectionnez les colonnes à exporter</h1>
            <form method="POST">
                <?php
                // On stocke le chemin du fichier dans un input caché
                echo '<input type="hidden" name="csv_file_path" value="' . htmlspecialchars($destination) . '">';
                echo '<input type="hidden" name="step" value="select_columns">';

                // Affichage des cases à cocher
                foreach ($csvHeaders as $header) {
                    echo '<label>';
                    echo '<input type="checkbox" name="selectedColumns[]" value="' . htmlspecialchars($header) . '"> ';
                    echo htmlspecialchars($header);
                    echo '</label><br>';
                }
                ?>
                <button type="submit">Suivant</button>
            </form>
        </body>
        </html>
        <?php
        exit;
    }

    // -------------------------------------------------------------------
    // Step 2 : Récupérer colonnes sélectionnées => Prévisualisation
    // -------------------------------------------------------------------
    if ($step === 'select_columns') {
        if (!isset($_POST['csv_file_path']) || !isset($_POST['selectedColumns'])) {
            die("Données manquantes pour la sélection des colonnes.");
        }

        $csvFilePath = $_POST['csv_file_path'];
        $selectedColumns = $_POST['selectedColumns'];

        // On relit le fichier pour en extraire un aperçu
        if (!file_exists($csvFilePath)) {
            die("Fichier CSV introuvable.");
        }

        if (($handle = fopen($csvFilePath, 'r')) === false) {
            die("Impossible de lire le fichier CSV.");
        }

        // Récupérer la ligne d'entête complète
        $csvHeaders = fgetcsv($handle, 1000, ";");

        // On calcule l'index des colonnes sélectionnées
        $selectedIndexes = [];
        foreach ($selectedColumns as $col) {
            $index = array_search($col, $csvHeaders);
            if ($index !== false) {
                $selectedIndexes[] = $index;
            }
        }

        // On va récupérer quelques lignes (par exemple 5) pour prévisualiser
        $previewData = [];
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ";")) !== false && $count < 5) {
            $row = [];
            foreach ($selectedIndexes as $idx) {
                $colName = $csvHeaders[$idx];
                $row[$colName] = isset($data[$idx]) ? $data[$idx] : null;
            }
            $previewData[] = $row;
            $count++;
        }
        fclose($handle);

        // On affiche la prévisualisation (étape 3)
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Étape 3 : Prévisualisation</title>
        </head>
        <body>
            <h1>Étape 3 : Prévisualisation des premières lignes</h1>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <?php foreach ($selectedColumns as $colHeader) : ?>
                            <th><?php echo htmlspecialchars($colHeader); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($previewData as $row) : ?>
                        <tr>
                            <?php foreach ($row as $value) : ?>
                                <td><?php echo htmlspecialchars($value); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="POST">
                <input type="hidden" name="csv_file_path" value="<?php echo htmlspecialchars($csvFilePath); ?>">
                <?php
                // On repasse les colonnes sélectionnées
                foreach ($selectedColumns as $col) {
                    echo '<input type="hidden" name="selectedColumns[]" value="' . htmlspecialchars($col) . '">';
                }
                ?>
                <input type="hidden" name="step" value="export_json">
                <button type="submit">Exporter en JSON</button>
            </form>
        </body>
        </html>
        <?php
        exit;
    }

    // -------------------------------------------------------------------
    // Step 3 : Export en JSON
    // -------------------------------------------------------------------
    if ($step === 'export_json') {
        if (!isset($_POST['csv_file_path']) || !isset($_POST['selectedColumns'])) {
            die("Données manquantes pour l'export JSON.");
        }

        $csvFilePath = $_POST['csv_file_path'];
        $selectedColumns = $_POST['selectedColumns'];

        if (!file_exists($csvFilePath)) {
            die("Fichier CSV introuvable.");
        }

        if (($handle = fopen($csvFilePath, 'r')) === false) {
            die("Impossible de lire le fichier CSV.");
        }

        // Récupérer la ligne d'entête complète
        $csvHeaders = fgetcsv($handle, 1000, ";");

        // On calcule l'index des colonnes sélectionnées
        $selectedIndexes = [];
        foreach ($selectedColumns as $col) {
            $index = array_search($col, $csvHeaders);
            if ($index !== false) {
                $selectedIndexes[] = $index;
            }
        }

        // Génération du tableau final
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

        // Écriture du fichier JSON
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $jsonFileName = 'export_' . time() . '.json';
        $jsonFilePath = $uploadDir . $jsonFileName;

        // On écrit le JSON de manière "jolie"
        file_put_contents($jsonFilePath, json_encode($jsonData, JSON_PRETTY_PRINT));

        // Affichage d'un lien pour télécharger le fichier
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Export terminé</title>
        </head>
        <body>
            <h1>Fichier JSON généré</h1>
            <p><a href="uploads/<?php echo htmlspecialchars($jsonFileName); ?>" download>Télécharger le fichier JSON</a></p>
        </body>
        </html>
        <?php
        exit;
    }
}

/*****************************************************
 *         Page par défaut (Step 1 : upload)
 *****************************************************/

// Si on n'est pas dans un POST avec un step, on affiche le formulaire initial
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Importer un fichier CSV</title>
</head>
<body>
    <h1>Étape 1 : Importer un fichier CSV</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="csv_file">Choisir un fichier CSV :</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <input type="hidden" name="step" value="upload_csv">
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
