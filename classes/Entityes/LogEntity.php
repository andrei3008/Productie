<?php

class LogEntity{
    private $idlog;
    private $user;
    private $eveniment;
    private $statement;
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
    public function getEveniment()
    {
        return $this->eveniment;
    }

    /**
     * @param mixed $eveniment
     */
    public function setEveniment($eveniment)
    {
        $this->eveniment = $eveniment;
    }

    /**
     * @return mixed
     */
    public function getIdlog()
    {
        return $this->idlog;
    }

    /**
     * @param mixed $idlog
     */
    public function setIdlog($idlog)
    {
        $this->idlog = $idlog;
    }

    /**
     * @return mixed
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @param mixed $statement
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getArrayCopy(){
        return get_object_vars($this);
    }

    public function exchangeArray($data){
        $this->idlog        = isset($data['idlog']) ? $data['idlog'] : NULL;
        $this->user         = isset($data['user']) ? $data['user'] : NULL;
        $this->eveniment    = isset($data['eveniment']) ? $data['eveniment'] : NULL;
        $this->statement    = isset($data['statement']) ? $data['statement'] : NULL;
        $this->data         = isset($data['data']) ? $data['data'] : NULL;
    }
}