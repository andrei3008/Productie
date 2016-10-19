<?php
class AvertizareEntity{
    private $idAparat;
    private $dtExpAutorizatie;
    private $dtExpMetrologie;
    private $zileAvAutorizatie;
    private $zileAvMetrologie;
    private $pragCashIn;
    private $pragCashOut;
    private $zileAvNeutilizat;
    private $zileAvLipsaComunicare;
    private $AvSMS;
    private $AvEmail;

    /**
     * @return mixed
     */
    public function getAvEmail()
    {
        return $this->AvEmail;
    }

    /**
     * @param mixed $AvEmail
     */
    public function setAvEmail($AvEmail)
    {
        $this->AvEmail = $AvEmail;
    }

    /**
     * @return mixed
     */
    public function getAvSMS()
    {
        return $this->AvSMS;
    }

    /**
     * @param mixed $AvSMS
     */
    public function setAvSMS($AvSMS)
    {
        $this->AvSMS = $AvSMS;
    }

    /**
     * @return mixed
     */
    public function getDtExpAutorizatie()
    {
        return $this->dtExpAutorizatie;
    }

    /**
     * @param mixed $dtExpAutorizatie
     */
    public function setDtExpAutorizatie($dtExpAutorizatie)
    {
        $this->dtExpAutorizatie = $dtExpAutorizatie;
    }

    /**
     * @return mixed
     */
    public function getDtExpMetrologie()
    {
        return $this->dtExpMetrologie;
    }

    /**
     * @param mixed $dtExpMetrologie
     */
    public function setDtExpMetrologie($dtExpMetrologie)
    {
        $this->dtExpMetrologie = $dtExpMetrologie;
    }

    /**
     * @return mixed
     */
    public function getIdAparat()
    {
        return $this->idAparat;
    }

    /**
     * @param mixed $idAparat
     */
    public function setIdAparat($idAparat)
    {
        $this->idAparat = $idAparat;
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
    public function getZileAvAutorizatie()
    {
        return $this->zileAvAutorizatie;
    }

    /**
     * @param mixed $zileAvAutorizatie
     */
    public function setZileAvAutorizatie($zileAvAutorizatie)
    {
        $this->zileAvAutorizatie = $zileAvAutorizatie;
    }

    /**
     * @return mixed
     */
    public function getZileAvLipsaComunicare()
    {
        return $this->zileAvLipsaComunicare;
    }

    /**
     * @param mixed $zileAvLipsaComunicare
     */
    public function setZileAvLipsaComunicare($zileAvLipsaComunicare)
    {
        $this->zileAvLipsaComunicare = $zileAvLipsaComunicare;
    }

    /**
     * @return mixed
     */
    public function getZileAvMetrologie()
    {
        return $this->zileAvMetrologie;
    }

    /**
     * @param mixed $zileAvMetrologie
     */
    public function setZileAvMetrologie($zileAvMetrologie)
    {
        $this->zileAvMetrologie = $zileAvMetrologie;
    }

    /**
     * @return mixed
     */
    public function getZileAvNeutilizat()
    {
        return $this->zileAvNeutilizat;
    }

    /**
     * @param mixed $zileAvNeutilizat
     */
    public function setZileAvNeutilizat($zileAvNeutilizat)
    {
        $this->zileAvNeutilizat = $zileAvNeutilizat;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function arrayExchange($data){
        $this->idAparat                 = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->dtExpMetrologie          = isset($data['dtExpMetrologie']) ? $data['dtExpMetrologie'] : NULL;
        $this->dtExpAutorizatie         = isset($data['dtExpAutorizatie']) ? $data['dtExpAutorizatie'] : NULL;
        $this->zileAvAutorizatie        = isset($data['zileAvAutorizatie']) ? $data['zileAvAutorizatie'] : NULL;
        $this->zileAvMetrologie         = isset($data['zileAvMetrologie']) ? $data['zileAvMetrologie'] : NULL;
        $this->pragCashIn               = isset($data['pragCashIn']) ? $data['pragCashIn'] : NULL;
        $this->pragCashOut              = isset($data['pragCashOut']) ? $data['pragCashOut'] : NULL;
        $this->zileAvNeutilizat         = isset($data['zileAvNeutilizat']) ? $data['zileAvNeutilizat'] : NULL;
        $this->zileAvLipsaComunicare    = isset($data['zileAvLipsaComunicare']) ? $data['zileAvLipsaComunicare'] : NULL;
        $this->AvSMS                    = isset($data['AvSMS']) ? $data['AvSMS']  : NULL;
        $this->AvEmail                  = isset($data['AvEmail']) ? $data['AvEmail'] : NULL;
    }
}