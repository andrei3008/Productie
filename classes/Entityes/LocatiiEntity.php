<?php

class LocatiiEntity
{
    private $idlocatie;
    private $idfirma;
    private $idresp;
    private $idOperator;
    private $denumire;
    private $regiune;
    private $localitate;
    private $codpostal;
    private $adresa;
    private $persContact;
    private $telefon;
    private $email;
    private $contractInternet;
    private $jackPot;
    private $tarifImpuls;
    private $fond;
    private $denFinante;
    private $coment;
    private $dtInfiintare;
    private $dtInchidere;
    private $data;
    private $bani;
    private $aparate = [];
    /** @var  DataConnection */
    private $db;
    /** @var  SessionClass */
    private $appSettings;


    public function __construct(DataConnection $db, SessionClass $appSettingsClass)
    {
        $this->setDb($db);
        $this->setAppSettings($appSettingsClass);
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
    public function getCodpostal()
    {
        return $this->codpostal;
    }

    /**
     * @param mixed $codpostal
     */
    public function setCodpostal($codpostal)
    {
        $this->codpostal = $codpostal;
    }

    /**
     * @return mixed
     */
    public function getComent()
    {
        return $this->coment;
    }

    /**
     * @param mixed $coment
     */
    public function setComent($coment)
    {
        $this->coment = $coment;
    }

    /**
     * @return mixed
     */
    public function getContractInternet()
    {
        return $this->contractInternet;
    }

    /**
     * @param mixed $contractInternet
     */
    public function setContractInternet($contractInternet)
    {
        $this->contractInternet = $contractInternet;
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
    public function getDenFinante()
    {
        return $this->denFinante;
    }

    /**
     * @param mixed $denFinante
     */
    public function setDenFinante($denFinante)
    {
        $this->denFinante = $denFinante;
    }

    /**
     * @return mixed
     */
    public function getDenumire()
    {
        return $this->denumire;
    }

    /**
     * @param mixed $denumire
     */
    public function setDenumire($denumire)
    {
        $this->denumire = $denumire;
    }

    /**
     * @return mixed
     */
    public function getDtInchidere()
    {
        $data = strtotime($this->dtInchidere);
        return date('d-M-Y', $data);
    }

    /**
     * @param mixed $dtInchidere
     */
    public function setDtInchidere($dtInchidere)
    {
        $this->dtInchidere = $dtInchidere;
    }

    /**
     * @return mixed
     */
    public function getDtInfiintare()
    {
        $data = strtotime($this->dtInfiintare);
        return date('d-M-Y', $data);
    }

    /**
     * @param mixed $dtInfiintare
     */
    public function setDtInfiintare($dtInfiintare)
    {
        $this->dtInfiintare = $dtInfiintare;
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
    public function getFond()
    {
        return $this->fond;
    }

    /**
     * @param mixed $fond
     */
    public function setFond($fond)
    {
        $this->fond = $fond;
    }

    /**
     * @return mixed
     */
    public function getIdfirma()
    {
        return $this->idfirma;
    }

    /**
     * @param mixed $idfirma
     */
    public function setIdfirma($idfirma)
    {
        $this->idfirma = $idfirma;
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
    public function getIdOperator()
    {
        return $this->idOperator;
    }

    /**
     * @param mixed $idOperator
     */
    public function setIdOperator($idOperator)
    {
        $this->idOperator = $idOperator;
    }

    /**
     * @return mixed
     */
    public function getIdresp()
    {
        return $this->idresp;
    }

    /**
     * @param mixed $idresp
     */
    public function setIdresp($idresp)
    {
        $this->idresp = $idresp;
    }

    /**
     * @return mixed
     */
    public function getJackPot()
    {
        return $this->jackPot;
    }

    /**
     * @param mixed $jackPot
     */
    public function setJackPot($jackPot)
    {
        $this->jackPot = $jackPot;
    }

    /**
     * @return mixed
     */
    public function getLocalitate()
    {
        return $this->localitate;
    }

    /**
     * @param mixed $localitate
     */
    public function setLocalitate($localitate)
    {
        $this->localitate = $localitate;
    }

    /**
     * @return mixed
     */
    public function getPersContact()
    {
        return $this->persContact;
    }

    /**
     * @param mixed $persContact
     */
    public function setPersContact($persContact)
    {
        $this->persContact = $persContact;
    }

    /**
     * @return mixed
     */
    public function getRegiune()
    {
        return $this->regiune;
    }

    /**
     * @param mixed $regiune
     */
    public function setRegiune($regiune)
    {
        $this->regiune = $regiune;
    }

    /**
     * @return mixed
     */
    public function getTarifImpuls()
    {
        return $this->tarifImpuls;
    }

    /**
     * @param mixed $tarifImpuls
     */
    public function setTarifImpuls($tarifImpuls)
    {
        $this->tarifImpuls = $tarifImpuls;
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

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getBani()
    {
        return $this->bani;
    }

    /**
     * @param mixed $bani
     */
    public function setBani($bani)
    {
        $this->bani = $bani;
    }

    /**
     * Returneaza castigurile pe luna curenta in milioane
     * @return int
     */
    public function getRoundBani()
    {
        return round($this->bani / 100);
    }

    /**
     * Returneaza sub formatul numar ani numar luni, numarul de luni de la afiliere
     * @return string
     */
    public function getLuniDeLaAfiliere()
    {
        $data_afiliere = new DateTime($this->getDtInfiintare());
        $data_acum = new DateTime(date('Y-m-d'));
        $diferenta = $data_acum->diff($data_afiliere);
        return $diferenta->format('%y ani si %m luni');
    }


    public function getAparateLocatie()
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.aparate WHERE aparate.idlocatie={$this->getIdlocatie()} AND (aparate.dtBlocare='1000-01-01' OR
                  ((aparate.dtBlocare >='{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-01') OR aparate.dtActivare >= '{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-01'))";
        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $aparat = new AparatEntity($this->getDb(),$this->getAppSettings());
                $aparat->exchangeArray($row);
                $this->aparate[] = clone $aparat;
                unset($aparat);
            }
        }

        return $this->aparate;
    }

    public function getFirma()
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.firme WHERE idfirma={$this->getIdfirma()}";

        $stmt = $this->getDb()->prepare($query);

        $firma = new FirmaEntity();

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $firma->exchangeArray($row);
        }

        return $firma;
    }

    public function getLocatie($idLocatie)
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.locatii WHERE idLocatie={$idLocatie}";

        $stmt = $this->getDb()->prepare($query);


        if($stmt->execute()) {
            $this->exchangeArray($stmt->fetch(PDO::FETCH_ASSOC));
        }
    }

    public function getAparate()
    {
        return $this->aparate;
    }

    public function getNumarAparateActive()
    {
        return count($this->aparate);
    }

    public function exchangeArray($data)
    {
        $this->idlocatie = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->idfirma = isset($data['idfirma']) ? $data['idfirma'] : NULL;
        $this->idresp = isset($data['idresp']) ? $data['idresp'] : NULL;
        $this->idOperator = isset($data['idOperator']) ? $data['idOperator'] : NULL;
        $this->denumire = isset($data['denumire']) ? $data['denumire'] : NULL;
        $this->regiune = isset($data['regiune']) ? $data['regiune'] : NULL;
        $this->localitate = isset($data['localitate']) ? $data['localitate'] : NULL;
        $this->codpostal = isset($data['codpostal']) ? $data['codpostal'] : NULL;
        $this->adresa = isset($data['adresa']) ? $data['adresa'] : NULL;
        $this->persContact = isset($data['persContact']) ? $data['persContact'] : NULL;
        $this->telefon = isset($data['telefon']) ? $data['telefon'] : NULL;
        $this->email = isset($data['email']) ? $data['email'] : NULL;
        $this->contractInternet = isset($data['contractInternet']) ? $data['contractInternet'] : NULL;
        $this->jackPot = isset($data['jackPot']) ? $data['jackPot'] : NULL;
        $this->tarifImpuls = isset($data['tarifInpuls']) ? $data['tarifInpuls'] : NULL;
        $this->fond = isset($data['fond']) ? $data['fond'] : NULL;
        $this->denFinante = isset($data['denFinante']) ? $data['denFinante'] : NULL;
        $this->coment = isset($data['coment']) ? $data['coment'] : NULL;
        $this->dtInfiintare = isset($data['dtInfiintare']) ? $data['dtInfiintare'] : NULL;
        $this->dtInchidere = isset($data['dtInchidere']) ? $data['dtInchidere'] : NULL;
        $this->data = isset($data['data']) ? $data['data'] : NULL;
        $this->bani = isset($data['bani']) ? $data['bani'] : 0;
    }

    public function isLocatieInchisa()
    {
        $maxZile = cal_days_in_month(CAL_GREGORIAN, $this->getAppSettings()->getLuna(), $this->getAppSettings()->getAn());
        $inceputLuna = new DateTime("{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-01");
        $sfarsitLuna = new DateTime("{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-{$maxZile}");
        $dtInfiintare = new DateTime($this->getDtInfiintare());
        $dtInchidere = new DateTime($this->getDtInchidere());
        if ($this->dtInchidere != '1000-01-01') {
            if ($inceputLuna > $dtInchidere OR $sfarsitLuna < $dtInfiintare) {
                return FALSE;
            }
        } else {
            if ($sfarsitLuna < $dtInfiintare) {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function getAllAparate()
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.aparate WHERE idlocatie={$this->getIdlocatie()}";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute() ) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $aparat = new AparatEntity($this->getDb(),$this->getAppSettings());
                $aparat->exchangeArray($row);
                $this->aparate[] = clone $aparat;
                unset($aparat);
            }
        }
        return $this->aparate;
    }


    public function __destruct()
    {
        unset($this->idlocatie);
        unset($this->idfirma);
        unset($this->idresp);
        unset($this->denumire);
        unset($this->regiune);
        unset($this->localitate);
        unset($this->codpostal);
        unset($this->adresa);
        unset($this->persContact);
        unset($this->telefon);
        unset($this->email);
        unset($this->contractInternet);
        unset($this->jackPot);
        unset($this->tarifImpuls);
        unset($this->fond);
        unset($this->denFinante);
        unset($this->coment);
        unset($this->dtInfiintare);
        unset($this->dtInchidere);
        unset($this->data);
    }

    public function areJackpot()
    {
        if ($this->jackPot == 1)
            return TRUE;
        return FALSE;
    }

    /**
     * @return JackpotEntity
     */
    public function getJackpotLuna()
    {
        $jackpot = new JackpotEntity($this->getDb(),$this->getAppSettings());
        $jackpot->exchangeArray([]);
        $maxZile = cal_days_in_month(CAL_GREGORIAN, $this->getAppSettings()->getLuna(), $this->getAppSettings()->getAn());
        $inceput = "{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-01";
        $sfarsit = "{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-{$maxZile}";

        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.jackpot WHERE idLocatie={$this->idlocatie} AND (data >= '{$inceput}' AND data <= '{$sfarsit}')";

        $stmt = $this->getDb()->prepare($query);


        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $jackpot->exchangeArray($row);
            }
        }

        return $jackpot;
    }

    public function getJackpotZilnic(){
        $data = $this->getAppSettings()->getCurentDate();

        $jackpot = new JackpotEntity($this->getDb(),$this->getAppSettings());


        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.jackpot WHERE idlocatie= :idLocatie AND data = :data";
        $stmt = $this->getDb()->prepare($query);

        $stmt->bindParam(":idLocatie",$this->getIdlocatie(),PDO::PARAM_INT);
        $stmt->bindParam(":data",$data,PDO::PARAM_STR);

        try{
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $jackpot->exchangeArray($result);
        }catch (PDOException $e){
            echo $e->getMessage();
        }

        return $jackpot;
    }

    public function getLocatieCurenta(){
        $this->getLocatie($this->getAppSettings()->getIdLocatie());
    }

    public function getLocatieByDenumire($searchString){
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.locatii WHERE UPPER(denumire) like '%{$searchString}%' LIMIT 1";


        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->exchangeArray($row);
        }

        if($this->idlocatie !== null)
            return TRUE;
        return FAlSE;
    }

    public function getAparateActive()
    {
        $aparate = [];
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.aparateactive WHERE {$this->getDb()->getDatabase()}.aparateactive.idLocatie= {$this->idlocatie}";
        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $aparat = new AparatEntity($this->getDb(),$this->getAppSettings());
                $aparat->exchangeArray($row);
                $aparate[] = clone $aparat;
                unset($aparat);
            }
        }
        return $aparate;
    }
    /**
     * ADDED - SILVIU - 02.09.2016
     */
    public function getNumeOperator()
    {
        $query = "SELECT a.denFirma FROM {$this->getDb()->getDatabase()}.operatori a WHERE a.idoperator=".$this->getIdOperator();

        $stmt = $this->getDb()->prepare($query);

        if($stmt->execute()){
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $operator = $row['denFirma'];
        }

        return $operator;
    }
}