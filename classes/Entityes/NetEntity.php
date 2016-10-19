<?php

class NetEntity{
    private $idNet;
    private $idLoc;
    private $tip;
    private $port;
    private $pmUser;
    private $valUser;
    private $pmPass;
    private $valPass;
    private $data;

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
    public function getIdLoc()
    {
        return $this->idLoc;
    }

    /**
     * @param mixed $idLoc
     */
    public function setIdLoc($idLoc)
    {
        $this->idLoc = $idLoc;
    }

    /**
     * @return mixed
     */
    public function getIdNet()
    {
        return $this->idNet;
    }

    /**
     * @param mixed $idNet
     */
    public function setIdNet($idNet)
    {
        $this->idNet = $idNet;
    }

    /**
     * @return mixed
     */
    public function getPmPass()
    {
        return $this->pmPass;
    }

    /**
     * @param mixed $pmPass
     */
    public function setPmPass($pmPass)
    {
        $this->pmPass = $pmPass;
    }

    /**
     * @return mixed
     */
    public function getPmUser()
    {
        return $this->pmUser;
    }

    /**
     * @param mixed $pmUser
     */
    public function setPmUser($pmUser)
    {
        $this->pmUser = $pmUser;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * @param mixed $tip
     */
    public function setTip($tip)
    {
        $this->tip = $tip;
    }

    /**
     * @return mixed
     */
    public function getValPass()
    {
        return $this->valPass;
    }

    /**
     * @param mixed $valPass
     */
    public function setValPass($valPass)
    {
        $this->valPass = $valPass;
    }

    /**
     * @return mixed
     */
    public function getValUser()
    {
        return $this->valUser;
    }

    /**
     * @param mixed $valUser
     */
    public function setValUser($valUser)
    {
        $this->valUser = $valUser;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idNet    = isset($data['idNet']) ? $data['idNet'] : NULL;
        $this->idLoc    = isset($data['idLoc']) ? $data['idLoc'] : NULL;
        $this->tip      = isset($data['tip']) ? $data['tip'] : NULL;
        $this->port     = isset($data['port']) ? $data['port'] : NULL;
        $this->pmUser   = isset($data['omUser']) ? $data['pmUser'] : NULL;
        $this->valUser  = isset($data['valUser']) ? $data['valUser'] : NULL;
        $this->pmPass   = isset($data['pmPass']) ? $data['pmPass'] : NULL;
        $this->valPass  = isset($data['valPass']) ? $data['valPass'] : NULL;
        $this->data     = isset($data['data']) ? $data['data'] : NULL;
    }
}