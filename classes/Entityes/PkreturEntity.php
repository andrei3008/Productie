<?php

class PkreturEntity{
    private $bytesComanda;
    private $idOp;
    private $idResp;
    private $idLoc;
    private $idAp;
    private $idxInM;
    private $idxOutM;
    private $idxBetM;
    private $timpOff;
    private $timpPachet1;
    private $timpPachet2;
    private $adrPachet1;
    private $adrPachet2;
    private $adrPachet3;
    private $hostNamePic;
    private $userPic;
    private $passPic;

    /**
     * @return mixed
     */
    public function getAdrPachet1()
    {
        return $this->adrPachet1;
    }

    /**
     * @param mixed $adrPachet1
     */
    public function setAdrPachet1($adrPachet1)
    {
        $this->adrPachet1 = $adrPachet1;
    }

    /**
     * @return mixed
     */
    public function getAdrPachet2()
    {
        return $this->adrPachet2;
    }

    /**
     * @param mixed $adrPachet2
     */
    public function setAdrPachet2($adrPachet2)
    {
        $this->adrPachet2 = $adrPachet2;
    }

    /**
     * @return mixed
     */
    public function getAdrPachet3()
    {
        return $this->adrPachet3;
    }

    /**
     * @param mixed $adrPachet3
     */
    public function setAdrPachet3($adrPachet3)
    {
        $this->adrPachet3 = $adrPachet3;
    }

    /**
     * @return mixed
     */
    public function getBytesComanda()
    {
        return $this->bytesComanda;
    }

    /**
     * @param mixed $bytesComanda
     */
    public function setBytesComanda($bytesComanda)
    {
        $this->bytesComanda = $bytesComanda;
    }

    /**
     * @return mixed
     */
    public function getHostNamePic()
    {
        return $this->hostNamePic;
    }

    /**
     * @param mixed $hostNamePic
     */
    public function setHostNamePic($hostNamePic)
    {
        $this->hostNamePic = $hostNamePic;
    }

    /**
     * @return mixed
     */
    public function getIdAp()
    {
        return $this->idAp;
    }

    /**
     * @param mixed $idAp
     */
    public function setIdAp($idAp)
    {
        $this->idAp = $idAp;
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
    public function getIdOp()
    {
        return $this->idOp;
    }

    /**
     * @param mixed $idOp
     */
    public function setIdOp($idOp)
    {
        $this->idOp = $idOp;
    }

    /**
     * @return mixed
     */
    public function getIdResp()
    {
        return $this->idResp;
    }

    /**
     * @param mixed $idResp
     */
    public function setIdResp($idResp)
    {
        $this->idResp = $idResp;
    }

    /**
     * @return mixed
     */
    public function getIdxBetM()
    {
        return $this->idxBetM;
    }

    /**
     * @param mixed $idxBetM
     */
    public function setIdxBetM($idxBetM)
    {
        $this->idxBetM = $idxBetM;
    }

    /**
     * @return mixed
     */
    public function getIdxInM()
    {
        return $this->idxInM;
    }

    /**
     * @param mixed $idxInM
     */
    public function setIdxInM($idxInM)
    {
        $this->idxInM = $idxInM;
    }

    /**
     * @return mixed
     */
    public function getIdxOutM()
    {
        return $this->idxOutM;
    }

    /**
     * @param mixed $idxOutM
     */
    public function setIdxOutM($idxOutM)
    {
        $this->idxOutM = $idxOutM;
    }

    /**
     * @return mixed
     */
    public function getPassPic()
    {
        return $this->passPic;
    }

    /**
     * @param mixed $passPic
     */
    public function setPassPic($passPic)
    {
        $this->passPic = $passPic;
    }

    /**
     * @return mixed
     */
    public function getTimpOff()
    {
        return $this->timpOff;
    }

    /**
     * @param mixed $timpOff
     */
    public function setTimpOff($timpOff)
    {
        $this->timpOff = $timpOff;
    }

    /**
     * @return mixed
     */
    public function getTimpPachet1()
    {
        return $this->timpPachet1;
    }

    /**
     * @param mixed $timpPachet1
     */
    public function setTimpPachet1($timpPachet1)
    {
        $this->timpPachet1 = $timpPachet1;
    }

    /**
     * @return mixed
     */
    public function getTimpPachet2()
    {
        return $this->timpPachet2;
    }

    /**
     * @param mixed $timpPachet2
     */
    public function setTimpPachet2($timpPachet2)
    {
        $this->timpPachet2 = $timpPachet2;
    }

    /**
     * @return mixed
     */
    public function getUserPic()
    {
        return $this->userPic;
    }

    /**
     * @param mixed $userPic
     */
    public function setUserPic($userPic)
    {
        $this->userPic = $userPic;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->bytesComanda = isset($data['bytesComanda']) ? $data['bytesComanda'] : NULL;
        $this->idOp         = isset($data['idOp']) ? $data['idOp'] : NULL;
        $this->idResp       = isset($data['idResp']) ? $data['idResp'] : NULL;
        $this->idLoc        = isset($data['idLoc']) ? $data['idLoc'] : NULL;
        $this->idAp         = isset($data['idAp']) ? $data['idAp'] : NULL;
        $this->idxInM       = isset($data['idxInM']) ? $data['idxInM'] : NULL;
        $this->idxOutM      = isset($data['idxOutM']) ? $data['idxOutM'] : NULL;
        $this->idxBetM      = isset($data['idxBetM']) ? $data['idxBetM'] : NULL;
        $this->timpOff      = isset($data['timpOff']) ? $data['timpOff'] : NULL;
        $this->timpPachet1  = isset($data['timpPachet1']) ? $data['timpPachet1'] : NULL;
        $this->timpPachet2  = isset($data['timpPachet2']) ? $data['timpPachet2'] : NULL;
        $this->adrPachet1   = isset($data['adrPachet1']) ? $data['adrPachet1'] : NULL;
        $this->adrPachet2   = isset($data['adrPachet2']) ? $data['adrPachet2'] : NULL;
        $this->adrPachet3   = isset($data['adrPachet3']) ? $data['adrPachet3'] : NULL;
        $this->hostNamePic  = isset($data['hostNamePic']) ? $data['hostNamePic'] : NULL;
        $this->userPic      = isset($data['userPic']) ? $data['userPic'] : NULL;
        $this->passPic      = isset($data['passPic']) ? $data['passPic'] : NULL;
    }
}