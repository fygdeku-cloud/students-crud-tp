<?php 
    require "connexion.php";
    session_start();
    $sql_select = $conn -> prepare("SELECT Et.*, F.nom as F_nom, Nv.nom as Nv_nom FROM etudiant Et INNER JOIN niveau Nv ON Et.niveau=Nv.id INNER JOIN filiere F ON Et.filiere=F.id");
    $sql_select -> execute();
    $etudiant = $sql_select -> fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des étudiants — TP CRUD</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <!-- ===================== EN-TÊTE ===================== -->
    <header class="entete">
        <div class="conteneur entete-contenu">
            <div class="logo">
                <span class="logo-icone">GE</span>
                <div>
                    <h1>Gestion des étudiants</h1>
                    <p>Application CRUD — HTML / CSS / JS / PHP</p>
                </div>
            </div>
            <nav class="menu">
                <a href="index.php">Nouvelle inscription</a>
                <a href="liste.php" class="actif">Liste des étudiants</a>
            </nav>
        </div>
    </header>

    <!-- ===================== CONTENU ===================== -->
    <main class="conteneur">

        <!--
            Message de confirmation.
            En PHP, il ne sera affiché qu'après un ajout, une modification ou une suppression.
            Ici, il est visible pour que vous voyiez son apparence.
        -->
             <?php if (isset($_SESSION['message'])) { ?>
            <div id="alerte" class="alerte <?php  echo $_SESSION['type']; ?> "role="status">
                <span><?php echo $_SESSION['message']; ?></span>
                <?php unset($_SESSION['message'], $_SESSION['type']) ?>
            </div>
            <?php } ?>
        <section class="carte">

            <div class="carte-entete entete-liste">
                <div>
                    <h2>Liste des étudiants</h2>
                    <!-- En PHP : le nombre sera calculé avec count() -->
                    <p> 9 étudiant(s) trouvé(s)</p>
                </div>
                <a href="index.php" class="btn btn-principal">+ Nouvel étudiant</a>
            </div>

            <!--
                Formulaire de recherche : méthode GET (le mot recherché apparaît dans l'URL).
                En PHP : action="liste.php"
            -->
            <form method="GET" action="liste.php" class="recherche">
                <input type="text" name="q" placeholder="Rechercher par matricule, nom ou prénom ..." value="">
                <button type="submit" class="btn btn-principal">Rechercher</button>
            </form>
                <?php if(count($etudiant) > 0){?>
                 <div class="tableau-conteneur">
                    <table class="tableau">
                        <thead>
                            <tr class="tableau"><?php 
                            $nbe=count($etudiant);
                            echo $nbe." etudiants" ?></tr>
                            <tr>
                                <th>#</th>
                                <th>Matricule</th>
                                <th>Nom et prénom</th>
                                <th>Filière</th>
                                <th>Niveau</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($etudiant as $key=>$etudiant) { ?>
                                <tr>
                                    <td><?php echo ++$key ?></td>
                                    <td><?php echo $etudiant["matricule"] ?></td>
                                    <td><?php echo $etudiant["nom"]." ".$etudiant["prenom"] ?></td>
                                    <td><?php echo $etudiant["F_nom"] ?></td>
                                    <td><?php echo $etudiant["Nv_nom"] ?></td>
                                    <td><?php echo $etudiant["telephone"] ?></td>
                                    <td>
                                        <a href="update.php?id=<?php echo (int)$etudiant["id"] ?>" class="lien">Modifier</a>
                                        <form action="supprimer.php?id=<?php echo (int)$etudiant["id"] ?> " method="post" onsubmit="Confirm('Confirmer la suppression');">
                                            <button type="submit" class="lien lien-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
               <?php }else{ ?>
                   <p class="vide">Aucun etudiant</p>
               <?php } ?>
                
        </section>
    </main>

    <footer class="pied">
        <div class="conteneur">TP Développement Web — Application CRUD &copy; 2026</div>
    </footer>

</body>

</html>