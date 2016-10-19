<?php

class PicClass
{
    private $ip;
    private $port;
    private $url;
    private $idLocatie;
    private $idOperator;
    private $idAparat;
    private $handle;
    private $postVars='';
    private $fields=[];

    public function __construct($ip, $port,$fields)
    {
        $this->setIp($ip);
        $this->setPort($port);
        $this->setUrl($ip, $port);
        $this->setFields($fields);
        $this->setPostVars($this->getFields());
        $this->setHandle($this->getUrl());
    }

    public function connect()
    {
        $ch = $this->getHandle();
        $url = $this->getUrl();
        $fields = $this->getFields();

        $postVars = $this->getPostVars();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postVars);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,20);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($ch);
        echo $url;
        if($result) return TRUE;
        return FALSE;
    }
    public function __destruct()
    {
        $ch = $this->getHandle();
        curl_close($ch);
    }

    /**
     * @return array
     */
    public function getPostVars()
    {
        return $this->postVars;
    }

    /**
     * @param $fields
     */
    public function setPostVars($fields)
    {
        $sep='';
        foreach($fields as $key=>$value)
        {
            $this->postVars.= $sep.urlencode($key).'='.urlencode($value);
            $sep='&';
        }
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param $url
     */
    public function setHandle($url)
    {
        $this->handle = curl_init($url);
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
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

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($ip, $port)
    {
        $this->url = 'http://admin:ampera@' . $ip . ':' . $port.'/tech/game.htm';
    }

    /**
     * @return mixed
     */
    public function getIdLocatie()
    {
        return $this->idLocatie;
    }

    /**
     * @param mixed $idLocatie
     */
    public function setIdLocatie($idLocatie)
    {
        $this->idLocatie = $idLocatie;
    }

    /**
     * @return mixed
     */
    public function getIdOperator()
    {
        return $this->idOperator;
    }

    /**
     * @param mixed $idOperator
     */
    public function setIdOperator($idOperator)
    {
        $this->idOperator = $idOperator;
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

}
