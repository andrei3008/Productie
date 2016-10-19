<?php 
    
    class datab
    {
        private $isConnected;
        public $datab;
        public function __construct($url, $username, $password, $host, $dbname, $options=array()) {
            $this->isConnected = true;
            $this->url = $url;
            try { 
                $this->datab = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); 
                $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } 
            catch(PDOException $e) { 
                $this->isConnected = false;
                throw new Exception($e->getMessage());
            }
        }
        public function Disconnect(){
            $this->datab = null;
            $this->isConnected = false;
            $this->url = null;
        }

        public function getRows($tabel, $ce, $where='', $array=array()) {
            $query = "SELECT $ce FROM $tabel $where";
            try{ 
                $stmt = $this->datab->prepare($query); 
                $stmt->execute($array);
                return $stmt->fetchAll();  
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }

        }
        public function insertRow($tabel, $cols, $params, $array) {
            $query = "INSERT INTO $tabel (".$cols.") VALUES (".$params.")";
            try{ 
                $stmt = $this->datab->prepare($query); 
                $stmt->execute($array);
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            }  
            // return $stmt->rowCount();         
            return $this->datab->lastInsertId();         
        }
        
        public function updateRow($tabel, $cols, $where, $array){
            $sql = "UPDATE ".$tabel." SET $cols $where";
            try{ 
                $stmt = $this->datab->prepare($sql); 
                $stmt->execute($array);
            }
            catch(PDOException $e){
                throw new Exception($e->getMessage());
            }  

            return $stmt->rowCount();
        }
        public function deleteRow($tabel, $where, $array){
            $sql = "DELETE FROM ".$tabel." ".$where;
            echo $sql;
            try{ 
                $stmt = $this->datab->prepare($sql); 
                $stmt->execute($array);
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            } 
            return $stmt->rowCount();
        }
    }
?>