<?php


class LocatiiMaper{
    /** @var  DataConnection */
    protected $db;
    /** @var  SessionClass */
    protected $appSettings;

    public function __construct(DataConnection $dbFull, SessionClass $appSettingsClass)
    {
        $this->setDb($dbFull);
        $this->setAppSettings($appSettingsClass);
    }

    /**
     * @return DataConnection
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb(DataConnection $db)
    {
        $this->db = $db;
    }

    public function getLocatii(){
        $locatii = [];

        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.locatii";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $locatie = new LocatiiEntity($this->getDb(),$this->getAppSettings());
                $locatie->exchangeArray($row);
                $locatii[] = clone $locatie;
                unset($locatie);
            }
        }
        return $locatii;
    }


    public function getLocatie($idLocatie){
        /** @var DataConnection $database */
        $database = $this->getDb();
        $locatie = new LocatiiEntity($database,$this->getAppSettings());
        $locatie->exchangeArray([]);

        $query = "SELECT * FROM {$database->getDatabase()}.locatii WHERE idlocatie={$idLocatie}";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            $locatie->exchangeArray($info);
        }
        return $locatie;
    }

    public function getLocatieByName($numeLocatie){
        /** @var DataConnection $database */
        $database = $this->getDb();
        $locatie = new LocatiiEntity($database,$this->getAppSettings());
        $locatie->exchangeArray([]);
        $query = "SELECT * FROM {$database->getDatabase()}.locatii WHERE trim(denumire) = '{$numeLocatie}'";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            $locatie->exchangeArray($info);
        }
        return $locatie;

    }

    /**
     * @param $an
     * @param $luna
     * @param $nrZile
     * @return array
     */
    public function getLocatiiInchise($an,$luna,$nrZile){
        $locatii = [];
        /** @var DataConnection $database */
        $database = $this->getDb();

        $query = "SELECT * FROM {$database->getDatabase()}.locatii WHERE dtInchidere >= '{$an}-{$luna}-1' AND dtInchidere <= '{$an}-{$luna}-{$nrZile}'";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $locatie = new LocatiiEntity($database,$this->getAppSettings());
                $locatie->exchangeArray($row);
                $locatii[] = clone $locatie;
                unset($locatie);
            }
        }

        return $locatii;
    }

    public function getLocatiiDeschise($an,$luna,$nrZile){
        $locatii = [];
        /** @var DataConnection $database */
        $database = $this->getDb();

        $query = "SELECT * FROM {$database->getDatabase()}.locatii WHERE dtInfiintare >= '{$an}-{$luna}-1' AND dtInfiintare <= '{$an}-{$luna}-{$nrZile}'";

        $stmt = $database->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $locatie = new LocatiiEntity($database,$this->getAppSettings());
                $locatie->exchangeArray($row);
                $locatii[] = clone $locatie;
                unset($locatie);
            }
        }

        return $locatii;
    }

    public function getNrLuniAsociereArray(){
        $array = [];
        /** @var DataConnection $db */
        $db = $this->getDb();
        $query = "SELECT locatii.idLocatie, timestampdiff(MONTH,locatii.dtInfiintare,curdate()) AS luni FROM {$db->getDatabase()}.locatii";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $array[$row->idLocatie] = $row->luni;
            }
        }
        return $array;
    }

    public function getCastigLunarLocatie($an,$luna){
        /** @var DataConnection $db */
        $db =$this->getDb();
        $suma = [];
        $query = "SELECT {$db->getDatabase()}.contormecanic{$an}{$luna}.idLocatie,SUM(castig) as totalLocatie FROM {$db->getDatabase()}.contormecanic{$an}{$luna} GROUP by contormecanic{$an}{$luna}.idlocatie";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $suma[$row['idLocatie']] = $row['totalLocatie'];
            }
        }

        return $suma;
    }

    public function getZoneDistincte(){
        $query = "SELECT distinct {$this->getDb()->getDatabase()}.locatii.regiune FROM {$this->getDb()->getDatabase()}.locatii ORDER BY regiune ASC";


        $regiuni = [];

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $regiuni[] = $row['regiune'];
            }
        }
        return $regiuni;
    }

    /**
     * @return SessionClass
     */
    public function getAppSettings()
    {
        return $this->appSettings;
    }

    /**
     * @param SessionClass $appSettings
     */
    public function setAppSettings($appSettings)
    {
        $this->appSettings = $appSettings;
    }


    /**
     * @param $regiune
     * @return array|LocatiiEntity
     */
    public function getLocatiiByRegiune($regiune){
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.locatii WHERE regiune = :regiune AND idOperator = :operator";

        $locatii = array();

        $stmt = $this->getDb()->prepare($query);
        $stmt->bindParam(":regiune",$regiune,PDO::PARAM_STR);
        $stmt->bindParam(":operator",$this->getAppSettings()->getOperator(),PDO::PARAM_INT);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $locatie = new LocatiiEntity($this->getDb(),$this->getAppSettings());
                $locatie->exchangeArray($row);
                $locatii[] = clone $locatie;
                unset($locatie);
            }
        }

        return $locatii;
    }

    public function getNumarLocatii($idPers = '', $idOp = '')
    {
        $inceputLuna = $this->getAppSettings()->inceputLuna();
        $sfarsitLuna = $this->getAppSettings()->sfarsitLuna();
        $query = "SELECT count(*) as nrLocatii FROM {$this->getDb()->getDatabase()}.locatii WHERE ";
        if ($idPers != '') {
            $query .= "idresp = :idResponsabil AND ";
        }
        if ($idOp != ''){
            $query .= "idOperator = :idOperator AND ";
        }
        $query .= " (dtInfiintare < :sfarsit AND (dtInchidere >=:inceput OR dtInchidere ='1000-01-01')) AND denumire NOT LIKE '%Depozit%'";
        $stmt = $this->getDb()->prepare($query);
        if($idPers !=''){
            $stmt->bindParam(":idResponsabil", $idPers, \PDO::PARAM_STR);
        }
        if($idOp !=''){
            $stmt->bindParam(":idOperator", $idOp, \PDO::PARAM_STR);
        }


        $stmt->bindParam(":inceput", $inceputLuna, \PDO::PARAM_STR);
        $stmt->bindParam(":sfarsit", $sfarsitLuna, \PDO::PARAM_STR);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $row['nrLocatii'];

    }

    public function getCurrentLocation()
    {
        return $this->getLocatie($this->getAppSettings()->getIdLocatie());
    }

}