<?php

class OperatorEntity
{
    /** @var  int */
    private $idoperator;
    /** @var  string */
    private $denFirma;
    /** @var  string */
    private $denBaza;
    /** @var  string */
    private $cui;
    /** @var  string */
    private $regComert;
    /** @var  string */
    private $manager;
    /** @var  string */
    private $telefon;
    /** @var  string */
    private $email;
    /** @var  string */
    private $user;
    /** @var  string */
    private $pass;
    /** @var  string */
    private $domiciliuFiscal;
    /** @var  string */
    private $capitalSocial;
    /** @var  string */
    private $licenta;
    /** @var  string */
    private $dtActivare;
    /** @var  string */
    private $dtLastGenTabel;
    /** @var  string */
    private $dtIncetare;
    /** @var  DataConnection */
    private $db;
    /** @var  SessionClass */
    private $appSettings;

    /**
     * OperatorEntity constructor.
     * @param DataConnection $db
     * @param SessionClass $appSettings
     */
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
     * @return string
     */
    public function getDomiciliuFiscal()
    {
        return $this->domiciliuFiscal;
    }

    /**
     * @param string $domiciliuFiscal
     */
    public function setDomiciliuFiscal($domiciliuFiscal)
    {
        $this->domiciliuFiscal = $domiciliuFiscal;
    }

    /**
     * @return string
     */
    public function getCapitalSocial()
    {
        return $this->capitalSocial;
    }

    /**
     * @param string $capitalSocial
     */
    public function setCapitalSocial($capitalSocial)
    {
        $this->capitalSocial = $capitalSocial;
    }

    /**
     * @return string
     */
    public function getLicenta()
    {
        return $this->licenta;
    }

    /**
     * @param string $licenta
     */
    public function setLicenta($licenta)
    {
        $this->licenta = $licenta;
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
     * @return mixed
     */
    public function getCui()
    {
        return $this->cui;
    }

    /**
     * @param mixed $cui
     */
    public function setCui($cui)
    {
        $this->cui = $cui;
    }

    /**
     * @return mixed
     */
    public function getDenBaza()
    {
        return $this->denBaza;
    }

    /**
     * @param mixed $denBaza
     */
    public function setDenBaza($denBaza)
    {
        $this->denBaza = $denBaza;
    }

    /**
     * @return mixed
     */
    public function getDenFirma()
    {
        return $this->denFirma;
    }

    /**
     * @param mixed $denFirma
     */
    public function setDenFirma($denFirma)
    {
        $this->denFirma = $denFirma;
    }

    /**
     * @return mixed
     */
    public function getDtActivare()
    {
        return $this->dtActivare;
    }

    /**
     * @param mixed $dtActivare
     */
    public function setDtActivare($dtActivare)
    {
        $this->dtActivare = $dtActivare;
    }

    /**
     * @return mixed
     */
    public function getDtIncetare()
    {
        return $this->dtIncetare;
    }

    /**
     * @param mixed $dtIncetare
     */
    public function setDtIncetare($dtIncetare)
    {
        $this->dtIncetare = $dtIncetare;
    }

    /**
     * @return mixed
     */
    public function getDtLastGenTabel()
    {
        return $this->dtLastGenTabel;
    }

    /**
     * @param mixed $dtLastGenTabel
     */
    public function setDtLastGenTabel($dtLastGenTabel)
    {
        $this->dtLastGenTabel = $dtLastGenTabel;
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
    public function getIdoperator()
    {
        return $this->idoperator;
    }

    /**
     * @param mixed $idoperator
     */
    public function setIdoperator($idoperator)
    {
        $this->idoperator = $idoperator;
    }

    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param mixed $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
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
    public function getRegComert()
    {
        return $this->regComert;
    }

    /**
     * @param mixed $regComert
     */
    public function setRegComert($regComert)
    {
        $this->regComert = $regComert;
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

    public function exchangeArray($data)
    {
        $this->idoperator = isset($data['idoperator']) ? $data['idoperator'] : NULL;
        $this->denFirma = isset($data['denFirma']) ? $data['denFirma'] : NULL;
        $this->denBaza = isset($data['denBaza']) ? $data['denBaza'] : NULL;
        $this->cui = isset($data['cui']) ? $data['cui'] : NULL;
        $this->regComert = isset($data['regComert']) ? $data['regComert'] : NULL;
        $this->manager = isset($data['manager']) ? $data['manager'] : NULL;
        $this->telefon = isset($data['telefon']) ? $data['telefon'] : NULL;
        $this->email = isset($data['email']) ? $data['email'] : NULL;
        $this->user = isset($data['user']) ? $data['user'] : NULL;
        $this->pass = isset($data['pass']) ? $data['pass'] : NULL;
        $this->dtActivare = isset($data['dtActivare']) ? $data['dtActivare'] : NULL;
        $this->dtLastGenTabel = isset($data['dtLastGenTabel']) ? $data['dtLastGenTabel'] : NULL;
        $this->dtIncetare = isset($data['dtIncetare']) ? $data['dtIncetare'] : NULL;
        $this->domiciliuFiscal = isset($data['domiciliuFiscal']) ? $data['domiciliuFiscal'] : NULL;
        $this->capitalSocial = isset($data['capitalSocial']) ? $data['capitalSocial'] : NULL;
        $this->licenta = isset($data['licenta']) ? $data['licenta'] : NULL;
    }

    public function getCurrentOperator()
    {
        $this->getOperator($this->getAppSettings()->getOperator());
    }

    public function getOperator($idOperator)
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.operatori WHERE idOperator = :idOperator";

        $stmt = $this->getDb()->prepare($query);

        $stmt->bindParam(":idOperator", $idOperator, PDO::PARAM_INT);

        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->exchangeArray($info);
    }
}