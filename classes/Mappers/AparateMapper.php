<?php


class AparateMapper
{
    /** @var  DataConnection */
    protected $db;
    /** @var  SessionClass */
    protected $appSettings;
    protected $aparate = [];

    /**
     * AparateMapper constructor.
     * @param DataConnection $db
     */
    public function __construct(DataConnection $db, SessionClass $sessionClass)
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
     * @param $idLocatie
     * @return mixed
     */
    public function getAparate($idLocatie)
    {
        /** @var DataConnection $db */
        $db = $this->getDb();

        $query = "SELECT * FROM {$db->getDatabase()}.aparate";
        if ($idLocatie != 0) {
            $query .= " WHERE idLocatie={$idLocatie}";
        }

        $result = $db->query($query);

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $aparat = new AparatEntity($this->getDb(), $this->getAppSettings());
                $aparat->exchangeArray($row);
                $this->aparate[] = clone $aparat;
                unset($aparat);
            }
        }

        return $this->aparate;
    }

    /**
     * @param $id
     * @return AparatEntity
     */
    public function getAparat($id)
    {
        $db = $this->getDb();
        $aparat = new AparatEntity($this->getDb(), $this->getAppSettings());
        $query = "SELECT * FROM {$db->getDatabase()}.aparate WHERE idAparat={$id}";

        $result = $db->query($query);

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $aparat->exchangeArray($row);
        }

        return $aparat;
    }


    public function getDistinctSerii()
    {
        $serii = [];
        $query = "SELECT {$this->getDb()->getDatabase()}.aparateactive.seria FROM {$this->getDb()->getDatabase()}.aparateactive";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $serii[] = $row['seria'];
            }
        }

        return $serii;
    }

    public function getAparatBySerie($serie)
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.aparate WHERE aparate.seria = '{$serie}' AND (dtBlocare='1000-01-01' OR seria='S_TEST1' OR seria='S_TEST2')";

        $result = $this->getDb()->query($query);
        $aparat = new AparatEntity($this->getDb(),$this->getAppSettings());

        $aparat->exchangeArray($result->fetch(PDO::FETCH_ASSOC));

        return $aparat;
    }
}