<?php

class FabricaDeErori{
    private $table = "errorpk";
    /** @var  DataConnection $dbConnection */
    private $dbConnection;
    private $errors = [];

    public function __construct(DataConnection $dbFull,SessionClass $appSettings){
        $this->setDbConnection($dbFull);
    }


    public function getEroriResponsabil($idResponsabil){
        $dataConnection = $this->getDbConnection();
        /** @var dbFull $databaseName */
        $databaseName = $dataConnection->getDatabase();
        $query = "SELECT DISTINCT errorpk.serieAparat FROM {$databaseName}.errorpk INNER JOIN {$databaseName}.locatii
                ON locatii.idlocatie = errorpk.idlocatie WHERE locatii.idresp={$idResponsabil};";
        try{
            $result = $dataConnection->query($query);
            $this->hydrate($result);
        }catch (Error $error){
            echo "Ne pare rau. A intervenit o eroare. ".$error->getMessage();
        }

        return $this->errors;
    }


    public function getEroriCondensate(){
        
    }

    private function hydrate(\PDOStatement $result){
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $this->errors[] = $row;
        }
    }

    /**
     * @return mixed
     */
    public function getDbConnection()
    {
        return $this->dbConnection;
    }

    /**
     * @param mixed $dbConnection
     */
    public function setDbConnection($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }


}