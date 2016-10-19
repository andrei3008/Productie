<?php 
class Logger {
    private $fileName;
    private $message;
    
    public function __construct($filename) {
        $this->setFilename($filename);
    }
    
    public function setFilename($filename) {
        $this->fileName = $filename;
    }
    
    public function writeToFile($message, $action, $sesionId){
        $this->message = date('Y-m-d H:i:s');
        $this->message.=' '.$action.' '.$sesionId. ' '.$message; 
        $logFile = fopen($this->fileName,'a+');
        fwrite($logFile, $this->message."\n");
    }
    
}