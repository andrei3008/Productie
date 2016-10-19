<?php
namespace Factoryes;

class FabricaDePersonal{
    /** @var  \DataConnection */
    private $db;
    /** @var  \SessionClass */
    private $appSettings;

    private $personal = [];

    /**
     * @return \SessionClass
     */
    public function getAppSettings()
    {
        return $this->appSettings;
    }

    /**
     * @param \SessionClass $appSettings
     */
    public function setAppSettings($appSettings)
    {
        $this->appSettings = $appSettings;
    }


    /**
     * @return \DataConnection
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param \DataConnection $db
     */
    public function setDb(\DataConnection $db)
    {
        $this->db = $db;
    }


    /**
     * FabricaDePersonal constructor.
     * @param \DataConnection $db
     */
    public function __construct(\DataConnection $db,\SessionClass $sessionClass)
    {
        $this->setDb($db);
        $this->setAppSettings($sessionClass);
        $this->getPersonal();
    }


    /**
     * getAllPersonal
     */
    public function getPersonal(){
        $dataConnection = $this->getDb();
        $query = "SELECT * FROM {$dataConnection->getDatabase()}.personal";
        $stmt = $dataConnection->prepare($query);
        if($stmt->execute()){
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $personal = new \PersonalEntity($this->getDb(),$this->getAppSettings());
                $personal->exchangeArray($row);
                $this->personal[$row['idpers']] = clone $personal;
                unset($personal);
            }
        }else{
            throw new \Exception("Nu este setat nici un reprezentant!");
        }

    }

    /**
     * @param \mysqli_result $result
     */
    public function hydrate(\mysqli_result $result){

    }

    /**
     * @param $id
     * @return \PersonalEntity
     */
    public function getPersoana($id){
        return $this->personal[$id];
    }
}