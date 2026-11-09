<?php
    session_start();
    require "connexion.php";
    $sql_select_filiere = $conn ->prepare("SELECT * FROM filiere");
    $sql_select_filiere ->execute();
    $filieres = $sql_select_filiere -> fetchAll(PDO::FETCH_ASSOC);


    $sql_select_niveau = $conn ->prepare("SELECT * FROM niveau");
    $sql_select_niveau ->execute();
    $niveau = $sql_select_niveau -> fetchAll(PDO::FETCH_ASSOC);
    


    try {
        if($_SERVER["REQUEST_METHOD"] == 'POST'){
            $matricule = trim($_POST['matricule']);
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $sexe = trim($_POST['sexe']);
            $mail = trim($_POST['email']);
            $telephone = trim($_POST['telephone']);
            $adresse = trim($_POST['adresse']);
            $filiere = trim($_POST['filiere']);
            $niveau = trim($_POST['niveau']);
            $date_naissance = $_POST['date_naissance'];
            $certification = isset($_POST['certification']) ? 1 : 0;


            $sql_v = $conn->prepare("SELECT id FROM etudiant WHERE matricule = ? OR email = ? OR telephone = ?");
            $sql_v->execute([$matricule, $mail, $telephone]);
            $result_v = $sql_v -> fetchAll(PDO::FETCH_ASSOC);

            if (count($result_v) > 0) {
                $_SESSION['message'] = 'Le matricule, email ou le telephone est deja utilise';
                $_SESSION['type'] = "erreur";
            }else{
                $sql_insert = $conn -> prepare("INSERT INTO etudiant(matricule, nom, prenom, adresse, date_naissance, sexe, email, telephone, filiere, niveau, certification)
                VALUES(:matricule, :nom, :prenom, :adresse, :date_naissance, :sexe, :email, :telephone, :filiere, :niveau, :certification);");
                $sql_insert ->execute([
                    ':matricule' => $matricule, 
                    ':nom' => $nom, 
                    ':prenom' => $prenom, 
                    ':adresse' => $adresse, 
                    ':date_naissance' => $date_naissance, 
                    ':sexe' => $sexe, 
                    ':email' => $mail, 
                    ':telephone' => $telephone, 
                    ':filiere' => $filiere, 
                    ':niveau' => $niveau, 
                    ':certification' => $certification
                ]);
                $_SESSION['message'] = "Message : Etudiant enregistrer avec succes";
                $_SESSION['type'] = "succes";
            }
        }
    } catch (PDOException $e) {
        echo "Error : ".$e->getMessage();
    }
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription des étudiants — TP CRUD</title>
    <!-- Feuille de style externe : toute la présentation est dans ce fichier -->
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
                <a href="index.php" class="actif">Nouvelle inscription</a>
                <a href="liste.php">Liste des étudiants</a>
            </nav>
        </div>
    </header>

    <!-- ===================== CONTENU ===================== -->
    <main class="conteneur">

        <!-- Zone d'alerte : PHP y affichera plus tard les messages de succès / d'erreur -->
         <?php if (isset($_SESSION['message'])) { ?>
            <div id="alerte" class="alerte <?php  echo $_SESSION['type']; ?> "role="status">
                <span><?php echo $_SESSION['message']; ?></span>
                <?php unset($_SESSION['message'], $_SESSION['type']) ?>
            </div>
            <?php } ?>
        <!-- <div id="alerte" class="alerte " role="status" hidden></div> -->

        <section class="carte">
            <div class="carte-entete">
                <h2>Fiche d'inscription</h2>
                <p>Les champs marqués d'un <span class="obligatoire">*</span> sont obligatoires.</p>
            </div>

            <form id="form-etudiant" action="index.php" method="POST" novalidate data-mode-demo="false">

                <!-- ---------- Bloc 1 : Identité ---------- -->
                <fieldset>
                    <legend>1. Identité</legend>
                    <div class="grille">

                        <div class="champ">
                            <label for="matricule">Matricule <span class="obligatoire">*</span></label>
                            <input type="text" id="matricule" name="matricule"
                                placeholder="Ex : ETU260001" maxlength="9" autocomplete="off">
                            <small class="aide">Format : ETU + 6 chiffres</small>
                            <small class="message-erreur" id="erreur-matricule"></small>
                        </div>

                        <div class="champ">
                            <label for="date_naissance">Date de naissance <span class="obligatoire">*</span></label>
                            <input type="date" id="date_naissance" name="date_naissance">
                            <small class="message-erreur" id="erreur-date_naissance"></small>
                        </div>

                        <div class="champ">
                            <label for="nom">Nom <span class="obligatoire">*</span></label>
                            <input type="text" id="nom" name="nom" placeholder="Ex : KAMGA" maxlength="50">
                            <small class="message-erreur" id="erreur-nom"></small>
                        </div>

                        <div class="champ">
                            <label for="prenom">Prénom(s) <span class="obligatoire">*</span></label>
                            <input type="text" id="prenom" name="prenom" placeholder="Ex : Jean Paul" maxlength="50">
                            <small class="message-erreur" id="erreur-prenom"></small>
                        </div>

                        <div class="champ champ-large">
                            <span class="label">Sexe <span class="obligatoire">*</span></span>
                            <div class="groupe-radio">
                                <label class="radio">
                                    <input type="radio" name="sexe" value="M"> Masculin
                                </label>
                                <label class="radio">
                                    <input type="radio" name="sexe" value="F"> Féminin
                                </label>
                            </div>
                            <small class="message-erreur" id="erreur-sexe"></small>
                        </div>

                    </div>
                </fieldset>

                <!-- ---------- Bloc 2 : Coordonnées ---------- -->
                <fieldset>
                    <legend>2. Coordonnées</legend>
                    <div class="grille">

                        <div class="champ">
                            <label for="email">Adresse e-mail <span class="obligatoire">*</span></label>
                            <input type="email" id="email" name="email" placeholder="Ex : jean.kamga@gmail.com" maxlength="100">
                            <small class="message-erreur" id="erreur-email"></small>
                        </div>

                        <div class="champ">
                            <label for="telephone">Téléphone <span class="obligatoire">*</span></label>
                            <input type="tel" id="telephone" name="telephone" placeholder="Ex : 677123456" inputmode="numeric">
                            <small class="aide">9 chiffres, commence par 6 ou 2</small>
                            <small class="message-erreur" id="erreur-telephone"></small>
                        </div>

                        <div class="champ champ-large">
                            <label for="adresse">Adresse</label>
                            <textarea id="adresse" name="adresse" rows="3" maxlength="200"
                                placeholder="Ex : Quartier Tamdja, Bafoussam"></textarea>
                            <small class="compteur"><span id="compteur-adresse">0</span> / 200 caractères</small>
                            <small class="message-erreur" id="erreur-adresse"></small>
                        </div>

                    </div>
                </fieldset>

                <!-- ---------- Bloc 3 : Scolarité ---------- -->
                <fieldset>
                    <legend>3. Scolarité</legend>
                    <div class="grille">

                        <div class="champ">
                            <label for="filiere">Filière <span class="obligatoire">*</span></label>
                            <select id="filiere" name="filiere">
                                <option value="selectionner une filiere" disabled></option>
                                <?php foreach($filieres as $filiere) { ?>
                                    <option value="<?php echo $filiere['id']?>">
                                        <?php echo $filiere['nom']?>
                                    </option>
                                <?php } ?>
                            </select>
                            <small class="message-erreur" id="erreur-filiere"></small>
                        </div>

                        <div class="champ">
                            <label for="niveau">Niveau <span class="obligatoire">*</span></label>
                            <select id="niveau" name="niveau">
                                <option value="selectionner un niveau" disabled></option>
                                <?php foreach($niveau as $niveau) { ?>
                                    <option value="<?php echo $niveau['id']?>">
                                        <?php echo $niveau['nom']?>
                                    </option>
                                <?php } ?>
                            </select>
                            <small class="message-erreur" id="erreur-niveau"></small>
                        </div>

                        <div class="champ champ-large">
                            <label class="case">
                                <input type="checkbox" id="certification" name="certification" value="1">
                                Je certifie l'exactitude des informations saisies. <span class="obligatoire">*</span>
                            </label>
                            <small class="message-erreur" id="erreur-certification"></small>
                        </div>

                    </div>
                </fieldset>

                <!-- ---------- Boutons ---------- -->
                <div class="actions">
                    <button type="reset" class="btn btn-secondaire">Réinitialiser</button>
                    <button type="submit" class="btn btn-principal">Enregistrer l'étudiant</button>
                </div>

            </form>
        </section>
    </main>

    <!-- ===================== FENÊTRE RÉCAPITULATIF (mode démo) ===================== -->
    <div id="modale" class="modale" hidden>
        <div class="modale-boite" role="dialog" aria-modal="true" aria-labelledby="modale-titre">
            <h3 id="modale-titre">Données prêtes à être envoyées</h3>
            <p class="modale-info">
                Mode démo actif : les données ne sont pas encore envoyées au serveur.
                Voici ce que <code>ajouter.php</code> recevra dans <code>$_POST</code>.
            </p>
            <table class="recap">
                <tbody id="recap-corps"></tbody>
            </table>
            <div class="actions">
                <button type="button" class="btn btn-principal" id="modale-fermer">Fermer</button>
            </div>
        </div>
    </div>

    <footer class="pied">
        <div class="conteneur">TP Développement Web — Application CRUD &copy; 2026</div>
    </footer>

    <!-- Script en fin de body : le HTML est déjà chargé quand le JS s'exécute -->
    <script src="assets/js/script.js"></script>
</body>

</html>