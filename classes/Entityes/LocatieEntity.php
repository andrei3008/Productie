<?php

class LocatieEntity{
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
    private $db;

    /**
     * LocatieEntity constructor.
     * @param dbFull $dbFull
     */
    public function __construct(dbFull $dbFull){
        $this->setDb($dbFull);
    }

    public function getLocatie($id){
        $dataConnection = $this->getDb();
        $query = "SELECT * FROM {$dataConnection->getDatabase()}.locatii WHERE idlocatie=$id";
        $result = $dataConnection->query($query);
        if($dataConnection->affected_rows>0){
            $row = $result->fetch_assoc();
            $this->exchangeArrat($row);
        }
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb(dbFull $db)
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
        return $this->dtInchidere;
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
        return $this->dtInfiintare;
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

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArrat($data){
        $this->idlocatie        = isset($data['idlocatie']) ? $data['idlocatie'] : NULL;
        $this->idfirma          = isset($data['idfirma']) ? $data['idfirma'] : NULL;
        $this->idresp           = isset($data['idresp']) ? $data['idresp'] : NULL;
        $this->denumire         = isset($data['denumire']) ? $data['denumire'] : NULL;
        $this->regiune          = isset($data['regiune']) ? $data['regiune'] : NULL;
        $this->localitate       = isset($data['localitate']) ? $data['localitate'] : NULL;
        $this->codpostal        = isset($data['codpostal']) ? $data['codpostal'] : NULL;
        $this->adresa           = isset($data['adresa']) ? $data['adresa'] : NULL;
        $this->persContact      = isset($data['persContact']) ? $data['persContact'] : NULL;
        $this->telefon          = isset($data['telefon']) ? $data['telefon'] : NULL;
        $this->email            = isset($data['email']) ? $data['email'] : NULL;
        $this->contractInternet = isset($data['contractInternet']) ? $data['contractInternet'] : NULL;
        $this->jackPot          = isset($data['jackPot']) ? $data['jackPot'] : NULL;
        $this->tarifImpuls      = isset($data['tarifInpuls']) ? $data['tarifInpuls'] : NULL;
        $this->fond             = isset($data['fond']) ? $data['fond'] : NULL;
        $this->denFinante       = isset($data['denFinante']) ? $data['denFinante'] : NULL;
        $this->coment           = isset($data['coment']) ? $data['coment'] : NULL;
        $this->dtInfiintare     = isset($data['dtInfiintare']) ? $data['dtInfiintare'] : NULL;
        $this->dtInchidere      = isset($data['dtInchidere']) ? $data['dtInchidere'] : NULL;
        $this->data             = isset($data['data']) ? $data['data'] : NULL;
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
}