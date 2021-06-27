<?php include('crud.php');

if (!isset($_SESSION['crud'])) {
    $crud = new CRUD();

    $_SESSION['crud'] = $crud;
}
if (isset($_GET['edit'])) {
    $crud->id = $_GET['edit'];
    $crud->edit_state = true;
    $rec = mysqli_query($crud->db, "SELECT * FROM klanten WHERE Klantnr=$crud->id");
    $record = mysqli_fetch_array($rec);
    $crud->voornaam = $record['Voornaam'];
    $crud->achternaam = $record['Achternaam'];
    $crud->email = $record['Email'];
    $crud->telefoon = $record['Telefoon'];
    $crud->bericht = $record['Bericht'];
    $crud->timestamp = $record['Timestamp2'];
    $crud->id = $record['Klantnr'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php if (isset($_SESSION['msg'])) : ?>
        <div class="msg">
            <?php
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
            ?>
        </div>
    <?php endif ?>
    <table>
        <thead>
            <tr>
                <th>KlantID</th>
                <th>Voornaam</th>
                <th>Achternaam</th>
                <th>Email</th>
                <th>Telefoon</th>
                <th>Bericht</th>
                <th>Timestamp</th>
                <th colspan="7">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($crud->results)) { ?>
                <tr>
                    <td><?php echo $row['Klantnr']; ?></td>
                    <td><?php echo $row['Voornaam']; ?></td>
                    <td><?php echo $row['Achternaam']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Telefoon']; ?></td>
                    <td><?php echo $row['Bericht']; ?></td>
                    <td><?php echo $row['Timestamp2']; ?></td>
                    <td>
                        <a class="edit_btn" href="index.php?edit=<?php echo $row['Klantnr']; ?>">Edit</a>
                    </td>
                    <td>
                        <a class="del_btn" href="controller.php?del=<?php echo $row['Klantnr'] ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <form method="post" action="controller.php">
        <input type="hidden" name="id" value="<?php echo $crud->id; ?>">
        <div class="input-group">
            <label>Voornaam</label>
            <input type="text" name="Voornaam" value="<?php echo $crud->voornaam ?>">
            <span class="error">* <?php echo empty($_SESSION["VoornaamErr"]) ? "" : $_SESSION["VoornaamErr"]; ?></span>
        </div>
        <div class="input-group">
            <label>Achternaam</label>
            <input type="text" name="Achternaam" value="<?php echo $crud->achternaam ?>">
            <span class="error">* <?php echo empty($_SESSION["AchternaamErr"]) ? "" : $_SESSION["AchternaamErr"]; ?></span>
        </div>
        <div class=" input-group">
            <label>Email</label>
            <input type="email" name="Email" value="<?php echo $crud->email ?>">
            <span class="error">* <?php echo empty($_SESSION["EmailErr"]) ? "" : $_SESSION["EmailErr"]; ?></span>
        </div>
        <div class=" input-group">
            <label>Telefoon</label>
            <input type="text" name="Telefoon" value="<?php echo $crud->telefoon ?>">
            <span class="error">* <?php echo empty($_SESSION["TelefoonErr"]) ? "" : $_SESSION["TelefoonErr"]; ?></span>
        </div>
        <div class=" input-group">
            <label>Bericht</label>
            <textarea type="text" name="Bericht"><?php echo $crud->bericht ?></textarea>
            <span class="error">* <?php echo empty($_SESSION["BerichtErr"]) ? "" : $_SESSION["BerichtErr"]; ?></span>
        </div>
        <div class=" input-group">
            <label>Timestamp</label>
            <input type="datetime-local" name="Timestamp" value="<?php echo $crud->timestamp ?>">
            <span class="error">* <?php echo empty($_SESSION["TimestampErr"]) ? "" : $_SESSION["TimestampErr"]; ?></span>
        </div>
        <div class=" input-group">
            <?php if ($crud->edit_state == false) : ?>
                <button type="submit" name="save" class="btn">Save</button>
            <?php else : ?>
                <button type="submit" name="update" class="btn">Update</button>
            <?php endif ?>
        </div>
    </form>
</body>

</html>