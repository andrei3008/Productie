<?php

class AgentiiEntity{
    protected $idagentie;
    protected $denumire;
    protected $analitic;
    protected $adresa;
    protected $corespondent;
    protected $idPersonal;
    protected $dtIncepere;
    protected $dtInchidere;

    /**
     * @return mixed
     */
    public function getIdagentie()
    {
        return $this->idagentie;
    }

    /**
     * @param mixed $idagentie
     */
    public function setIdagentie($idagentie)
    {
        $this->idagentie = $idagentie;
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
    public function getAnalitic()
    {
        return $this->analitic;
    }

    /**
     * @param mixed $analitic
     */
    public function setAnalitic($analitic)
    {
        $this->analitic = $analitic;
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
    public function getCorespondent()
    {
        return $this->corespondent;
    }

    /**
     * @param mixed $corespondent
     */
    public function setCorespondent($corespondent)
    {
        $this->corespondent = $corespondent;
    }

    /**
     * @return mixed
     */
    public function getIdPersonal()
    {
        return $this->idPersonal;
    }

    /**
     * @param mixed $idPersonal
     */
    public function setIdPersonal($idPersonal)
    {
        $this->idPersonal = $idPersonal;
    }

    /**
     * @return mixed
     */
    public function getDtIncepere()
    {
        return $this->dtIncepere;
    }

    /**
     * @param mixed $dtIncepere
     */
    public function setDtIncepere($dtIncepere)
    {
        $this->dtIncepere = $dtIncepere;
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
     * @param $data
     */
    public function exchangeArray($data){
        $this->idagentie = isset($data['idpariuri']) ? $data['idpariuri'] : NULL;
        $this->denumire = isset($data['denumire']) ? $data['denumire'] : NULL;
        $this->analitic = isset($data['analitic']) ? $data['analitic'] : NULL;
        $this->adresa = isset($data['adresa']) ? $data['adresa'] : NULL;
        $this->corespondent = isset($data['corespondent']) ? $data['corespondent'] : NULL;
        $this->idPersonal = isset($data['idPersonal']) ? $data['idPersonal'] : NULL;
        $this->dtIncepere = isset($data['dtIncepere']) ? $data['dtIncepere'] : NULL;
        $this->dtInchidere = isset($data['dtIncheiere']) ? $data['dtIncheiere'] : NULL;
    }

    /**
     * @return array
     */
    public function getArrayCopy(){
        return get_object_vars($this);
    }
}