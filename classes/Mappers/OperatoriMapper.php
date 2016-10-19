<?php

class OperatoriMapper{
    /** @var  DataConnection */
    protected $db;
    /** @var  SessionClass */
    protected $appSettings;
    protected $operatori = [];

    /**
     * OperatoriMapper constructor.
     * @param DataConnection $db
     */
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
     * @return array OperatorEntity
     */
    public function getOpertatori(){
        $db = $this->getDb();
        $query = "SELECT * FROM {$db->getDatabase()}.operatori";

        $result = $db->query($query);

        if($result->rowCount() > 0){
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $operator = new OperatorEntity($this->getDb(),$this->getAppSettings());
                $operator->exchangeArray($row);
                $this->operatori[] = clone $operator;
                unset($operator);
            }
        }
        return $this->operatori;
    }

    /**
     * @param $idOperator
     * @return OperatorEntity
     */
    public function getOperator($idOperator){
        $db = $this->getDb();
        $operator = new OperatorEntity($this->getDb(),$this->getAppSettings());
        $query = "SELECT * FROM {$db->getDatabase()}.operatori WHERE idOperator = {$idOperator}";

        $result = $db->query($query);

        if($result->rowCount() > 0){
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $operator->exchangeArray($row);
        }
        return $operator;
    }

    public function getCurrentOperator(){
        return $this->getOperator($this->getAppSettings()->getOperator());
    }
}