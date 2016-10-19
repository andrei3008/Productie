<?php
class PersonalMapper{
    /** @var  DataConnection */
    protected $db;
    /** @var  SessionClass */
    protected $appSettings;
    protected $colaboratoriPariuri = [];

    public function __construct(DataConnection $db,SessionClass $sessionClass)
    {
        $this->setDb($db);
        $this->setAppSettings($sessionClass);
    }

    /**
     * @return DataConnection
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param DataConnection $db
     */
    public function setDb($db)
    {
        $this->db = $db;
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
     * @param $idPersonal
     * @return PersonalEntity
     */
    public function getPersonal($idPersonal){
        $db = $this->getDb();
        $query = "SELECT * FROM {$db->getDatabase()}.personal WHERE personal.idpers = {$idPersonal}";
        $personal = new PersonalEntity($this->getDb(),$this->getAppSettings());
        $result = $db->query($query);
        if($result->rowCount() > 0){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $personal->exchangeArray($row);
        }
        return $personal;
    }


    public function getColaboratoriPariuri(DataConnection $db){
        $query = "SELECT idPersonal FROM {$db->getDatabase()}.agentii GROUP BY idPersonal";

        $result = $db->query($query);

        if($result->rowCount() > 0){
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $this->colaboratoriPariuri[] = $this->getPersonal($row['idPersonal']);
            }
        }
    }

    public function getColaboratori(DataConnection $db){
        $this->getColaboratoriPariuri($db);
        return $this->colaboratoriPariuri;
    }

    public function getResponsabbili(){
        $personal = [];
        $query = "SELECT DISTINCT {$this->getDb()->getDatabase()}.locatii.idresp FROM {$this->getDb()->getDatabase()}.locatii WHERE idOperator != 3";

        $result = $this->getDb()->query($query);

        if($result->rowCount() > 0){
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $persoana = $this->getPersonal($row['idresp']);
                $personal[] = clone $persoana;
                unset($persoana);
            }
        }
        return $personal;
    }

    public function getCurentPersonal(){
        return $this->getPersonal($this->getAppSettings()->getIdresp());
    }


    public function getIdPrimaLocatieResponsabil($id){
        $query = "SELECT {$this->getDb()->getDatabase()}.locatii.idLocatie FROM {$this->getDb()->getDatabase()}.locatii WHERE idresp = :idResp AND dtBlocare='1000-01-01' LIMIT 1";

        $stmt = $this->getDb()->prepare($query);
        $stmt->bindParam(":idResp",$id,PDO::PARAM_INT);

        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['idLocatie'];
        }
    }
}