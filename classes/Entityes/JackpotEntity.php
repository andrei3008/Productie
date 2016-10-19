<?php

class JackpotEntity{
    private $idjackpot;
    private $idlocatie;
    private $jack_pot;
    private $data;
    /** @var  DataConnection */
    private $db;
    /** @var  SessionClass */
    private $appSettings;

    public function __construct(DataConnection $db,SessionClass $sessionClass)
    {
        $this->setDb($db);
        $this->setAppSettings($sessionClass);
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

    /**
     * @return mixed
     */


    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getIdjackpot()
    {
        return $this->idjackpot;
    }

    /**
     * @param mixed $idjackpot
     */
    public function setIdjackpot($idjackpot)
    {
        $this->idjackpot = $idjackpot;
    }

    /**
     * @return mixed
     */
    public function getIdlocatie()
    {
        return $this->idlocatie;
    }

    /**
     * @param mixed $idlocatie
     */
    public function setIdlocatie($idlocatie)
    {
        $this->idlocatie = $idlocatie;
    }

    /**
     * @return mixed
     */
    public function getJackPot()
    {
        return $this->jack_pot;
    }

    /**
     * @param mixed $jack_pot
     */
    public function setJackPot($jack_pot)
    {
        $this->jack_pot = $jack_pot;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idjackpot    = isset($data['idjackpot']) ? $data['idjackpot'] : NULL;
        $this->idlocatie    = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->jack_pot     = isset($data['jack_pot']) ? $data['jack_pot'] : 0;
        $this->data         = isset($data['data']) ? $data['data'] : NULL;
    }


    public function save(){
        $query = "UPDATE {$this->getDb()->getDatabase()}.jackpot SET idlocatie='{$this->idlocatie}', jack_pot = '{$this->jack_pot}', data = '{$this->data}' WHERE idjackpot = {$this->idjackpot}";
        $result = $this->getDb()->query($query);
        echo $this->getDb()->error;

        if($this->getDb()->affected_rows > 0)
            return TRUE;
        return FALSE;
    }

    public function insert(){
        $query = "INSERT INTO {$this->getDb()->getDatabase()}.jackpot (idlocatie,jack_pot,data) VALUES ('{$this->idlocatie}','{$this->jack_pot}','{$this->data}')";

        $result = $this->getDb()->query($query);
        echo $this->getDb()->error;
        if($this->getDb()->affected_rows > 0)
            return TRUE;
        return FALSE;
    }
}