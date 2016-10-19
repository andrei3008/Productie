<?php

class StareAparateMapper
{
    /** @var  SessionClass */
    protected $appSettings;
    /** @var  DataConnection */
    protected $db;

    public function __construct(DataConnection $db,SessionClass $appSettings)
    {
        $this->setDb($db);
        $this->setAppSettings($appSettings);
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

    public function updateStareAparate(StareAparatEntity $stare)
    {
        $query = "UPDATE {$this->getDb()->getDatabase()}.stareaparate SET";
        $proprietati = $stare->getArrayCopy();
        foreach ($proprietati as $key => $value)
        {
            if(!is_object($value) AND $key!= "oreDeLaUltimultPachet"){
                if($key != "macPic") {
                    $query .= " {$key} = '{$value}', ";
                }else{
                    $query .= " {$key} = '{$value}' ";
                }
            }
        }
        $query .= " WHERE idAparat = {$stare->getIdAparat()}";
        echo $query;
        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute())
        {
            return TRUE;
        }

        return $this->getDb()->errorInfo();

    }

    public function getStareAparatByIdAparat($idAparat)
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.stareaparate WHERE stareaparate.idAparat = '{$idAparat}'";

        $result = $this->getDb()->query($query);
        $stare = new StareAparatEntity($this->getDb(),$this->getAppSettings());

        $stare->exchangeArray($result->fetch(PDO::FETCH_ASSOC));

        return $stare;
    }
}