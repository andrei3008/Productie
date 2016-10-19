<?php


class TransferuriMapper
{
    /** @var  DataConnection */
    protected $db;
    /** @var  SessionClass */
    protected $appSettings;

    public function __construct(DataConnection $db, SessionClass $appSettings)
    {
        $this->setAppSettings($appSettings);
        $this->setDb($db);
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

    public function getTransferuri(){
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.transferaparate WHERE ";
    }
}