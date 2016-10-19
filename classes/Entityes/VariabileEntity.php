<?php

class VariabileEntity{
    protected $idVariabila;
    protected $idaparat;
    protected $fm_mec;
    protected $pi_mec;
    protected $fm_elec;
    protected $pi_elec;
    protected $data;

    /**
     * @return mixed
     */
    public function getIdVariabila()
    {
        return $this->idVariabila;
    }

    /**
     * @param mixed $idVariabila
     */
    public function setIdVariabila($idVariabila)
    {
        $this->idVariabila = $idVariabila;
    }

    /**
     * @return mixed
     */
    public function getIdaparat()
    {
        return $this->idaparat;
    }

    /**
     * @param mixed $idaparat
     */
    public function setIdaparat($idaparat)
    {
        $this->idaparat = $idaparat;
    }

    /**
     * @return mixed
     */
    public function getFmMec()
    {
        return $this->fm_mec;
    }

    /**
     * @param mixed $fm_mec
     */
    public function setFmMec($fm_mec)
    {
        $this->fm_mec = $fm_mec;
    }

    /**
     * @return mixed
     */
    public function getPiMec()
    {
        return $this->pi_mec;
    }

    /**
     * @param mixed $pi_mec
     */
    public function setPiMec($pi_mec)
    {
        $this->pi_mec = $pi_mec;
    }

    /**
     * @return mixed
     */
    public function getFmElec()
    {
        return $this->fm_elec;
    }

    /**
     * @param mixed $fm_elec
     */
    public function setFmElec($fm_elec)
    {
        $this->fm_elec = $fm_elec;
    }

    /**
     * @return mixed
     */
    public function getPiElec()
    {
        return $this->pi_elec;
    }

    /**
     * @param mixed $pi_elec
     */
    public function setPiElec($pi_elec)
    {
        $this->pi_elec = $pi_elec;
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


    public function exchangeArray($data){
        $this->idVariabila = isset($data['idVariabila']) ? $data['idVariabila'] : NULL;
        $this->idaparat = isset($data['idaparat']) ? $data['idaparat'] : NULL;
        $this->fm_mec = isset($data['fm_mec']) ? $data['fm_mec'] : NULL;
        $this->pi_mec = isset($data['pi_mec']) ? $data['pi_mec'] : NULL;
        $this->fm_elec = isset($data['fm_elec']) ? $data['fm_elec'] : NULL;
        $this->pi_elec = isset($data['pi_elec']) ? $data['pi_elec'] : NULL;
        $this->data = isset($data['data']) ? $data['data'] : NULL;
    }


    public function getArrayCopy(){
        return get_object_vars($this);
    }
}