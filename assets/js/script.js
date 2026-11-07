/* ==========================================================
   TP CRUD — Gestion des étudiants
   Fichier : assets/js/script.js
   Rôle    : validation du formulaire CÔTÉ CLIENT.
   ATTENTION : cette validation améliore le confort de l'utilisateur
   mais NE PROTÈGE PAS le serveur (elle peut être contournée).
   Il faudra TOUJOURS revalider les données en PHP (Partie 3).
   ========================================================== */

// 'use strict' : active un mode plus rigoureux (erreurs sur variables non déclarées, etc.)
'use strict';

// Le script est chargé en fin de <body> : les éléments HTML existent déjà.
const formulaire = document.getElementById('form-etudiant');

/* ----------------------------------------------------------
   1. Les règles de validation
   Chaque clé = attribut "name" du champ.
   Chaque fonction reçoit la valeur et retourne :
     - ""  (chaîne vide) si la valeur est correcte
     - un message d'erreur sinon
   ---------------------------------------------------------- */
const regles = {
    matricule(valeur) {
        if (valeur === '') return 'Le matricule est obligatoire.';
        if (!/^ETU\d{6}$/.test(valeur)) return 'Format attendu : ETU suivi de 6 chiffres (ex : ETU260001).';
        return '';
    },

    nom(valeur) {
        if (valeur === '') return 'Le nom est obligatoire.';
        if (valeur.length < 2) return 'Le nom doit contenir au moins 2 caractères.';
        if (!/^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$/.test(valeur)) return 'Le nom ne doit contenir que des lettres.';
        return '';
    },

    prenom(valeur) {
        if (valeur === '') return 'Le prénom est obligatoire.';
        if (valeur.length < 2) return 'Le prénom doit contenir au moins 2 caractères.';
        if (!/^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$/.test(valeur)) return 'Le prénom ne doit contenir que des lettres.';
        return '';
    },

    date_naissance(valeur) {
        if (valeur === '') return 'La date de naissance est obligatoire.';
        const age = calculerAge(valeur);
        if (Number.isNaN(age)) return 'Date invalide.';
        if (age < 15) return "L'étudiant doit avoir au moins 15 ans.";
        if (age > 60) return 'Veuillez vérifier la date (âge supérieur à 60 ans).';
        return '';
    },

    sexe(valeur) {
        return valeur === '' ? 'Veuillez choisir le sexe.' : '';
    },

    email(valeur) {
        if (valeur === '') return "L'adresse e-mail est obligatoire.";
        if (!/^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i.test(valeur)) return 'Adresse e-mail invalide (ex : nom@domaine.com).';
        return '';
    },

    telephone(valeur) {
        if (valeur === '') return 'Le téléphone est obligatoire.';
        if (!/^[26]\d{8}$/.test(valeur)) return 'Numéro invalide : 9 chiffres commençant par 6 ou 2.';
        return '';
    },

    adresse(valeur) {
        // Champ facultatif : seule la longueur est contrôlée
        return valeur.length > 200 ? "L'adresse ne doit pas dépasser 200 caractères." : '';
    },

    filiere(valeur) {
        return valeur === '' ? 'Veuillez choisir une filière.' : '';
    },

    niveau(valeur) {
        return valeur === '' ? 'Veuillez choisir un niveau.' : '';
    },

    certification(valeur) {
        return valeur === '' ? 'Vous devez certifier les informations.' : '';
    }
};

/* ----------------------------------------------------------
   2. Fonctions utilitaires
   ---------------------------------------------------------- */

// Calcule l'âge à partir d'une date au format AAAA-MM-JJ
function calculerAge(dateTexte) {
    const naissance = new Date(dateTexte);
    if (Number.isNaN(naissance.getTime())) return NaN;
    const aujourdhui = new Date();
    let age = aujourdhui.getFullYear() - naissance.getFullYear();
    const moisDiff = aujourdhui.getMonth() - naissance.getMonth();
    if (moisDiff < 0 || (moisDiff === 0 && aujourdhui.getDate() < naissance.getDate())) {
        age--;
    }
    return age;
}

// Lit la valeur d'un champ à partir de son "name" (gère radio et checkbox)
function lireValeur(nom) {
    const element = formulaire.elements[nom];

    // Boutons radio : formulaire.elements renvoie une liste (RadioNodeList)
    if (element instanceof RadioNodeList) {
        return element.value; // "" si aucun bouton coché
    }
    // Case à cocher
    if (element.type === 'checkbox') {
        return element.checked ? element.value : '';
    }
    return element.value.trim();
}

// Affiche (ou efface) le message d'erreur d'un champ et met à jour son style
function afficherEtat(nom, message) {
    const zoneErreur = document.getElementById('erreur-' + nom);
    const blocChamp = zoneErreur.closest('.champ');

    zoneErreur.textContent = message;
    blocChamp.classList.toggle('invalide', message !== '');
    blocChamp.classList.toggle('valide', message === '' && lireValeur(nom) !== '');
}

// Valide un seul champ ; retourne true s'il est correct
function validerChamp(nom) {
    const message = regles[nom](lireValeur(nom));
    afficherEtat(nom, message);
    return message === '';
}

// Valide tout le formulaire ; retourne true si TOUS les champs sont corrects
function validerFormulaire() {
    let toutEstValide = true;
    for (const nom in regles) {
        if (!validerChamp(nom)) {
            toutEstValide = false;
        }
    }
    return toutEstValide;
}

/* ----------------------------------------------------------
   3. Validation en temps réel
   - "blur"  : quand l'utilisateur quitte un champ
   - "input" : à chaque frappe, mais seulement si le champ
               a déjà été signalé en erreur (pour ne pas
               agresser l'utilisateur pendant qu'il tape)
   - "change": pour les radios, select et checkbox
   ---------------------------------------------------------- */
for (const nom in regles) {
    const element = formulaire.elements[nom];
    const liste = element instanceof RadioNodeList ? Array.from(element) : [element];

    liste.forEach((champ) => {
        champ.addEventListener('blur', () => validerChamp(nom));
        champ.addEventListener('change', () => validerChamp(nom));
        champ.addEventListener('input', () => {
            if (champ.closest('.champ').classList.contains('invalide')) {
                validerChamp(nom);
            }
        });
    });
}

/* ----------------------------------------------------------
   4. Petites aides à la saisie
   ---------------------------------------------------------- */

// Matricule et nom automatiquement en MAJUSCULES
['matricule', 'nom'].forEach((nom) => {
    formulaire.elements[nom].addEventListener('input', (e) => {
        e.target.value = e.target.value.toUpperCase();
    });
});

// Téléphone : on supprime tout ce qui n'est pas un chiffre et on garde 9 chiffres max
// (ex : "677 12 34 56" collé devient "677123456")
formulaire.elements.telephone.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/\D/g, '').slice(0, 9);
});

// Compteur de caractères de l'adresse
const champAdresse = formulaire.elements.adresse;
const compteur = document.getElementById('compteur-adresse');

function majCompteur() {
    const longueur = champAdresse.value.length;
    compteur.textContent = longueur;
    compteur.parentElement.classList.toggle('limite', longueur >= 180);
}
champAdresse.addEventListener('input', majCompteur);

/* ----------------------------------------------------------
   5. Soumission du formulaire
   ---------------------------------------------------------- */
formulaire.addEventListener('submit', (evenement) => {
    if (!validerFormulaire()) {
        // On bloque l'envoi et on place le curseur sur le premier champ en erreur
        evenement.preventDefault();
        const premierInvalide = formulaire.querySelector('.champ.invalide input, .champ.invalide select, .champ.invalide textarea');
        if (premierInvalide) premierInvalide.focus();
        return;
    }

    // Mode démo : tant que ajouter.php n'existe pas, on affiche un récapitulatif
    if (formulaire.dataset.modeDemo === 'true') {
        evenement.preventDefault();
        afficherRecapitulatif();
    }
    // Sinon : rien à faire, le navigateur envoie les données à ajouter.php en POST
});

/* ----------------------------------------------------------
   6. Réinitialisation
   ---------------------------------------------------------- */
formulaire.addEventListener('reset', (evenement) => {
    if (!confirm('Voulez-vous vraiment effacer toutes les informations saisies ?')) {
        evenement.preventDefault();
        return;
    }
    // On retire les messages et les couleurs après la remise à zéro
    setTimeout(() => {
        formulaire.querySelectorAll('.champ').forEach((bloc) => bloc.classList.remove('valide', 'invalide'));
        formulaire.querySelectorAll('.message-erreur').forEach((zone) => (zone.textContent = ''));
        majCompteur();
    }, 0);
});

/* ----------------------------------------------------------
   7. Fenêtre de récapitulatif (mode démo uniquement)
   Montre exactement les paires name => valeur que PHP
   recevra dans le tableau $_POST.
   ---------------------------------------------------------- */
const modale = document.getElementById('modale');
const corpsRecap = document.getElementById('recap-corps');

// La fenêtre n'existe que dans index.html (mode démo) : on vérifie sa présence
if (modale) {
    document.getElementById('modale-fermer').addEventListener('click', fermerModale);
    modale.addEventListener('click', (e) => {
        if (e.target === modale) fermerModale(); // clic en dehors de la boîte
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modale.hidden) fermerModale();
    });
}

function afficherRecapitulatif() {
    corpsRecap.innerHTML = '';
    const donnees = new FormData(formulaire);

    donnees.forEach((valeur, cle) => {
        const ligne = document.createElement('tr');
        const th = document.createElement('th');
        const td = document.createElement('td');
        th.textContent = "$_POST['" + cle + "']";
        td.textContent = valeur === '' ? '(vide)' : valeur; // textContent : pas d'injection HTML
        ligne.append(th, td);
        corpsRecap.appendChild(ligne);
    });

    modale.hidden = false;
    document.getElementById('modale-fermer').focus();
}

function fermerModale() {
    modale.hidden = true;
}

// Au chargement : met à jour le compteur (utile quand l'adresse est déjà remplie en modification)
majCompteur();
