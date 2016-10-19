<?php

class MetadataEntity{
    private $id;
    private $dataoprire;
    private $ipserver;
    private $domeniu;
    private $versiunFW;
    private $taxaAparat;
    private $comandaPic;

    /**
     * @return mixed
     */
    public function getComandaPic()
    {
        return $this->comandaPic;
    }

    /**
     * @param mixed $comandaPic
     */
    public function setComandaPic($comandaPic)
    {
        $this->comandaPic = $comandaPic;
    }

    /**
     * @return mixed
     */
    public function getDataoprire()
    {
        return $this->dataoprire;
    }

    /**
     * @param mixed $dataoprire
     */
    public function setDataoprire($dataoprire)
    {
        $this->dataoprire = $dataoprire;
    }

    /**
     * @return mixed
     */
    public function getDomeniu()
    {
        return $this->domeniu;
    }

    /**
     * @param mixed $domeniu
     */
    public function setDomeniu($domeniu)
    {
        $this->domeniu = $domeniu;
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
    public function getIpserver()
    {
        return $this->ipserver;
    }

    /**
     * @param mixed $ipserver
     */
    public function setIpserver($ipserver)
    {
        $this->ipserver = $ipserver;
    }

    /**
     * @return mixed
     */
    public function getTaxaAparat()
    {
        return $this->taxaAparat;
    }

    /**
     * @param mixed $taxaAparat
     */
    public function setTaxaAparat($taxaAparat)
    {
        $this->taxaAparat = $taxaAparat;
    }

    /**
     * @return mixed
     */
    public function getVersiunFW()
    {
        return $this->versiunFW;
    }

    /**
     * @param mixed $versiunFW
     */
    public function setVersiunFW($versiunFW)
    {
        $this->versiunFW = $versiunFW;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->id           = isset($data['id']) ? $data['id'] : NULL;
        $this->dataoprire   = isset($data['dataoprire']) ? $data['dataoprire'] : NULL;
        $this->ipserver     = isset($data['ipserver']) ? $data['ipserver'] : NULL;
        $this->domeniu      = isset($data['domeniu']) ? $data['domeniu'] : NULL;
        $this->versiunFW    = isset($data['versiuneFW']) ? $data['versiuneFW'] : NULL;
        $this->taxaAparat   = isset($data['taxaAparat']) ? $data['taxaAparat'] : NULL;
        $this->comandaPic   = isset($data['comandaPic']) ? $data['comandaPic'] : NULL;
    }
}