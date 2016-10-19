<?php

class MacPicMapper
{
    /** @var  DataConnection */
    protected $db;
    /** @var  SessionClass */
    protected $appSettings;

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


    public function getMacs(){
        $macs = [];
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.macpicneasociat";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $mac = new MacPic($this->getDb(),$this->getAppSettings());
                $mac->exchangeArray($row);
                $macs[] = clone $mac;
                unset($mac);
            }
        }

        return $macs;
    }

    public function getMacPic($idMacPic)
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.macpicneasociat WHERE idmacpic= :idMacPic";

        $stmt = $this->getDb()->prepare($query);
        $stmt->bindParam(":idMacPic",$idMacPic,PDO::PARAM_INT);


        $mac = new MacPic($this->getDb(),$this->getAppSettings());

        if($stmt->execute())
        {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $mac->exchangeArray($row);
        }
        return $mac;
    }

    public function getStringComanda($biti)
    {
        $string = '';
        for ($i = 31; $i >= 0; $i--) {
            if (!isset($biti[$i])) {
                $string .= 0;
            } else {
                $string .= $biti[$i];
            }
        }
        return bindec($string);
    }


    public function deleteMac(MacPic $mac)
    {
        $query = "DELETE FROM {$this->getDb()->getDatabase()}.macpicneasociat WHERE idmacpic = {$mac->getIdmacpic()}";

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute())
        {
            return TRUE;
        }
        return FALSE;
    }

    public function deleteMacByIp($ip){
        $query = "DELETE FROM {$this->getDb()->getDatabase()}.macpicneasociat WHERE ip='".$ip."'";
        echo $query;
        $stmt = $this->getDb()->prepare($query);

        // $stmt->bindParam(":ipPic",$ip);

        if($stmt->execute())
            return TRUE;
        return FALSE;
    }

    /**
     * ADDED - 01.09.2016 - SILVIU
     * PRELUARE MACURI NEASOCIATE CU SERII SI LOCATII SI APARATE
     *
     * @return     array
     */
    public function getMacs2(){
        $macs = [];
        $query = "
                SELECT
                        m.*,
                        m.idAparat AS idAparatMac,
                        s.idAparat,
                        a.seria,
                        a.idLocatie,
                        l.adresa,
                        s.stareRetur,
                        l.denumire
                FROM
                    {$this->getDb()->getDatabase()}.macpicneasociat m
                        LEFT JOIN
                    {$this->getDb()->getDatabase()}.stareaparate s ON m.macPic = s.macPic
                        LEFT JOIN
                    {$this->getDb()->getDatabase()}.aparate a ON a.idAparat = s.idAparat
                        LEFT JOIN
                    {$this->getDb()->getDatabase()}.locatii l ON l.idlocatie = a.idLocatie
                ORDER BY s.stareRetur ASC, m.ip ASC
        ";
        $stmt = $this->getDb()->prepare($query);

        if ($exec = $stmt->execute()) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $mac = new MacPic($this->getDb(),$this->getAppSettings());
                $mac->exchangeArray($row);
                $macs[] = clone $mac;
                unset($mac);
            }
        }

        return $macs;
    }
}