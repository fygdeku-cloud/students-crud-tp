# TP CRUD — Gestion des étudiants (HTML / CSS / JS / PHP)

Projet de départ fourni aux étudiants (Partie 1).

## Installation
1. Copier le dossier `tp-crud-etudiants` dans le dossier web du serveur local :
   - XAMPP : `C:\xampp\htdocs\`
   - WAMP : `C:\wamp64\www\`
   - Laragon : `C:\laragon\www\`
2. Démarrer Apache (et MySQL pour la Partie 2).
3. Ouvrir `http://localhost/tp-crud-etudiants/` dans le navigateur.

## Contenu fourni
```
tp-crud-etudiants/
├── index.html              Formulaire d'inscription (fourni)
├── liste.html              Maquette de la liste avec boutons (fourni, données fictives)
├── assets/
│   ├── css/style.css       Mise en forme du formulaire et de la liste (fourni)
│   └── js/script.js        Validation côté client (fourni)
├── sql/base_etudiants.sql  Script de la base (Partie 2)
└── docs/                   Énoncé du TP (PDF)
```

## À réaliser par l'étudiant
Voir l'énoncé PDF : Parties 2 à 7 (base de données, Create, Read, Update, Delete, bonus).

Rappel : le formulaire est en **mode démo** (`data-mode-demo="true"`).
Il faudra passer cet attribut à `"false"` dès que `ajouter.php` sera créé.

`liste.html` contient 4 étudiants écrits à la main (les mêmes que dans `sql/base_etudiants.sql`).
Il sera transformé en `liste.php` pour afficher les données de la base. Les boutons Voir / Modifier / Supprimer
ne font encore rien : les commentaires du fichier indiquent ce qu'ils deviendront en PHP.
# students-crud-tp
