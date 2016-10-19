<?php
class ContorMecanicMapper{
    protected $an;
    protected $luna;
    protected $db;
    protected $indexi = [];

    /**
     * ContorMecanicMapper constructor.
     * @param $an
     * @param $luna
     * @param DataConnection $db
     */
    public function __construct($an, $luna, DataConnection $db)
    {
        $this->setLuna($luna);
        $this->setAn($an);
        $this->setDb($db);
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
        $this->an = $an;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb(DataConnection $db)
    {
        $this->db =  $db;
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
        $this->luna = $luna;
    }

    /**
     * @param int $idAparat
     * @return array ContorEntity
     */
    public function getIndexiLuna($idAparat = 0){
        $db = $this->getDb();
        $query = "SELECT * FROM {$db->getDatabase()}.contormecanic{$this->an}{$this->luna}";
        if($idAparat != 0){
            $query.= " WHERE idAparat = {$idAparat}";
        }

        $result = $db->query($query);

        if($db->affected_rows > 0){
            while($row = $result->fetch_assoc()){
                $contor = new ContorMecanicEntity();
                $contor->exchangeArray($row);
                $this->indexi[] = clone $contor;
                unset($contor);
            }
        }
        return $this->indexi;
    }
}