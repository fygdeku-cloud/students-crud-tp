

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
            <div class="alerte">
            </div>

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

            <!--
                Si aucun étudiant n'est trouvé, PHP affichera ceci à la place du tableau :
                <p class="vide">Aucun étudiant trouvé.</p>
            -->
                <div class="tableau-conteneur">
                    <table class="tableau">
                        <thead>
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
                           

                        </tbody>
                    </table>
                </div>
        </section>
    </main>

    <footer class="pied">
        <div class="conteneur">TP Développement Web — Application CRUD &copy; 2026</div>
    </footer>

</body>

</html>