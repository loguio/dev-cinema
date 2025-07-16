<?php
session_start();

// --- CONFIG ---
$PASSWORD = 'cinema2024'; // À changer !
$FILMS_FILE = 'films.json';
$UPLOAD_DIR = 'uploads/';

// --- Auth ---
if (!isset($_SESSION['admin'])) {
    if (isset($_POST['password']) && $_POST['password'] === $PASSWORD) {
        $_SESSION['admin'] = true;
        header('Location: admin.php'); exit;
    }
    echo '<form method="post" style="margin:2rem auto;max-width:300px;text-align:center;"><h2>Connexion admin</h2><input type="password" name="password" placeholder="Mot de passe" style="padding:0.5rem;width:100%;margin-bottom:1rem;"><br><button type="submit" style="padding:0.5rem 1.2rem;">Connexion</button></form>';
    exit;
}

// --- Load films ---
$films = file_exists($FILMS_FILE) ? json_decode(file_get_contents($FILMS_FILE), true) : [];

// --- Add film ---
if (isset($_POST['add_film'])) {
    $titre = trim($_POST['titre']);
    $desc = trim($_POST['desc']);
    $seances = array_filter(array_map('trim', explode("\n", $_POST['seances'])));
    $img = '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $img = uniqid('film_').'.'.$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $UPLOAD_DIR.$img);
    }
    $films[] = [
        'titre' => $titre,
        'desc' => $desc,
        'image' => $img,
        'seances' => $seances
    ];
    file_put_contents($FILMS_FILE, json_encode($films, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    header('Location: admin.php'); exit;
}

// --- Delete film ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (isset($films[$id])) {
        if ($films[$id]['image']) @unlink($UPLOAD_DIR.$films[$id]['image']);
        array_splice($films, $id, 1);
        file_put_contents($FILMS_FILE, json_encode($films, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }
    header('Location: admin.php'); exit;
}
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des films</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f8f6f6; color:#222; margin:0; padding:0; }
        .container { max-width: 600px; margin: 2rem auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #e6b3b3; padding: 2rem; }
        h1 { color: #c1121f; text-align:center; }
        form { margin-bottom:2.5rem; }
        input, textarea { width:100%; padding:0.5rem; margin-bottom:1rem; border-radius:6px; border:1px solid #ccc; }
        button { background:#e63946; color:#fff; border:none; padding:0.6rem 1.5rem; border-radius:6px; font-weight:bold; cursor:pointer; }
        button:hover { background:#780000; }
        .film-list { margin-top:2rem; }
        .film-item { background:#f3eaea; border-radius:8px; padding:1rem; margin-bottom:1.2rem; display:flex; align-items:center; gap:1rem; }
        .film-item img { width:60px; height:80px; object-fit:cover; border-radius:6px; }
        .film-info { flex:1; }
        .delete-btn { background:#c1121f; color:#fff; border:none; border-radius:5px; padding:0.3rem 0.8rem; margin-left:1rem; cursor:pointer; }
        .delete-btn:hover { background:#e63946; }
    </style>
</head>
<body>
<div class="container">
    <h1>Gestion des films</h1>
    <form method="post" enctype="multipart/form-data">
        <h2>Ajouter un film</h2>
        <input type="text" name="titre" placeholder="Titre du film" required>
        <textarea name="desc" placeholder="Description" required></textarea>
        <input type="file" name="image" accept="image/*">
        <textarea name="seances" placeholder="Séances (une par ligne)" rows="4"></textarea>
        <button type="submit" name="add_film">Ajouter</button>
    </form>
    <div class="film-list">
        <h2>Films existants</h2>
        <?php foreach($films as $i=>$film): ?>
            <div class="film-item">
                <?php if($film['image']): ?><img src="<?= $UPLOAD_DIR.$film['image'] ?>" alt="affiche"><?php endif; ?>
                <div class="film-info">
                    <strong><?= htmlspecialchars($film['titre']) ?></strong><br>
                    <small><?= nl2br(htmlspecialchars($film['desc'])) ?></small><br>
                    <em>Séances :</em><br>
                    <ul><?php foreach($film['seances'] as $s): ?><li><?= htmlspecialchars($s) ?></li><?php endforeach; ?></ul>
                </div>
                <form method="get" onsubmit="return confirm('Supprimer ce film ?');">
                    <input type="hidden" name="delete" value="<?= $i ?>">
                    <button class="delete-btn">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html> 