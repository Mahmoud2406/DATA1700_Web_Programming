
<?php
include ("error_handler.php");
/*
$db = new mysqli("localhost", "root", "");
$sql = "CREATE DATABASE vm_ski";
$db->query($sql);
$db->close();
$db = new mysqli("localhost", "root", "", "vm_ski");
$location = 'vm_ski.sql';
$commands = file_get_contents($location); 
$db->multi_query($commands);
$db->close();
*/


?>
<html>
  <head>
    <title>Oblig 2 
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>
  <body>
              <?php
$db = new mysqli("localhost", "root", "", "vm_ski");
$db->set_charset("utf8");

if ($db->connect_error)
  {
  die("Kunne ikke koble til db : " . $db->connect_error);
  }

$sql = "SELECT * FROM `øvelse` where 1";
$resultat = $db->query($sql);

if ($resultat)
  {
  if ($db->affected_rows > 0)
    {
    $antallRader = $db->affected_rows;
    echo "<h2> Informasjon om øvelsene </h2><ol>";
    for ($i = 0; $i < $antallRader; $i++)
      {
      $radObjekt = $resultat->fetch_object();
      echo "<li>" . "<strong>Type</strong> : " . $radObjekt->type .
      " <strong>Dato</strong> : " . $radObjekt->dato .
      " <strong>Sted</strong> : " . $radObjekt->sted . "</li>";
      }

    echo "</ol>";
    }
  }
  else
  {
  printError("Feil i visning fra databasen : " . $db->error);
  }

?>
  <h4> Oppmeldingsskjema (utøvere må forhåndsbestille billett)</h4>
    <form action="SESSION_2.php" method="post">
      <table>
        <tr>
          <th>
            Personopplysningner
          </th>
        </tr>
        <tr>
          <td>Fornavn
          </td>
          <td>
            <input type="text" name="fornavn" id="fornavn">
          </td>
        </tr>
        <tr>
          <td>Etternavn
          </td>
          <td>
            <input type="text" name="etternavn" id="etternavn">
          </td>
        </tr>
        <tr>
          <td>Adresse
          </td>
          <td>
            <input type="text" name="adresse" id="adresse">
          </td>
        </tr>
        <tr>
          <td>Postnummer
          </td>
          <td>
            <input type="text" name="postnr" id="postnr">
          </td>
        </tr>
        <tr>
          <td>Poststed
          </td>
          <td>
            <input type="text" name="poststed" id="poststed">
          </td>
        </tr>
        <tr>
          <td>Telefonnummer
          </td>
          <td>
            <input type="text" name="telefonnr" id="telefonnr">
          </td>
        </tr>
        <br/>
        <tr>
        <tr>
          <th>Velg øvelse
          </th>
          <td>
            <select name="ovelsestype">
              <?php
$sql = "SELECT * FROM `øvelse` where 1";
$resultat = $db->query($sql);

if ($resultat)
  {
  if ($db->affected_rows > 0)
    {
    $antallRader = $db->affected_rows;
    for ($i = 0; $i < $antallRader; $i++)
      {
      $radObjekt = $resultat->fetch_object();
      echo "<option>" . $radObjekt->type . "</option>";
      }
    }
  }
  else
  {
  printError("Feil i visning fra databasen : " . $db->error);
  $db->close();
  }

?>
            </select>
          </td>
        </tr>
        <tr>
          <td> Billetytype 
          </td>
          <td>
            <select name="billetttype">
              <option>
                Utøver
              </option>
              <option>
                Barn
              </option>
              <option>
                Student
              </option>
              <option>
                Honnør
              </option>
              <option>
                Ungdom
              </option>
              <option>
                Voksen
              </option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            Nasjonalitet
          </td>
          <td>
            <input type="text" name="nasjonalitet" id="nasjonalitet">
          </td>
        </tr>
        <tr>
          <td>
            <input type="submit" name="seOversikt" value="Se Oversikt">
          </td>
          <td>
            <input type="submit" name="bekreft" value="Bekreft">
          </td>
        </tr>
      </table>
      </body>
    </html>