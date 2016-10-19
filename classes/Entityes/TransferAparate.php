<?php


class TransferAparate{
    private $idtransfer;
    private $idApInainte;
    private $idLocInainte;
    private $idApDupa;
    private $idLocDupa;
    private $grupTransfer;
    private $dtPVR;
    private $dtONJN;
    private $dtDGFP;
    private $dtPVPF;
    private $dtBaza;

    /**
     * @return mixed
     */
    public function getDtBaza()
    {
        return $this->dtBaza;
    }

    /**
     * @param mixed $dtBaza
     */
    public function setDtBaza($dtBaza)
    {
        $this->dtBaza = $dtBaza;
    }

    /**
     * @return mixed
     */
    public function getDtDGFP()
    {
        return $this->dtDGFP;
    }

    /**
     * @param mixed $dtDGFP
     */
    public function setDtDGFP($dtDGFP)
    {
        $this->dtDGFP = $dtDGFP;
    }

    /**
     * @return mixed
     */
    public function getDtONJN()
    {
        return $this->dtONJN;
    }

    /**
     * @param mixed $dtONJN
     */
    public function setDtONJN($dtONJN)
    {
        $this->dtONJN = $dtONJN;
    }

    /**
     * @return mixed
     */
    public function getDtPVPF()
    {
        return $this->dtPVPF;
    }

    /**
     * @param mixed $dtPVPF
     */
    public function setDtPVPF($dtPVPF)
    {
        $this->dtPVPF = $dtPVPF;
    }

    /**
     * @return mixed
     */
    public function getDtPVR()
    {
        return $this->dtPVR;
    }

    /**
     * @param mixed $dtPVR
     */
    public function setDtPVR($dtPVR)
    {
        $this->dtPVR = $dtPVR;
    }

    /**
     * @return mixed
     */
    public function getGrupTransfer()
    {
        return $this->grupTransfer;
    }

    /**
     * @param mixed $grupTransfer
     */
    public function setGrupTransfer($grupTransfer)
    {
        $this->grupTransfer = $grupTransfer;
    }

    /**
     * @return mixed
     */
    public function getIdApDupa()
    {
        return $this->idApDupa;
    }

    /**
     * @param mixed $idApDupa
     */
    public function setIdApDupa($idApDupa)
    {
        $this->idApDupa = $idApDupa;
    }

    /**
     * @return mixed
     */
    public function getIdApInainte()
    {
        return $this->idApInainte;
    }

    /**
     * @param mixed $idApInainte
     */
    public function setIdApInainte($idApInainte)
    {
        $this->idApInainte = $idApInainte;
    }

    /**
     * @return mixed
     */
    public function getIdLocDupa()
    {
        return $this->idLocDupa;
    }

    /**
     * @param mixed $idLocDupa
     */
    public function setIdLocDupa($idLocDupa)
    {
        $this->idLocDupa = $idLocDupa;
    }

    /**
     * @return mixed
     */
    public function getIdLocInainte()
    {
        return $this->idLocInainte;
    }

    /**
     * @param mixed $idLocInainte
     */
    public function setIdLocInainte($idLocInainte)
    {
        $this->idLocInainte = $idLocInainte;
    }

    /**
     * @return mixed
     */
    public function getIdtransfer()
    {
        return $this->idtransfer;
    }

    /**
     * @param mixed $idtransfer
     */
    public function setIdtransfer($idtransfer)
    {
        $this->idtransfer = $idtransfer;
    }

    /**
     * @return array
     */
    public function getObjectArray(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idtransfer = isset($data['idtransfer']) ? $data['idtransfer'] : NULL;
        $this->idApInainte = isset($data['idApInainte']) ? $data['idApInainte'] : NULL;
        $this->idLocInainte = isset($data['idLocInainte']) ? $data['idLocInainte'] : NULL;
        $this->idApDupa = isset($data['idApDupa']) ? $data['idApDupa'] : NULL;
        $this->idLocDupa = isset($data['idLocDupa']) ? $data['idLocDupa'] : NULL;
        $this->grupTransfer = isset($data['grupTransfer']) ? $data['grupTransfer'] : NULL;
        $this->dtPVR    = isset($data['dtPVR']) ? $data['dtPVR'] : NULL;
        $this->dtONJN = isset($data['dtONJN']) ? $data['dtONJN'] : NULL;
        $this->dtDGFP = isset($data['dtDGFP']) ? $data['dtDGFP'] : NULL;
        $this->dtPVPF = isset($data['dtPVPF']) ? $data['dtPVPF'] : NULL;
        $this->dtBaza  = isset($data['dtBaza']) ? $data['dtBaza'] : NULL;
    }

    public function __construct($data)
    {
        $this->exchangeArray($data);
    }
}