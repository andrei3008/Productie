<?php

class FirmaEntity{
    private $idfirma;
    private $denumire;
    private $cui;
    private $reg_comert;
    private $activitatea_principala;
    private $regiune;
    private $localitate;
    private $codpostal;
    private $adresa;
    private $telefon;
    private $email;
    private $manager;
    private $data_colaborare;
    private $data;

    /**
     * @return mixed
     */
    public function getActivitateaPrincipala()
    {
        return $this->activitatea_principala;
    }

    /**
     * @param mixed $activitatea_principala
     */
    public function setActivitateaPrincipala($activitatea_principala)
    {
        $this->activitatea_principala = $activitatea_principala;
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
    public function getDataColaborare()
    {
        return $this->data_colaborare;
    }

    /**
     * @param mixed $data_colaborare
     */
    public function setDataColaborare($data_colaborare)
    {
        $this->data_colaborare = $data_colaborare;
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
    public function getRegComert()
    {
        return $this->reg_comert;
    }

    /**
     * @param mixed $reg_comert
     */
    public function setRegComert($reg_comert)
    {
        $this->reg_comert = $reg_comert;
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

    public function exchangeArray($data){
        $this->idfirma                  = isset($data['idfirma']) ? $data['idfirma'] : NULL;
        $this->denumire                 = isset($data['denumire']) ? $data['denumire'] : NULL;
        $this->cui                      = isset($data['cui']) ? $data['cui'] : NULL;
        $this->reg_comert               = isset($data['reg_comert']) ? $data['reg_comert'] : NULL;
        $this->activitatea_principala   = isset($data['activitatea_principala']) ? $data['activitatea_principala'] : NULL;
        $this->regiune                  = isset($data['regiune']) ? $data['regiune'] : NULL;
        $this->localitate               = isset($data['localitate']) ? $data['localitate'] : NULL;
        $this->codpostal                = isset($data['codpostal']) ? $data['codpostal'] : NULL;
        $this->adresa                   = isset($data['adresa']) ? $data['adresa'] : NULL;
        $this->telefon                  = isset($data['telefon']) ? $data['telefon'] : NULL;
        $this->email                    = isset($data['email']) ? $data['email'] : NULL;
        $this->manager                  = isset($data['manager']) ? $data['manager'] : NULL;
        $this->data_colaborare          = isset($data['data_colaborare']) ? $data['data_colaborare'] : NULL;
        $this->data                     = isset($data['data']) ? $data['data'] : NULL;

    }
}