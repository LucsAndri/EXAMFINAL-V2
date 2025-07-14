<?php 
session_start();
if($_POST['nom']=='Alice Dupont' && $_POST['password']=='password1'){
    $_SESSION['anarana']=$_POST['nom'];
    $_SESSION['mot de passe']=$_POST['password'];
    header('Location:listeobj.php');
}
else{
    header('Location:index.php?error=1');
}
?>