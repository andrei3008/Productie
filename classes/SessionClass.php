<?php

/**
 * Class SessionClass
 */
class SessionClass{
    /**
     * @var
     */
    protected $username;
    /**
     * @var
     */
    protected $operator;
    /**
     * @var
     */
    protected $idLocatie;
    /**
     * @var
     */
    protected $grad;
    /**
     * @var
     */
    protected $userId;
    /**
     * @var
     */
    protected $flag;
    /**
     * @var
     */
    protected $idresp;
    /**
     * @var
     */
    protected $an;
    /**
     * @var
     */
    protected $luna;
    /**
     * @var
     */
    protected $zi;
    /**
     * @var
     */
    protected $order;
    /**
     * @var
     */
    protected $direction;

    /** @var  string $zona */
    protected $zona;

    /**
     * SessionClass constructor.
     */
    public function __construct()
    {
        session_start();
        $this->exchangeArray($_SESSION);
    }

    /**
     * @return mixed
     */
    public function getAn()
    {
        return $this->an;
    }

    /**
     * @param mixed $an
     */
    public function setAn($an)
    {
        $_SESSION['an'] = $an;
        $this->an = $an;
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag)
    {
        $_SESSION['flag'] = $flag;
        $this->flag = $flag;
    }

    /**
     * @return mixed
     */
    public function getGrad()
    {
        return $this->grad;
    }

    /**
     * @param mixed $grad
     */
    public function setGrad($grad)
    {
        $_SESSION['grad'] = $grad;
        $this->grad = $grad;
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
        $_SESSION['idLocatie'] = $idLocatie;
        $this->idLocatie = $idLocatie;
    }

    /**
     * @return mixed
     */
    public function getIdresp()
    {
        return $this->idresp;
    }

    /**
     * @param mixed $idresp
     */
    public function setIdresp($idresp)
    {
        $_SESSION['idresp'] = $idresp;
        $this->idresp = $idresp;
    }

    /**
     * @return mixed
     */
    public function getLuna()
    {
        return $this->luna;
    }

    /**
     * @param mixed $luna
     */
    public function setLuna($luna)
    {
        $_SESSION['luna'] = $luna;
        $this->luna = $luna;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     */
    public function setOperator($operator)
    {
        $_SESSION['operator'] = $operator;
        $this->operator = $operator;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $_SESSION['userId'] = $userId;
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $_SESSION['username'] = $username;
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getZi()
    {
        return $this->zi;
    }

    /**
     * @param mixed $zi
     */
    public function setZi($zi)
    {
        $_SESSION['zi'] = $zi;
        $this->zi = $zi;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
        $_SESSION['order'] = $order;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
        $_SESSION['direction'] = $direction;
    }

    /**
     * @return string
     */
    public function getZona()
    {
        return $this->zona;
    }

    /**
     * @param string $zona
     */
    public function setZona($zona)
    {
        $_SESSION['zona'] = $zona;
        $this->zona = $zona;
    }



    /**
     * @param $data
     */
    public function exchangeArray($data){
        $this->an   = isset($data['an']) ? $data['an'] : (int)date('Y');
        $this->operator = isset($data['operator']) ? $data['operator'] : 1;
        $this->idLocatie = isset($data['idLocatie']) ? $data['idLocatie'] : 1;
        $this->idresp = isset($data['idresp']) ? $data['idresp'] : 1;
        $this->username = isset($data['username']) ? $data['username'] : 'adi';
        $this->zi = isset($data['zi']) ? $data['zi'] : (int)date('d');
        $this->luna = isset($data['luna']) ? $data['luna'] : (int)date('n');
        $this->grad = isset($data['grad']) ? $data['grad'] : 0;
        $this->userId = isset($data['userId']) ? $data['userId'] : 1;
        $this->flag = isset($data['flag']) ? $data['flag'] : null;
        $this->direction = isset($data['direction']) ? $data['direction'] : 'DESC';
        $this->order = isset($data['order']) ? $data['order'] : 'bani';
        $this->zona = isset($data['zona']) ? $data['zona'] : '';
    }

    /**
     * @return array
     */
    public function getArrayCopy(){
        return get_object_vars($this);
    }

    /**
     *
     */
    public function checkLogin(){
        if (!isset($_SESSION['username']) AND !isset($_SESSION['operator'])) {
            header('location:'.DOMAIN.'/index.php');
        }
    }


    public function getDaysInCurrentMonth(){
        return cal_days_in_month(CAL_GREGORIAN,$this->luna,$this->an);
    }

    public function getShortDay($nrZi){
        switch($nrZi){
            case 1 : return "L";break;
            case 2 : return "Ma";break;
            case 3 : return "Mi";break;
            case 4 : return "J";break;
            case 5 : return "V";break;
            case 6 : return "S";break;
            case 7 : return "D";break;
        }
        return 0;
    }

    public function getCurentDate(){
        return "{$this->getAn()}-{$this->getLuna()}-{$this->getZi()}";
    }

    public function getInceputulLunii(){
        return $this->getAn().'-'.$this->getLuna().'-01';
    }

    public function getSfarsitulLunii(){
        return $this->getAn().'-'.$this->getLuna().'-'.$this->getDaysInCurrentMonth();
    }

    public function getLunaInRomana($nrLuna){
        switch($nrLuna){
            case 1 : return "Ianuarie"; break;
            case 2 : return "Februarie"; break;
            case 3 : return "Martie"; break;
            case 4 : return "Aprilie";break;
            case 5 : return "Mai"; break;
            case 6 : return "Iunie";break;
            case 7 : return "Iulie"; break;
            case 8 : return "August";break;
            case 9 : return "Septembrie";break;
            case 10 : return "Octombrie"; break;
            case 11 : return "Noiembrie"; break;
            case 12 : return "Decembrie"; break;
            default : return "Luna nu exista!"; break;
        }
    }

    public function getDataDecorata($zi = NULL, $luna = NULL, $an = NULL){
        $zi = ($zi != NULL) ? $zi : $this->getZi();
        $luna = ($luna != NULL) ? $luna : $this->getLuna();
        $an = ($an != NULL) ? $an : $this->getAn();
        return "{$zi} - {$this->getLunaInRomana($luna)} - {$an}";
    }

    public function inceputLuna()
    {
        return "{$this->getAn()}-{$this->getLuna()}-01";
    }

    public function sfarsitLuna()
    {
        return "{$this->getAn()}-{$this->getLuna()}-{$this->getDaysInCurrentMonth()}";
    }

    /**
     * @return string
     */
    public function getOperatorLetter(){
        if($this->getOperator() == ""){
            return "T";
        }elseif($this->getOperator() == 1){
            return "A";
        }
        return "R";
    }
}