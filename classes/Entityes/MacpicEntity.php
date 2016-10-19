<?php

class MacpicEntity{
    private $idmacpic;
    private $macPic;
    private $dataInserare;

    /**
     * @return mixed
     */
    public function getDataInserare()
    {
        return $this->dataInserare;
    }

    /**
     * @param mixed $dataInserare
     */
    public function setDataInserare($dataInserare)
    {
        $this->dataInserare = $dataInserare;
    }

    /**
     * @return mixed
     */
    public function getIdmacpic()
    {
        return $this->idmacpic;
    }

    /**
     * @param mixed $idmacpic
     */
    public function setIdmacpic($idmacpic)
    {
        $this->idmacpic = $idmacpic;
    }

    /**
     * @return mixed
     */
    public function getMacPic()
    {
        return $this->macPic;
    }

    /**
     * @param mixed $macPic
     */
    public function setMacPic($macPic)
    {
        $this->macPic = $macPic;
    }

    public function exchangeArray($data){
        $this->idmacpic     = isset($data['idmacpic'])  ? $data['idmacpic'] : NULL;
        $this->macPic       = isset($data['macPic']) ? $data['macPic'] : NULL;
        $this->dataInserare = isset($data['dataInserare']) ? $data['dataInserare'] : NULL;
    }
}