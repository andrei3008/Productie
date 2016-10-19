<?php
class ContactEntity{
    private $id;
    private $nume;
    private $prenume;
    private $cnp;
    private $mesaj;
    private $data;

    /**
     * @return mixed
     */
    public function getCnp()
    {
        return $this->cnp;
    }

    /**
     * @param mixed $cnp
     */
    public function setCnp($cnp)
    {
        $this->cnp = $cnp;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMesaj()
    {
        return $this->mesaj;
    }

    /**
     * @param mixed $mesaj
     */
    public function setMesaj($mesaj)
    {
        $this->mesaj = $mesaj;
    }

    /**
     * @return mixed
     */
    public function getNume()
    {
        return $this->nume;
    }

    /**
     * @param mixed $nume
     */
    public function setNume($nume)
    {
        $this->nume = $nume;
    }

    /**
     * @return mixed
     */
    public function getPrenume()
    {
        return $this->prenume;
    }

    /**
     * @param mixed $prenume
     */
    public function setPrenume($prenume)
    {
        $this->prenume = $prenume;
    }

    public function exchangeArray($data){
        $this->id       = isset($data['id'])? $data['id'] : NULL;
        $this->nume     = isset($data['nume']) ? $data['nume'] : NULL;
        $this->prenume  = isset($data['prenume']) ? $data['prenume'] : NULL;
        $this->cnp      = isset($data['cnp']) ? $data['cnp'] : NULL;
        $this->mesaj    = isset($data['mesaj']) ? $data['mesaj'] : NULL;
        $this->data     = isset($data['data']) ? $data['data'] : NULL;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }
}