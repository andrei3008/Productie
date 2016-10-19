<?php

class ErrorPkTact{
    private $idpachet;
    private $idAparat;
    private $mac;
    private $exceptia;
    private $dtServer;

    /**
     * @return mixed
     */
    public function getDtServer()
    {
        return $this->dtServer;
    }

    /**
     * @param mixed $dtServer
     */
    public function setDtServer($dtServer)
    {
        $this->dtServer = $dtServer;
    }

    /**
     * @return mixed
     */
    public function getExceptia()
    {
        return $this->exceptia;
    }

    /**
     * @param mixed $exceptia
     */
    public function setExceptia($exceptia)
    {
        $this->exceptia = $exceptia;
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
    public function getIdpachet()
    {
        return $this->idpachet;
    }

    /**
     * @param mixed $idpachet
     */
    public function setIdpachet($idpachet)
    {
        $this->idpachet = $idpachet;
    }

    /**
     * @return mixed
     */
    public function getMac()
    {
        return $this->mac;
    }

    /**
     * @param mixed $mac
     */
    public function setMac($mac)
    {
        $this->mac = $mac;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idpachet = isset($data['idpachet']) ? $data['idpachet'] : NULL;
        $this->idAparat = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->mac      = isset($data['mac']) ? $data['mac'] : NULL;
        $this->exceptia = isset($data['exceptia']) ? $data['exceptia'] : NULL;
        $this->dtServer = isset($data['dtServer']) ? $data['dtServer'] : NULL;
    }
}