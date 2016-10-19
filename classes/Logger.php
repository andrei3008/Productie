<?php

class Logger {

    private $fileName;
    private $message;
    private $logFile;

    public function __construct($filename) {
        $this->setFilename($filename);
        $this->connectToFile();
    }

    public function setFilename($filename) {
        $this->fileName = $filename;
    }

    private function connectToFile() {
        $this->logFile = fopen($this->fileName, 'a+');
    }

    private function getFileHandle() {
        return $this->logFile;
    }

    public function writeToFile($message, $action, $sesionId) {
        $this->message = date('Y-m-d H:i:s');
        $this->message.=' ' . $action . ' ' . $sesionId . ' ' . $message;
        $log = $this->getFileHandle();
        fwrite($log, $this->message . "\n");
    }

    public function getLogs($data = null, $tipLog = null) {
        $array = [];
        $log = $this->getFileHandle();
        while (($line = fgets($log)) !== false) {
            if ($tipLog == null AND $data == null) {
                $array[] = $line;
            } elseif ($tipLog != null AND $data == null) {
                $elemente = explode(' ', $line);
                if ($elemente[2] == $tipLog) {
                    $array[] = $line;
                }
            } elseif ($data != null AND $tipLog == null) {
                $elemente = explode(' ', $line);
                if ($elemente[0] == $data) {
                    $array[] = $line;
                }
            } elseif ($data != null AND $tipLog != null) {
                $elemente = explode(' ', $line);
                if ($elemente[2] == $tipLog AND $data = $elemente[0]) {
                    $array[] = $line;
                }
            }
        }
        return array_reverse($array);
    }

    public function getAll() {
        $sessionInfo = $this->getLogins();
        $final = [];
        $i = 0;
        foreach ($sessionInfo as $session) {
            if (isset($session['LOGIN'])) {
                $partsVisit = explode(' ', $session['VISIT']);
                $partsLogin = explode(' ', $session['LOGIN']);

                $final[$i]['ip'] = $partsVisit[count($partsVisit) - 1];
                $final[$i]['ora-login'] = $partsLogin[1];
                if(isset($session['LOGOUT'])){
                    $partsLogout = explode(' ', $session['LOGOUT']);
                    $final[$i]['ora-logout'] = $partsLogout[1];
                    $durata = $this->toSeconds($partsLogout[1]) - $this->toSeconds($partsLogin[1]);
                    $final[$i]['durata-sesiune'] = $this->toTime($durata);
                }else{
                    $final[$i]['ora-logout'] = 0;
                    $durata = $this->toSeconds(date('H:i:s')) - $this->toSeconds($partsLogin[1]);
                    $final[$i]['durata-sesiune'] = $durata*1000;
                }

                $i++;
            }
        }
        return $final;
    }

    public function getLogins() {
        $sesionInfo = [];
        $log = $this->getFileHandle();
        while (($line = fgets($log)) !== FALSE) {
            $parts = explode(' ', $line);
            if (count($parts) > 4) {

                if ($parts[2] == "LOGIN") {
                    $ids[] = $parts[3];
                }
            }
        }

        if (isset($ids)) {
            foreach ($ids as $id) {
                $sesionInfo[] = $this->getRowsBySesion($id);
            }
        }
        return $sesionInfo;
    }

    public function getRowsBySesion($sesionId) {
        $log = $this->getFileHandle();
        rewind($log);
        $array = [];
        while (($line1 = fgets($log)) !== FALSE) {
            $parts = explode(' ', $line1);            
            if (count($parts)>4 AND $parts[3] == $sesionId) {
                $array[$parts[2]] = $line1;
            }
        }
        return $array;
    }

    public function getClassByType($type) {
        switch ($type) {
            case 'LOGIN' : return 'success';
                break;
            case 'ERROR' : return 'danger';
                break;
            case 'LOGOUT' : return 'warning';
                break;
            case 'VISIT' : return 'info';
                break;
            default:
                break;
        }
    }

    public function toSeconds($clock) {
        $parts = explode(':', $clock);
        $seconds = 3600 * (int) $parts[0] + 60 * (int) $parts[1] + (int) $parts[2];
        return $seconds;
    }

    public function toTime($init) {
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        $result = $hours . ':' . (($minutes < 10) ? "0" . $minutes : $minutes) . ':' . (($seconds < 10) ? '0' . $seconds : $seconds);
        return $result;
    }

}
