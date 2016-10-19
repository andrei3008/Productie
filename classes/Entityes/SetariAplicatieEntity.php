<?php

/**
 * Class SetariAplicatieEntity
 */
class SetariAplicatieEntity{
    /**
     * @var
     */
    private $idsetariAplicatie;
    /**
     * @var
     */
    private $pragCashIn;
    /**
     * @var
     */
    private $pragCashOut;
    /**
     * @var
     */
    private $pragCastig;
    /**
     * @var
     */
    private $nrZileMetrologie;
    /**
     * @var
     */
    private $nrZileAutorizatie;
    /**
     * @var
     */
    private $mailuri;

    /**
     * @var
     */
    private $db;

    /**
     * @var
     */
    private $avertizeazaResponsabil;

    /**
     * @return mixed
     */
    public function getIdsetariAplicatie()
    {
        return $this->idsetariAplicatie;
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
    public function setDb($db)
    {
        $this->db = $db;
    }


    /**
     * @param mixed $idsetariAplicatie
     */
    public function setIdsetariAplicatie($idsetariAplicatie)
    {
        $this->idsetariAplicatie = $idsetariAplicatie;
    }

    /**
     * @return mixed
     */
    public function getNrZileAutorizatie()
    {
        return $this->nrZileAutorizatie;
    }

    /**
     * @param mixed $nrZileAutorizatie
     */
    public function setNrZileAutorizatie($nrZileAutorizatie)
    {
        $this->nrZileAutorizatie = $nrZileAutorizatie;
    }

    /**
     * @return mixed
     */
    public function getNrZileMetrologie()
    {
        return $this->nrZileMetrologie;
    }

    /**
     * @param mixed $nrZileMetrologie
     */
    public function setNrZileMetrologie($nrZileMetrologie)
    {
        $this->nrZileMetrologie = $nrZileMetrologie;
    }

    /**
     * @return mixed
     */
    public function getPragCashIn()
    {
        return $this->pragCashIn;
    }

    /**
     * @param mixed $pragCashIn
     */
    public function setPragCashIn($pragCashIn)
    {
        $this->pragCashIn = $pragCashIn;
    }

    /**
     * @return mixed
     */
    public function getPragCashOut()
    {
        return $this->pragCashOut;
    }

    /**
     * @param mixed $pragCashOut
     */
    public function setPragCashOut($pragCashOut)
    {
        $this->pragCashOut = $pragCashOut;
    }

    /**
     * @return mixed
     */
    public function getPragCastig()
    {
        return $this->pragCastig;
    }

    /**
     * @param mixed $pragCastig
     */
    public function setPragCastig($pragCastig)
    {
        $this->pragCastig = $pragCastig;
    }

    /**
     * @return mixed
     */
    public function getMailuri()
    {
        return $this->mailuri;
    }

    /**
     * @param mixed $mailuri
     */
    public function setMailuri($mailuri)
    {
        $this->mailuri = $mailuri;
    }

    /**
     * @return array
     */
    public function getArrayCopy(){
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getAvertizeazaResponsabil()
    {
        return $this->avertizeazaResponsabil;
    }

    /**
     * @param $avertizeazaResponsabil
     */
    public function setAvertizareResponsabil($avertizeazaResponsabil)
    {
        $this->avertizeazaResponsabil = $avertizeazaResponsabil;
    }


    /**
     * @param $valori
     */
    public function exchangeArray($valori){
        $this->pragCashIn = (isset($valori['pragCashIn']) ? $valori['pragCashIn'] : null);
        $this->pragCashOut = (isset($valori['pragCashOut']) ? $valori['pragCashOut'] : null);
        $this->pragCastig = (isset($valori['pragCastig']) ? $valori['pragCastig'] : NULL);
        $this->nrZileMetrologie = (isset($valori['nrZileMetrologie']) ? $valori['nrZileMetrologie'] : NULL);
        $this->nrZileAutorizatie = (isset($valori['nrZileAutorizatie']) ? $valori['nrZileAutorizatie'] : NULL);
        $this->mailuri = (isset($valori['mailuri']) ? $valori['mailuri'] : NULL);
        $this->avertizeazaResponsabil = (isset($valori['avertizeazaResponsabil']) ? $valori['avertizeazaResponsabil'] : 0);
    }


    /**
     * @param $email
     * @return bool
     */
    public function eliminaEmail($email){
        if(strpos($this->mailuri,$email) !== FALSE){
            $this->mailuri= str_replace(','.$email,'',$this->mailuri);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @return string
     */
    public function salveazaMailuri(){
        $dataConector = $this->getDb();
        $query = "UPDATE {$dataConector->getDatabase()}.setariaplicatie SET mailuri='$this->mailuri' WHERE idsetariAplicatie=1";
        $dataConector->runQuery($query);
    }

    public function __construct(DataConnection $dbFull)
    {
        $this->setDb($dbFull);
        $inBaza = $dbFull->getAplicationSettings();
        $this->exchangeArray($inBaza);
    }

}