<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CINÉMA JEANNE D'ARC - Programme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1><span style="font-size:1.3em; vertical-align:middle; margin-right:0.5rem;">🎬</span>CINÉMA JEANNE D'ARC<span style="font-size:1.3em; vertical-align:middle; margin-left:0.5rem;">🎥</span></h1>
        <div class="info-cinema">
            SAINT-MARS-LA-JAILLE<br>
            02 40 97 08 63
        </div>
        <nav class="headbar">
            <a href="index.html">Accueil</a>
            <a href="programme.php">Programme</a>
            <a href="tarif.html">Tarifs</a>
            <a href="plan.html">Plan</a>
        </nav>
    </header>
    <main>
        <h2>Programme à venir <span style="font-size:1.5rem;">🎟️🍿</span></h2>
        <div class="film-list">
        <?php
        $films = json_decode(file_get_contents('films.json'), true);
        foreach ($films as $film) {
            echo '<div class="film-item">';
            echo '<div class="film-img">';
            if (!empty($film['image'])) {
                echo '<img src="uploads/' . htmlspecialchars($film['image']) . '" alt="Affiche de ' . htmlspecialchars($film['titre']) . '" style="width:120px;height:160px;object-fit:cover;border-radius:8px;">';
            } else {
                echo '<span>🎬</span>';
            }
            echo '</div>';
            echo '<div class="film-info">';
            echo '<h3>' . htmlspecialchars($film['titre']) . '</h3>';
            echo '<p class="film-desc">' . htmlspecialchars($film['desc']) . '</p>';
            if (!empty($film['seances'])) {
                echo '<ul class="film-seances">';
                foreach ($film['seances'] as $seance) {
                    echo '<li>' . htmlspecialchars($seance) . '</li>';
                }
                echo '</ul>';
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 CINÉMA JEANNE D'ARC. Tous droits réservés.</p>
    </footer>
</body>
</html> 