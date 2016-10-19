<?php

class AuditPicEntity{
    private $idAudit;
    private $idAparat;
    private $idPers;
    private $seria;
    private $postPic;
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
    public function getIdAudit()
    {
        return $this->idAudit;
    }

    /**
     * @param mixed $idAudit
     */
    public function setIdAudit($idAudit)
    {
        $this->idAudit = $idAudit;
    }

    /**
     * @return mixed
     */
    public function getIdPers()
    {
        return $this->idPers;
    }

    /**
     * @param mixed $idPers
     */
    public function setIdPers($idPers)
    {
        $this->idPers = $idPers;
    }

    /**
     * @return mixed
     */
    public function getPostPic()
    {
        return $this->postPic;
    }

    /**
     * @param mixed $postPic
     */
    public function setPostPic($postPic)
    {
        $this->postPic = $postPic;
    }

    /**
     * @return mixed
     */
    public function getSeria()
    {
        return $this->seria;
    }

    /**
     * @param mixed $seria
     */
    public function setSeria($seria)
    {
        $this->seria = $seria;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function arrayExchange($data){
        $this->idAudit  = isset($data['idAudit']) ? $data['idAudit'] : NULL;
        $this->idAparat = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->idPers   = isset($data['idPers']) ? $data['idPers'] : NULL;
        $this->postPic  = isset($data['postPic']) ? $data['postPic'] : NULL;
        $this->data     = isset($data['data']) ? $data['data'] : NULL;
    }
}