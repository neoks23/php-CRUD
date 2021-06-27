<?php
class CRUD
{
    public $db;
    public $results;

    public $id = 0;
    public $edit_state = false;
    private $i = 0;
    public $voornaam = "";
    public $achternaam = "";
    public $email = "";
    public $telefoon = "";
    public $bericht = "";
    public $timestamp;

    function __construct()
    {
        session_start();
        $this->id = 0;
        $this->idedit_state = false;
        $this->idi = 0;
        $this->idvoornaam = "";
        $this->idachternaam = "";
        $this->idemail = "";
        $this->idtelefoon = "";
        $this->idbericht = "";
        $this->db = mysqli_connect('localhost', 'root', '', 'contactformulier');
        $this->results = mysqli_query($this->db, "SELECT * FROM klanten");
    }

    public function action($id, $voornaam, $achternaam, $email, $telefoon, $bericht, $timestamp, $type)
    {
        $this->db = mysqli_connect('localhost', 'root', '', 'contactformulier');
        $this->results = mysqli_query($this->db, "SELECT * FROM klanten");
        $this->timestamp = date("Y-m-d H:i:s");
        $this->validate($voornaam, $achternaam, $email, $telefoon, $bericht, $timestamp);
        if ($this->db->connect_error) {
            $_SESSION['msg'] = "Cant resolve error, attempt connecting to db";
            header('location: index.php');
        } else if ($this->i > 0) {
            $_SESSION['msg'] = "Insert all fields";
            header('location: index.php');
        } else {
            if ($type == 0) {
                $this->save($voornaam, $achternaam, $email, $telefoon, $bericht, $timestamp);
            }
            if ($type == 1) {
                $this->update($id, $voornaam, $achternaam, $email, $telefoon, $bericht, $timestamp);
            }
            if ($type == 2) {
                $this->delete();
            }
            $this->results = mysqli_query($this->db, "SELECT * FROM klanten");
        }
    }

    function validate($voornaam, $achternaam, $email, $telefoon, $bericht, $timestamp)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($voornaam)) {

                $_SESSION["VoornaamErr"] = "Voornaam is required";
                $this->i++;
            } else if (strlen($voornaam) > 128) {
                $_SESSION["VoornaamErr"] = "Voornaam moet kleiner zijn dan 128 characters";
                $this->i++;
            } else {
                $_SESSION["VoornaamErr"] = "";
            }
            if (empty($achternaam)) {
                $_SESSION["AchternaamErr"] = "Achternaam is required";
                $this->i++;
            } else if (strlen($achternaam) > 128) {
                $_SESSION["AchternaamErr"] = "Achternaam moet kleiner zijn dan 128 characters";
                $this->i++;
            } else {
                $_SESSION["AchternaamErr"] = "";
            }
            if (empty($email)) {
                $_SESSION["EmailErr"] = "Email is required";
                $this->i++;
            } else if (strlen($email) > 128) {
                $_SESSION["EmailErr"] = "Achternaam moet kleiner zijn dan 128 characters";
                $this->i++;
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["EmailErr"] = "Niet geldige email validatie";
                $this->i++;
            } else {
                $_SESSION["EmailErr"] = "";
            }
            if (empty($telefoon)) {
                $_SESSION["TelefoonErr"] = "Telefoon is required";
                $this->i++;
            } else if ($this->validate_phone_number($telefoon)) {
                $_SESSION["TelefoonErr"] = "Telefoon is niet geldig";
                $this->i++;
            } else {
                $_SESSION["TelefoonErr"] = "";
            }
            if (empty($bericht)) {
                $_SESSION["BerichtErr"] = "Bericht is required";
                $this->i++;
            } else {
                $_SESSION["BerichtErr"] = "";
            }
            if (empty($timestamp)) {
                $_SESSION["TimestampErr"] = "Timestamp is required";
                $this->i++;
            } else {
                $_SESSION["TimestampErr"] = "";
            }
        }
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function validate_phone_number($phone)
    {
        // Allow +, - and . in phone number
        $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        // Remove "-" from number
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        // Check the lenght of number
        // This can be customized if you want phone number from a specific country
        if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
            return true;
        } else {
            return false;
        }
    }
    function save($voornaam, $achternaam, $email, $telefoon, $bericht, $timestamp)
    {
        $this->achternaam = $achternaam;
        $this->voornaam = $voornaam;
        $this->email = $email;
        $this->telefoon = $telefoon;
        $this->bericht = $bericht;
        $this->timestamp = $timestamp;
        $query = "INSERT INTO klanten (Voornaam, Achternaam, Email, Telefoon, Bericht, Timestamp2) 
        VALUES ('$this->voornaam', '$this->achternaam', '$this->email', '$this->telefoon', '$this->bericht','$this->timestamp')";
        mysqli_query($this->db, $query);
        $_SESSION['msg'] = "Row saved";
        header('location: index.html');
    }
    function update($id, $voornaam, $achternaam, $email, $telefoon, $bericht, $timestamp)
    {
        $this->id = mysqli_real_escape_string($this->db, $id);
        $this->voornaam = mysqli_real_escape_string($this->db, $voornaam);
        $this->achternaam = mysqli_real_escape_string($this->db, $achternaam);
        $this->email = mysqli_real_escape_string($this->db, $email);
        $this->telefoon = mysqli_real_escape_string($this->db, $telefoon);
        $this->bericht = mysqli_real_escape_string($this->db, $bericht);
        $this->timestamp = mysqli_real_escape_string($this->db, $timestamp);

        mysqli_query($this->db, "UPDATE klanten SET Voornaam='$this->voornaam', Achternaam ='$this->achternaam', Email ='$this->email', Telefoon='$this->telefoon', Bericht = '$this->bericht', Timestamp2='$this->timestamp' WHERE Klantnr=$this->id");
        $_SESSION['msg'] = "Row updated";
        header('location: index.php');
    }
    function delete()
    {
        $this->id = $_GET['del'];
        mysqli_query($this->db, "DELETE FROM klanten WHERE Klantnr=$this->id");
        $_SESSION['msg'] = "Row deleted";
        header('location: index.php');
    }
}
