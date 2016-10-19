<?php

class FileClass{
    protected $fileName;
    protected $connection;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->connectToFile('a+');
    }

    protected function connectToFile($mode){
        $this->conection = fopen($this->fileName,$mode);
    }

    protected function getConnection(){
        return $this->conection;
    }

    public function resetConnection(){
        rewind($this->getConnection());
    }

    public function __destruct()
    {
        fclose($this->getConnection());
    }

    public function getText(){
        $lines = [];
        $this->resetConnection();
        while(!feof($this->getConnection())){
            $lines[] = fgets($this->getConnection());
        }
        return $lines;
    }

    public function deleteFileContent(){
        $fp = fopen($this->fileName, "w");
        fclose($fp);
    }

    public function deleteLine($nrLinie){
        $text = $this->getText();
        $this->deleteFileContent();
        foreach($text as $linie){
            $data = explode(';',$linie);
            if($data[0]!=$nrLinie){
                $this->writeToFile($linie);
            }
        }
    }

    public function getNumberOfRows(){
        $this->resetConnection();
        $text = $this->getText();
        $this->resetConnection();
        return  count($text);
    }

    public function getNumberOfLastRow()
    {
        $text = $this->getText();
        $i=1;
        foreach($text as $linie){
            if($i==count($text)-1){
                $data = explode(';',$linie);
                return $data[0];
            }
            $i++;
        }
        return 1;
    }

    public function writeToFile($string){
        fwrite($this->getConnection(),$string);
    }
}