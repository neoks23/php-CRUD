<?php
include('crud.php');
session_start();

if (isset($_POST['save'])) {
    $crud = $_SESSION['crud'];
    $crud->action($_POST['id'], $_POST['Voornaam'], $_POST['Achternaam'], $_POST['Email'], $_POST['Telefoon'], $_POST['Bericht'], $_POST['Timestamp'], 0);
}
if (isset($_POST['update'])) {
    $crud = $_SESSION['crud'];
    $crud->action($_POST['id'], $_POST['Voornaam'], $_POST['Achternaam'], $_POST['Email'], $_POST['Telefoon'], $_POST['Bericht'], $_POST['Timestamp'], 1);
}
if (isset($_GET['del'])) {
    $crud = $_SESSION['crud'];
    $crud->action($_POST['id'], $_POST['Voornaam'], $_POST['Achternaam'], $_POST['Email'], $_POST['Telefoon'], $_POST['Bericht'], $_POST['Timestamp'], 2);
}
