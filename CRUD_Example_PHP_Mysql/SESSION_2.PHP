<?php
include ("functions.php");
include ("Objects.php");
include ("error_handler.php");
session_start();
function connectToDatabase()
{
    $db = new mysqli("localhost", "root", "", "vm_ski");
    if (!$db)
    {
        printError("Kan ikke koble til databasen");
    }
    else
    {
        return $db;
    }
}
?>
<html>
    <head>
        <title>Oblig 2 side 2</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <style type="text/css"></style>
    </head>
    <body>
        <?php
echo "<h1> Ski Øvelser </h1>";
?>
<?php
if (isset($_POST["bekreft"]))
{
    //tomme eller blanke felter håndteres nå av regex
    //lager en array med felter og verdier for å kunne bruke løkker på validering
    $fields = array(
        "fornavn" => $_POST["fornavn"],
        "etternavn" => $_POST["etternavn"],
        "adresse" => $_POST["adresse"],
        "postnr" => $_POST["postnr"],
        "poststed" => $_POST["poststed"],
        "telefonnr" => $_POST["telefonnr"],
        "nasjonalitet" => $_POST["nasjonalitet"],
    );

    //validerer opp mot regexfunksjon
    $alle_felter_ok = true;
    $feilmeldinger = "";

    foreach ($fields as $key => $value)
    {
        $feilmeldinger .= validate($key, $value);
    }

    if ($feilmeldinger != "")
    { //dette betyr at en av feilmeldingene har slått til
        echo $feilmeldinger;
        $alle_felter_ok = false;
    }

    //forhindrer at operasjoner mot klasser og fil gjøres hvis feltene ikke er ok ift regex
    if ($alle_felter_ok)
    {
        if ($_POST["billetttype"] == "Utøver")
        {
            $person = new Utøver($_POST["fornavn"], $_POST["etternavn"], $_POST["adresse"], $_POST["postnr"], $_POST["poststed"], $_POST["telefonnr"], $_POST["nasjonalitet"]);
        }
        else
        {
            $person = new publikum($_POST["fornavn"], $_POST["etternavn"], $_POST["adresse"], $_POST["postnr"], $_POST["poststed"], $_POST["telefonnr"], $_POST["billetttype"]);
        }
        //Kobler til databasen ved å lage et objekt.
        $db = connectToDatabase();
        $db->set_charset("utf8");
        $db->autocommit(false);
        $ok = true;
        //Sjekker mot databasemetodene om informasjonen ble
        // lagret konsistens.
        if (!$person->insertIntoDatabase($db))
        {
            $ok = false;
            printError("Feil i lagring til person");
        }
        if (!(Oppmelding::insertIntoDatabase($db, $person)))
        {
            $ok = false;
            printError("Feil i lagring til oppmelding");
        }
        if ($ok)
        {
            $db->commit();
        }
        else
        {
            $db->rollback();
            printError("Feil i lagring til tabellene");
        }
    }
    else
    {
        echo "<br/><strong>Dataen ble ikke lagret pga. feilaktig input. Vennligst rett opp og prøv igjen.</strong>";

    }
    $db->close();
}
?>

    <form action="" method="post">
      Se utøvere <br/>
      <input type="submit" name="seUtovere" value="se utøvere">
    </form>
       <?php
if (isset($_POST["seUtovere"]))
{
    $db = new mysqli("localhost", "root", "", "vm_ski");
    $db->set_charset("utf8");
    if ($db->connect_error)
    {
        die("Kunne ikke koble til db : " . $db->connect_error);
    }

    $sql = "SELECT ø.type, p.fornavn from øvelse AS ø, utøver AS u, person AS p, oppmelding AS o
        WHERE ø.øvelseid = o.øvelseid AND o.personid = p.personid
        AND p.personid = u.personid";
    $resultat = $db->query($sql);
    if ($resultat)
    {
        if ($db->affected_rows > 0)
        {
            $antallRader = $db->affected_rows;
            echo "<table> <tr> <th> Fornavn </th>
                                <th> type </th>";
            for ($i = 0;$i < $antallRader;$i++)
            {
                $radObjekt = $resultat->fetch_object();
                echo "<tr>";
                echo "<td>" . $radObjekt->fornavn . "</td>" . "<td>" . $radObjekt->type . "</td>" . "</tr>";
            }
            echo "</table>";

        }
        else
        {
            echo "feil1";
        }
    }
    else
    {
        echo "feil2";
    }
}
?>

<br/>
<form action="" method="post">
      Se Publikum<br/>
      <input type="submit" name="sePublikum" value="se publikum">
    </form>
       <?php
if (isset($_POST["sePublikum"]))
{
    $db = new mysqli("localhost", "root", "", "vm_ski");
    $db->set_charset("utf8");
    if ($db->connect_error)
    {
        die("Kunne ikke koble til db : " . $db->connect_error);
    }

    $sql = "SELECT u.billetttype, p.fornavn, ø.type from øvelse AS ø, publikum AS u, person AS p, oppmelding AS o
        WHERE ø.øvelseid = o.øvelseid AND o.personid = p.personid
        AND p.personid = u.personid";
    $resultat = $db->query($sql);
    if ($resultat)
    {
        if ($db->affected_rows > 0)
        {
            $antallRader = $db->affected_rows;
            echo "<table> <tr> <th> Fornavn </th>
                                <th> billetttype </th>
                                <th> Øvelse </th>";
            for ($i = 0;$i < $antallRader;$i++)
            {
                $radObjekt = $resultat->fetch_object();
                echo "<tr>";
                echo "<td>" . $radObjekt->fornavn . "</td>" . "<td>" . $radObjekt->billetttype . "</td>" . "<td>" . $radObjekt->type . "</td>" .
                "</tr>";
            }
            echo "</table>";

        }
        else
        {
            echo " Null ";
        }
    }
    else
    {
        echo "Feil i databasen";
    }
}
?>
    <form action="index.php" method="post">
        <input type="SUBMIT" value="Gå tilbake">
    </form>
    </body>
</html>
