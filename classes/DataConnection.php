<?php

class DataConnection extends PDO{
    private $host = 'localhost';
    private $user = 'shorek';
    private $password = 'BudsSql7';
    private $database = 'brunersrl';


    public function __construct()
    {
        try {
            parent::__construct("mysql:{$this->host};port:3307;dbname={$this->database}", $this->user, $this->password);
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    public function sanitizePost(array $data){
       return $data;
    }
}