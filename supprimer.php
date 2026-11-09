<?php 
    require "connexion.php";
    session_start();
    if (isset($_GET["id"])) {
        if($_SERVER["REQUEST_METHOD"] == 'POST'){
            $id = $_GET["id"];
            $sql_delete = $conn -> prepare("DELETE FROM etudiant WHERE id=?");
            $sql_delete -> execute([$id]);

            $_SESSION["message"]="Etudiant supprimer avec succes";
            $_SESSION["type"]="succes";
            header('Location:liste.php');
        }
    }
   
?>