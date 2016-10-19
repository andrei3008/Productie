<?php

class PariuriMapper
{
    protected $db;

    public function __construct(DataConnection $dbFull)
    {
        $this->setDb($dbFull);
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    public function insertLocatie(PariuriEntity $pariu)
    {
        $database = $this->getDb();

        $query = "INSERT INTO {$database->getDatabase()}.pariuri (denumire,analitic,adresa,corespondent,idPersonal,dtIncepere,dtInchidere)
        VALUES ('{$pariu->getDenumire()}','{$pariu->getAnalitic()}','{$pariu->getAdresa()}','{$pariu->getCorespondent()}','{$pariu->getIdPersonal()}',
        '{$pariu->getDtIncepere()}','{$pariu->getDtInchidere()}')";
        $database->query($query);

        if ($database->affected_rows > 0) {
            echo "A fost introdus recordul cu numarul " . $database->insert_id;
        } elsE {
            echo $database->error;
        }
    }

    /**
     * @return PariuriEntity array
     */
    public function getLocatii()
    {
        $locatii = [];
        /** @var DataConnection $database */
        $database = $this->getDb();
        $query = "SELECT * FROM {$database->getDatabase()}.pariuri";
        $result = $database->query($query);

        if ($database->affected_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $locatie = new PariuriEntity();
                $locatie->exchangeArray($row);
                $locatii[] = clone $locatie;
                unset($locatie);
            }
        }

        return $locatii;
    }


    /**
     * @param int $idLocatie
     * @return PariuriEntity
     */
    public function getLocatieById($idLocatie){
        /** @var DataConnection $database */
        $database = $this->getDb();
        $query = "SELECT * FROM {$database->getDatabase()}.pariuri WHERE idpariuri= {$idLocatie}";
        $locatie = new PariuriEntity();

        $result = $database->query($query);

        if($database->affected_rows > 0){
            $info = $result->fetch_assoc();
            $locatie->exchangeArray($info);
        }

        return $locatie;
    }

    public function updatePariuri(PariuriEntity $pariu){
        $database = $this->getDb();

        $query = "UPDATE {$database->getDatabase()}.pariuri SET
            denumire = '{$pariu->getDenumire()}',
            analitic = {$pariu->getAnalitic()},
            adresa = '{$pariu->getAdresa()}',
            corespondent = {$pariu->getCorespondent()},
            idPersonal = {$pariu->getIdPersonal()},
            dtIncepere = '{$pariu->getDtIncepere()}',
            dtInchidere = '{$pariu->getDtInchidere()}'
          WHERE idpariuri = {$pariu->getIdpariuri()};";
        $database->query($query);
        echo $database->error;
        if($database->affected_rows > 0){
            return TRUE;
        }
        return FALSE;
    }

    public function locatieExists(PariuriEntity $pariu){
        /** @var DataConnection $db */
        $db = $this->getDb();
        $query = "SELECT * FROM {$db->getDatabase()}.pariuri WHERE analitic = {$pariu->getAnalitic()}";

        $result = $db->query($query);

        if($db->affected_rows > 0) return TRUE;

        return FALSE;
    }

}