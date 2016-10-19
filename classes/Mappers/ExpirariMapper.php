<?php

class ExpirariMapper
{
    /** @var  DataConnection */
    protected $db;
    /** @var  SessionClass */
    protected $appSettings;

    public function __construct(DataConnection $db, SessionClass $appSettings)
    {
        $this->setDb($db);
        $this->setAppSettings($appSettings);
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

    public function getAparateLuna($nrLuna,$operator)
    {
        $regiuni = [];
        $maxZile = cal_days_in_month(CAL_GREGORIAN,$nrLuna,$this->getAppSettings()->getAn());
        $dataInceput = "{$this->getAppSettings()->getAn()}-{$nrLuna}-01";
        $sfarsitLuna = "{$this->getAppSettings()->getAn()}-{$nrLuna}-{$maxZile}";
        $query = "SELECT {$this->getDb()->getDatabase()}.locatii.regiune FROM {$this->getDb()->getDatabase()}.locatii
                INNER JOIN {$this->getDb()->getDatabase()}.aparate ON aparate.idLocatie = locatii.idlocatie
                INNER JOIN {$this->getDb()->getDatabase()}.avertizari ON aparate.idAparat = avertizari.idAparat
                WHERE {$this->getDb()->getDatabase()}.avertizari.dtExpMetrologie >= '{$dataInceput}' AND {$this->getDb()->getDatabase()}.avertizari.dtExpMetrologie <= '{$sfarsitLuna}' AND {$this->getDb()->getDatabase()}.locatii.idOperator = {$operator}
                GROUP BY {$this->getDb()->getDatabase()}.locatii.regiune";
        echo $query;
        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $regiuni[] = $row["regiune"];
            }
        }


        return $regiuni;
    }
}