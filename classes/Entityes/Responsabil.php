<?php

class Responsabil extends PersonalEntity
{
    protected $idresp;
    protected $nrEroriAmpera;
    protected $nrEroriRedlong;
    protected $nrLocatiiAmpera;
    protected $nrLocatiiRedlong;
    protected $nrAparateAmpera;
    protected $nrAparateRedlong;
    protected $locatiiAmpera;
    protected $locatiiRedlong;
    protected $db;

    public function __construct($idResponsabil, DataConnection $db)
    {
        parent::__construct($idResponsabil, $db);
        $this->idresp = $idResponsabil;
        $this->db = $db;
        $this->preiaNumarErori();
        $this->setResponsabilLocatii();
    }

    /**
     * @param $idResp
     * @param $idOp
     * @param DataConnection $db
     * @return int
     */
    public function countErori($idResp, $idOp, DataConnection $db)
    {
        $query = "SELECT count(idpachet) AS errori FROM {$db->getDatabase()}.errorpk
                  INNER JOIN {$db->getDatabase()}.aparate ON aparate.idAparat = errorpk.idAparat
                  INNER JOIN {$db->getDatabase()}.locatii ON locatii.idlocatie = aparate.idlocatie WHERE idresp={$idResp} AND locatii.idOperator={$idOp}";

        $queryResult = $db->query($query);
        $data = $queryResult->fetch_object();
        return (int)$data->errori;
    }

    /**
     * Seteaza numarul de erori per operator
     */
    public function preiaNumarErori()
    {
        $this->nrEroriAmpera = $this->countErori($this->idresp, 1, $this->db);
        $this->nrEroriRedlong = $this->countErori($this->idresp, 2, $this->db);
    }

    /**
     * @return int
     */
    public function totalErori()
    {
        return ((int)$this->nrEroriAmpera + (int)$this->nrEroriRedlong);
    }

    /**
     * Initializeaza lista cu locatiile responsabilului
     */
    public function setResponsabilLocatii(){
        $query = "SELECT * FROM {$this->db->getDatabase()}.locatii WHERE idresp={$this->idresp}";
        $result = $this->db->query($query);
        if($result){
            while($row = $result->fetch_assoc()){
                $locatie = new LocatieEntity();
                $locatie->exchangeArrat($row);
                if($locatie->getIdOperator() == 1){
                    $this->locatiiAmpera[] = clone $locatie;
                }else{
                    $this->locatiiRedlong[] = clone $locatie;
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getAllLocatii(){
        return array_merge($this->locatiiAmpera,$this->locatiiRedlong);
    }

    /**
     * @param  int $idOperator
     * @return mixed
     */
    public function getLocatiiByOperator($idOperator){
        if($idOperator == 1)
            return $this->locatiiAmpera;
        return $this->locatiiRedlong;
    }

    /**
     * @param int $idOperator
     * @return int
     */
    public function getNrLocatiiOperator($idOperator){
        if($idOperator == 1)
            return count($this->nrLocatiiAmpera);
        return count($this->nrLocatiiRedlong);
    }

    /**
     * @return int
     */
    public function getTotalLocatiiResponsabil(){
        return ($this->getLocatiiByOperator(1) + $this->getNrLocatiiOperator(2));
    }
}