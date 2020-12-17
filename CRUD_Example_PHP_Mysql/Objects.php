<?php
class Person
{
    protected $personid;
    protected $fornavn;
    protected $etternavn;
    protected $adresse;
    protected $postnr;
    protected $poststed;
    protected $telefonnr;


    public function __construct($fornavn, $etternavn, $adresse, $postnr, $poststed, $telefonnr)
    {
        $this->fornavn = $fornavn;
        $this->etternavn = $etternavn;
        $this->adresse = $adresse;
        $this->postnr = $postnr;
        $this->poststed = $poststed;
        $this->telefonnr = $telefonnr;
    }
    public function getPersonid()
    {
        return $this->personid;
    }
    public function getFornavn()
    {
        return $this->fornavn;
    }
    public function getEtternavn()
    {
        return $this->etterfornavn;
    }
    public function getAdresse()
    {
        return $this->adresse;
    }
    public function getPostnr()
    {
        return $this->postnr;
    }
    public function getPoststed()
    {
        return $this->poststed;
    }
    public function getTelefonnr()
    {
        return $this->telefonnr;
    }

    public function setPersonid($personid)
    {
        $this->personid = $personid;
    }
    public function setFornavn($fornavn)
    {
        $this->fornavn = $fornavn;
    }
    public function setEtternavn($etternavn)
    {
        $this->etterfornavn = $etterfornavn;
    }
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }
    public function setPostnr($postnr)
    {
        $this->postnr = $postnr;
    }
    public function setPoststed($poststed)
    {
        $this->poststed = $poststed;
    }
    public function setTelefonnr($telefonnr)
    {
        $this->telefonnr = $telefonnr;
    }

}

class Utøver extends Person
{
    protected $nasjonalitet;

    public function __construct($fornavn, $etternavn, $adresse, $postnr, $poststed, $telefonnr, $nasjonalitet)
    {

        parent::__construct($fornavn, $etternavn, $adresse, $postnr, $poststed, $telefonnr);
        $this->nasjonalitet = $nasjonalitet;

    }
    public function getNasjonalitet()
    {
        return $this->nasjonalitet;
    }

    public function setNasjonalitet($nasjonalitet)
    {
        $this->nasjonalitet = $nasjonalitet;
    }

    /**
     * [insertIntoDatabase Inserts information from objects into tables]
     * @param  mysqli $db [description]
     * @return boolean   [description]
     */
    function insertIntoDatabase($db)
    {
        $sql = "INSERT INTO person (fornavn, etternavn, adresse, postnr, poststed, telefonnr)
VALUES ('$this->fornavn', '$this->etternavn', '$this->adresse',
    '$this->postnr','$this->poststed', '$this->telefonnr')";

        $result = $db->query($sql);
        $ok = true;
        if (!$result)
        {
            $ok = false;
            echo "feil i første";
        }
        else
        {
            if ($db->affected_rows == 0)
            {
                $ok = false;
            }
            $id = $db->insert_id;
            $this->setPersonid($id);
            $sql = "INSERT INTO utøver (nasjonalitet, personid)
        VALUES ('$this->nasjonalitet', '$id')";
            $result = $db->query($sql);
            if (!$result)
            {
                $ok = false;
            }
            else
            {
                if ($db->affected_rows == 0)
                {
                    $ok = false;
                }
            }
            if ($ok)
            {
                $db->commit();
            }
            else
            {
                $db->rollback();
            }
            return $ok;
        }
    }

}

class Publikum extends Person
{
    protected $billetttype;

    public function __construct($fornavn, $etternavn, $adresse, $postnr, $poststed, $telefonnr, $billetttype)
    {

        parent::__construct($fornavn, $etternavn, $adresse, $postnr, $poststed, $telefonnr);
        $this->billetttype = $billetttype;
    }
    public function getBillettype()
    {
        return $this->billetttype;
    }
    public function setBilletttype($billetttype)
    {
        $this->billetttype = $billetttype;
    }

     /**
     * [insertIntoDatabase Inserts information from objects into tables]
     * @param  mysqli $db [description]
     * @return boolean   [description]
     */
    function insertIntoDatabase($db)
    {
        $sql = "INSERT INTO person (fornavn, etternavn, adresse, postnr, poststed, telefonnr)
      VALUES ('$this->fornavn', '$this->etternavn', '$this->adresse',
      '$this->postnr','$this->poststed', '$this->telefonnr')";

        $result = $db->query($sql);
        $ok = true;
        if (!$result)
        {
            $ok = false;
            echo "feil i første";
        }
        else
        {
            if ($db->affected_rows == 0)
            {
                $ok = false;
            }
            $id = $db->insert_id;
            $this->setPersonid($id);
            $sql = "INSERT INTO Publikum (billetttype, personid)
        VALUES ('$this->billetttype', '$id')";
            $result = $db->query($sql);
            if (!$result)
            {
                $ok = false;
            }
            else
            {
                if ($db->affected_rows == 0)
                {
                    $ok = false;
                }
            }
            if ($ok)
            {
                $db->commit();
            }
            else
            {
                $db->rollback();
            }
            return $ok;
        }
    }

}

class Oppmelding
{
    /**
    * inserts values to a database
    *
    * @param mysqli $db the databaseobject as the first paramater.
    * @param Person $person a person object with values to insert.
    * @return boolean returns true if inserted.
    */
    static function insertIntoDatabase($db, $person)
    {
        $ok = true;
        /* @var $db type */
        $type = $_POST['ovelsestype'];
        $sql = "SELECT øvelseid FROM øvelse WHERE type = '$type'";
        $result = $db->query($sql);
        if (!$result)
        {
            echo "iNFORMASJONEN BLE IKKE LASTET OPP TIL Oppmeldingstabellen";
            $ok = false;
            return false;
        }
        else
        {
            if ($db->affected_rows == 0)
            {
                echo "RADER BLE IKKE LASTET OPP TIL Oppmeldingstabellen";
                $ok = false;
                return false;
            }
        }

        $radObjekt = $result->fetch_assoc();
        $ovelseid = $radObjekt['øvelseid'];
        $personid = $person->getPersonid();

        $sql = "INSERT INTO oppmelding (øvelseid, personid) 
            VALUES ('$ovelseid','$personid')";
        $result = $db->query($sql);
        if (!$result)
        {
            echo "iNFORMASJONEN BLE IKKE LASTET OPP TIL Oppmeldingstabellen" . $ovelseid;
            $ok = false;
            return false;
        }
        else
        {
            if ($db->affected_rows == 0)
            {
                echo "RADER BLE IKKE LASTET OPP TIL Oppmeldingstabellen";
                $ok = false;
                return false;
            }
        }
        if ($ok)
        {
            $db->commit();
        }
        else
        {
            $db->rollback();
        }
        return $ok;
    }
}

?>
