<?php

class PersonalEntity
{
    private $idpers;
    private $idsup;
    private $idlocatie;
    private $nick;
    private $nume;
    private $prenume;
    private $cnp;
    private $adresa;
    private $telefon;
    private $email;
    private $user;
    private $pass;
    private $codpc;
    private $codtel;
    private $resetPass;
    private $grad;
    private $data;
    private $privilegii;
    private $agentiiPariuri = [];
    /** @var  DataConnection */
    private $db;
    /** @var  SessionClass */
    private $appSettings;

    public function __construct(DataConnection $db, SessionClass $appSettings)
    {
        $this->setDb($db);
        $this->setAppSettings($appSettings);
    }

    public function getPersonal()
    {
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
    public function setDb(DataConnection $db)
    {
        $this->db = $db;
    }


    /**
     * @return mixed
     */
    public function getAdresa()
    {
        return $this->adresa;
    }

    /**
     * @param mixed $adresa
     */
    public function setAdresa($adresa)
    {
        $this->adresa = $adresa;
    }

    /**
     * @return mixed
     */
    public function getCnp()
    {
        return $this->cnp;
    }

    /**
     * @param mixed $cnp
     */
    public function setCnp($cnp)
    {
        $this->cnp = $cnp;
    }

    /**
     * @return mixed
     */
    public function getCodpc()
    {
        return $this->codpc;
    }

    /**
     * @param mixed $codpc
     */
    public function setCodpc($codpc)
    {
        $this->codpc = $codpc;
    }

    /**
     * @return mixed
     */
    public function getCodtel()
    {
        return $this->codtel;
    }

    /**
     * @param mixed $codtel
     */
    public function setCodtel($codtel)
    {
        $this->codtel = $codtel;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getGrad()
    {
        return $this->grad;
    }

    /**
     * @param mixed $grad
     */
    public function setGrad($grad)
    {
        $this->grad = $grad;
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
    public function getIdpers()
    {
        return $this->idpers;
    }

    /**
     * @param mixed $idpers
     */
    public function setIdpers($idpers)
    {
        $this->idpers = $idpers;
    }

    /**
     * @return mixed
     */
    public function getIdsup()
    {
        return $this->idsup;
    }

    /**
     * @param mixed $idsup
     */
    public function setIdsup($idsup)
    {
        $this->idsup = $idsup;
    }

    /**
     * @return mixed
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param mixed $nick
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
    }

    /**
     * @return mixed
     */
    public function getNume()
    {
        return $this->nume;
    }

    /**
     * @param mixed $nume
     */
    public function setNume($nume)
    {
        $this->nume = $nume;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return mixed
     */
    public function getPrenume()
    {
        return $this->prenume;
    }

    /**
     * @param mixed $prenume
     */
    public function setPrenume($prenume)
    {
        $this->prenume = $prenume;
    }

    /**
     * @return mixed
     */
    public function getResetPass()
    {
        return $this->resetPass;
    }

    /**
     * @param mixed $resetPass
     */
    public function setResetPass($resetPass)
    {
        $this->resetPass = $resetPass;
    }

    /**
     * @return mixed
     */
    public function getTelefon()
    {
        return $this->telefon;
    }

    /**
     * @param mixed $telefon
     */
    public function setTelefon($telefon)
    {
        $this->telefon = $telefon;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @param DataConnection $db
     * @param $an
     * @param $luna
     * @param null $order
     * @param null $direction
     * @return array|LocatiiEntity
     */
    public function  getLocatii(DataConnection $db, $an, $luna, $order = NULL, $direction = NULL)
    {
        $locatii = [];


        $query = "SELECT *, sum(contormecanic{$an}{$luna}.castig) as bani FROM {$db->getDatabase()}.locatii INNER JOIN {$db->getDatabase()}.contormecanic{$an}{$luna} ON contormecanic{$an}{$luna}.idLocatie = locatii.idlocatie
                  WHERE locatii.idresp={$this->getIdpers()} GROUP BY locatii.idlocatie ";

        if ($order != NULL and $direction != '') {
            $query .= " ORDER BY {$order} {$direction}";
        }

        $result = $db->query($query);

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $locatie = new LocatiiEntity($this->getDb(), $this->getAppSettings());
                $locatie->exchangeArray($row);
                $locatii[] = clone $locatie;
                unset($locatie);
            }
        }

        return $locatii;
    }

    public function setAgentiiPariuri(DataConnection $db)
    {
        $query = "SELECT * FROM {$db->getDatabase()}.agentii WHERE idPersonal = {$this->idpers}";

        $result = $db->query($query);

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $agentiePariuri = new AgentiiEntity();
                $agentiePariuri->exchangeArray($row);
                $this->agentiiPariuri[] = clone $agentiePariuri;
                unset($agentiePariuri);
            }
        }
    }

    public function getAgentiiPariuri(DataConnection $db)
    {
        $this->setAgentiiPariuri($db);
        return $this->agentiiPariuri;
    }

    public function getNumarAgentiiPariuri()
    {
        return count($this->agentiiPariuri);
    }

    /**
     * @param $data
     */
    public function exchangeArray($data)
    {
        $this->idpers = isset($data['idpers']) ? $data['idpers'] : NULL;
        $this->idsup = isset($data['idsup']) ? $data['idsup'] : NULL;
        $this->idlocatie = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->nick = isset($data['nick']) ? $data['nick'] : NULL;
        $this->nume = isset($data['nume']) ? $data['nume'] : NULL;
        $this->prenume = isset($data['prenume']) ? $data['prenume'] : NULL;
        $this->cnp = isset($data['cnp']) ? $data['cnp'] : NULL;
        $this->adresa = isset($data['adresa']) ? $data['adresa'] : NULL;
        $this->telefon = isset($data['telefon']) ? $data['telefon'] : NULL;
        $this->email = isset($data['email']) ? $data['email'] : NULL;
        $this->user = isset($data['user']) ? $data['user'] : NULL;
        $this->pass = isset($data['pass']) ? $data['pass'] : NULL;
        $this->codpc = isset($data['codpc']) ? $data['codpc'] : NULL;
        $this->codtel = isset($data['codTel']) ? $data['codTel'] : NULL;
        $this->resetPass = isset($data['resetPass']) ? $data['resetPass'] : NULL;
        $this->grad = isset($data['grad']) ? $data['grad'] : NULL;
        $this->privilegii = isset($data['privilegii']) ? $data['privilegii'] : NULL;
        $this->data = isset($data['data']) ? $data['data'] : NULL;
    }


    public function getRegiuni()
    {
        $query = "SELECT DISTINCT {$this->getDb()->getDatabase()}.locatii.regiune FROM {$this->getDb()->getDatabase()}.locatii WHERE idResp = :idResponsabil";

        $stmt = $this->getDb()->prepare($query);
        $regiuni = [];
        $stmt->bindParam(":idResponsabil", $this->idpers, PDO::PARAM_INT);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $regiuni[] = $row['regiune'];
            }
        } else {
            var_dump($this->getDb()->errorInfo());
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

    public function getLocatiiByRegiune()
    {
        $locatii = [];
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.locatii WHERE idresp={$this->appSettings->getIdresp()}  AND ";
        if ($this->getAppSettings()->getZona() != '') {
            $query .= "regiune='{$this->getAppSettings()->getZona()}' AND ";
        }
        $query .= " idOperator={$this->getAppSettings()->getOperator()} ORDER BY dtInfiintare ASC";
        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $locatie = new LocatiiEntity($this->getDb(), $this->getAppSettings());
                $locatie->exchangeArray($row);
                $locatii[] = clone $locatie;
                unset($locatie);
            }
        }

        return $locatii;
    }

    /**
     * @param null $idOperator
     * @return mixed
     */
    public function getNumarAparateOperator($idOperator = null)
    {
        $query = "SELECT count(*) as nrAparate FROM {$this->getDb()->getDatabase()}.aparate INNER JOIN
        {$this->getDb()->getDatabase()}.locatii ON locatii.idLocatie = aparate.idLocatie WHERE idResp = {$this->getIdpers()} AND dtBlocare='1000-01-01' ";
        if ($idOperator != NULL) {
            $query .= " AND locatii.idOperator = {$idOperator}  ";
        }
        $result = $this->getDb()->query($query);
        $array = $result->fetch(\PDO::FETCH_ASSOC);
        return $array['nrAparate'];
    }

    /**
     * @param int $idOperator
     * @return int
     */
    public function getNumarAparateDepozitOperator($idOperator = null)
    {
        $query = "SELECT count(*) as nrAparate FROM {$this->getDb()->getDatabase()}.aparate where ";
        if ($idOperator === 1) {
            $query .= " aparate.idLocatie = 0 AND ";
        } elseif ($idOperator === 2) {
            $query .= "aparate.idLocatie = 441 AND ";
        } else {
            $query .= "(aparate.idlocatie=0 OR aparate.idlocatie=441) AND ";
        }
        $query .= "aparate.idLocVechi in (SELECT idlocatie FROM {$this->getDb()->getDatabase()}.locatii where locatii.idresp ={$this->getIdpers()}) AND dtBlocare = \"1000-01-01\"";


        $result = $this->getDb()->query($query);
        $info = $result->fetch(\PDO::FETCH_ASSOC);
        return $info['nrAparate'];
    }

    public function getNumarLocatii()
    {
        $inceputLuna = $this->getAppSettings()->inceputLuna();
        $sfarsitLuna = $this->getAppSettings()->sfarsitLuna();
        $query = "SELECT count(*) as nrLocatii FROM {$this->getDb()->getDatabase()}.locatii WHERE idresp = :idResponsabil AND ";
        if ($this->getAppSettings()->getOperator() !== null) {
            $query .= "idOperator = :idOperator AND ";
        }
        $query .= " (dtInfiintare < :sfarsit AND (dtInchidere >=:inceput OR dtInchidere ='1000-01-01')) AND denumire NOT LIKE '%Depozit%'";
        $stmt = $this->getDb()->prepare($query);


        if ($this->getAppSettings()->getOperator() !== null) {
            $stmt->bindParam(":idOperator", $this->getAppSettings()->getOperator(), \PDO::PARAM_STR);
        }

        $stmt->bindParam(":idResponsabil", $this->getIdpers(), \PDO::PARAM_STR);
        $stmt->bindParam(":inceput", $inceputLuna, \PDO::PARAM_STR);
        $stmt->bindParam(":sfarsit", $sfarsitLuna, \PDO::PARAM_STR);

        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        return $row['nrLocatii'];

    }

    public function  getLocatiiNew($order = NULL, $direction = NULL)
    {
        $locatii = [];

        $query = "SELECT *, sum(contormecanic{$this->getAppSettings()->getAn()}{$this->getAppSettings()->getLuna()}.castig) as bani FROM {$this->getDb()->getDatabase()}.locatii INNER JOIN {$this->getDb()->getDatabase()}.contormecanic{$this->getAppSettings()->getAn()}{$this->getAppSettings()->getLuna()} ON contormecanic{$this->getAppSettings()->getAn()}{$this->getAppSettings()->getLuna()}.idLocatie = locatii.idlocatie
                  WHERE locatii.idresp={$this->getIdpers()} ";
        if ($this->getAppSettings()->getOperator() != NULL) {
            $query .= " AND idOperator={$this->getAppSettings()->getOperator()} ";
        }
        $query .= " GROUP BY locatii.idlocatie ";

        if ($order != NULL and $direction != NULL) {
            $query .= " ORDER BY {$order} {$direction}";
        }

        $result = $this->getDb()->query($query);
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $locatie = new LocatiiEntity($this->getDb(), $this->getAppSettings());
            $locatie->exchangeArray($row);
            $locatii[] = clone $locatie;
            unset($locatie);
        }

        return $locatii;
    }

    /**
     * @param SessionClass $appSettings
     */
    public function setAppSettings($appSettings)
    {
        $this->appSettings = $appSettings;
    }


}