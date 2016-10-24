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
            try{ 
                $stmt = $this->datab->prepare($sql); 
                $stmt->execute($array);
                }catch(PDOException $e){
                throw new Exception($e->getMessage());
            } 
            return $stmt->rowCount();
        }
        public function sanitize($fields) {
            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    $fields[$key] = $this->sanitize($value);
                }
            } else {
                $magic_quotes = (bool) get_magic_quotes_gpc();
                if ($magic_quotes === true) {
                    $fields = stripslashes($fields);
                }
                if (strpos($fields, "\r") !== false) {
                    $fields = trim($fields);
                }
                $fields = strip_tags($fields);
                $fields = filter_var($fields, FILTER_SANITIZE_STRING);
            }   
            return $fields;
        }
        /**
         * [Inserare loguri in tabela LOGS]
         * @param  [string] $user         [nick user logat]
         * @param  [string] $tip_query    [INSERT/UPDATE/DELETE - <TABELA>]
         * @param  [string] $tabel_query  [tabelul unde se aplica query-ul]
         * @param  [string] $cols_query   [coloanele pentru query]
         * @param  [array]  $array_query  [array cu valorile din query]
         * @param  [string] $where_query  [where clause pentru query(delete/update)]
         * @return [int]                  [id-ul ultimei inserati - A.I.]
         */
        public function logsInsertRow($user, $tip_query, $tabel_query, $cols_query, $array_query, $where_query) {
            // CONSTRUIRE QUERY PENTRU COLOANA <statement>
                if ($tip_query == 'INSERT') {
                    $query_to_insert = "INSERT INTO {$tabel_query} ({$cols_query}) VALUES (".implode(',', $array_query).")";
                } elseif ($tip_query == 'UPDATE') {
                    $query_to_insert = "UPDATE {$tabel_query} SET ";
                    $array_cols = explode(',', $cols_query);
                    foreach ($array_cols as $key => $val) {
                        $query_to_insert .= $val.'='.$array_query[$key].(($key < (count($array_cols)-1)) ? ',' : '').' ';
                    }
                    $query_to_insert .= $where_query;
                } elseif ($tip_query == 'DELETE') {
                    $query_to_insert = "DELETE FROM {$tabel_query} {$where_query}";
                }
            // INSERT IN TABLEA [logs]
                $array_logs = array($user, $tip_query.'-'.$tabel_query, $query_to_insert, date('Y-m-d H:i:s'));
                // print_r($array_los);
                $last_log_id = $this->insertRow('logs', 'user, eveniment, statement, data', '?, ?, ?, ?', $array_logs);
                // echo $query_to_insert;
       
            return $last_log_id;         
        }
        /**
         * [Inserare loguri in tabela LOGS]
         * @param  [string] $user         [nick user logat]
         * @param  [string] $tip_query    [INSERT/UPDATE/DELETE - <TABELA>]
         * @param  [string] $tabel_query  [tabelul unde se aplica query-ul]
         * @param  [string] $sql_old      [varianta sql folosita de vechiul dbFull]
         * @return [int]                  [id-ul ultimei inserati - A.I.]
         */
        public function logsInsertRow_fromOld($user, $tip_query, $tabel_query, $sql_old) {
            $sql_old = str_replace("'", "", $sql_old);
            $sql_old = str_replace('"', "", $sql_old);
            $array_logs = array($user, $tip_query.'-'.$tabel_query, $sql_old, date('Y-m-d H:i:s'));
            $last_log_id = $this->insertRow('logs', 'user, eveniment, statement, data', '?, ?, ?, ?', $array_logs);
            return $last_log_id;         
        }
    }
?>