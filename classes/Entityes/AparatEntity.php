<?php

/**
 * Class AparatEntity
 */
class AparatEntity
{
    /**
     * @var
     */
    private $idAparat;
    /**
     * @var
     */
    private $seria;
    /**
     * @var
     */
    private $tip;
    /**
     * @var
     */
    private $idLocVechi;
    /**
     * @var
     */
    private $idLocatie;
    /**
     * @var
     */
    private $pozitieLocatie;
    /**
     * @var
     */
    private $idxInM;
    /**
     * @var
     */
    private $idxOutM;
    /**
     * @var
     */
    private $idxBetM;
    /**
     * @var
     */
    private $idxWinM;
    /**
     * @var
     */
    private $idxGamesM;
    /**
     * @var
     */
    private $idxInE;
    /**
     * @var
     */
    private $idxOutE;
    /**
     * @var
     */
    private $idxBetE;
    /**
     * @var
     */
    private $tipJocMetrologii;
    /**
     * @var
     */
    private $dtActivare;
    /**
     * @var
     */
    private $dtBlocare;
    /**
     * @var
     */
    private $dtInserare;
    /**
     * @var
     */
    private $status;

    /** @var  DataConnection */
    private $db;

    /** @var  SessionClass */
    private $appSettings;

    /**
     * @var array|ContorMecanicEntity
     */
    private $contori = [];

    /** @var array array|ContorMecanicEntity */
    protected $contoriTeren = [];

    /** @var array ErrorpkEntity */
    protected $erori = [];

    /** @var  AvertizareEntity */
    protected $avertizari;

    /** @var  VariabileEntity */
    protected $variabile;

    /** @var  StareAparatEntity */
    protected $stareaparate;

    /**
     * @return mixed
     */
    public function getDtActivare()
    {
        return $this->dtActivare;
    }


    /**
     * @param mixed $dtActivare
     */
    public function setDtActivare($dtActivare)
    {
        $this->dtActivare = $dtActivare;
    }

    /**
     * @return mixed
     */
    public function getDtBlocare()
    {
        return $this->dtBlocare;
    }

    /**
     * @param mixed $dtBlocare
     */
    public function setDtBlocare($dtBlocare)
    {
        $this->dtBlocare = $dtBlocare;
    }

    /**
     * @return mixed
     */
    public function getDtInserare()
    {
        return $this->dtInserare;
    }

    /**
     * @param mixed $dtInserare
     */
    public function setDtInserare($dtInserare)
    {
        $this->dtInserare = $dtInserare;
    }

    /**
     * @return mixed
     */
    public function getIdAparat()
    {
        return $this->idAparat;
    }

    /**
     * @param mixed $idAparat
     */
    public function setIdAparat($idAparat)
    {
        $this->idAparat = $idAparat;
    }

    /**
     * @return int
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
        $this->idLocatie = $idLocatie;
    }

    /**
     * @return mixed
     */
    public function getIdLocVechi()
    {
        return $this->idLocVechi;
    }

    /**
     * @param mixed $idLocVechi
     */
    public function setIdLocVechi($idLocVechi)
    {
        $this->idLocVechi = $idLocVechi;
    }

    /**
     * @return mixed
     */
    public function getIdxBetE()
    {
        return $this->idxBetE;
    }

    /**
     * @param mixed $idxBetE
     */
    public function setIdxBetE($idxBetE)
    {
        $this->idxBetE = $idxBetE;
    }

    /**
     * @return mixed
     */
    public function getIdxBetM()
    {
        return $this->idxBetM;
    }

    /**
     * @param mixed $idxBetM
     */
    public function setIdxBetM($idxBetM)
    {
        $this->idxBetM = $idxBetM;
    }

    /**
     * @return mixed
     */
    public function getIdxGamesM()
    {
        return $this->idxGamesM;
    }

    /**
     * @param mixed $idxGamesM
     */
    public function setIdxGamesM($idxGamesM)
    {
        $this->idxGamesM = $idxGamesM;
    }

    /**
     * @return mixed
     */
    public function getIdxInE()
    {
        return $this->idxInE;
    }

    /**
     * @param mixed $idxInE
     */
    public function setIdxInE($idxInE)
    {
        $this->idxInE = $idxInE;
    }

    /**
     * @return mixed
     */
    public function getIdxInM()
    {
        return $this->idxInM;
    }

    /**
     * @param mixed $idxInM
     */
    public function setIdxInM($idxInM)
    {
        $this->idxInM = $idxInM;
    }

    /**
     * @return mixed
     */
    public function getIdxOutE()
    {
        return $this->idxOutE;
    }

    /**
     * @param mixed $idxOutE
     */
    public function setIdxOutE($idxOutE)
    {
        $this->idxOutE = $idxOutE;
    }

    /**
     * @return mixed
     */
    public function getIdxOutM()
    {
        return $this->idxOutM;
    }

    /**
     * @param mixed $idxOutM
     */
    public function setIdxOutM($idxOutM)
    {
        $this->idxOutM = $idxOutM;
    }

    /**
     * @return mixed
     */
    public function getIdxWinM()
    {
        return $this->idxWinM;
    }

    /**
     * @param mixed $idxWinM
     */
    public function setIdxWinM($idxWinM)
    {
        $this->idxWinM = $idxWinM;
    }

    /**
     * @return mixed
     */
    public function getPozitieLocatie()
    {
        return $this->pozitieLocatie;
    }

    /**
     * @param mixed $pozitieLocatie
     */
    public function setPozitieLocatie($pozitieLocatie)
    {
        $this->pozitieLocatie = $pozitieLocatie;
    }

    /**
     * @return mixed
     */
    public function getSeria()
    {
        return $this->seria;
    }

    /**
     * @param mixed $seria
     */
    public function setSeria($seria)
    {
        $this->seria = $seria;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * @param mixed $tip
     */
    public function setTip($tip)
    {
        $this->tip = $tip;
    }

    /**
     * @return mixed
     */
    public function getTipJocMetrologii()
    {
        return $this->tipJocMetrologii;
    }

    /**
     * @param mixed $tipJocMetrologii
     */
    public function setTipJocMetrologii($tipJocMetrologii)
    {
        $this->tipJocMetrologii = $tipJocMetrologii;
    }

    /**
     * @return DataConnection
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param DataConnection $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return SessionClass
     */
    public function getAppSettings()
    {
        return $this->appSettings;
    }

    /**
     * @param SessionClass $appSettings
     */
    public function setAppSettings($appSettings)
    {
        $this->appSettings = $appSettings;
    }

    public function __construct(DataConnection $db,SessionClass $appSettings)
    {
        $this->setAppSettings($appSettings);
        $this->setDb($db);
        $this->avertizari = new AvertizareEntity($db,$appSettings);
        $this->variabile = new VariabileEntity($db,$appSettings);
        $this->stareaparate = new StareAparatEntity($db,$appSettings);
    }

    /**
     * @return array|ContorMecanicEntity
     */
    public function getContori()
    {
        return $this->contori;
    }

    /**
     * @param array|ContorMecanicEntity $contori
     */
    public function setContori($contori)
    {
        $this->contori = $contori;
    }

    /**
     * @return StareAparatEntity
     */
    public function getStareaparate()
    {
        return $this->stareaparate;
    }

    /**
     * @param StareAparatEntity $stareaparate
     */
    public function setStareaparate($stareaparate)
    {
        $this->stareaparate = $stareaparate;
    }


    /**
     * Se instantiaza obiectul din un array
     * @param $data
     */
    public function exchangeArray($data)
    {
        $this->idAparat = isset($data['idAparat']) ? $data['idAparat'] : NULL;
        $this->seria = isset($data['seria']) ? $data['seria'] : NULL;
        $this->tip = isset($data['tip']) ? $data['tip'] : NULL;
        $this->idLocVechi = isset($data['idLocVechi']) ? $data['idLocVechi'] : NULL;
        $this->idLocatie = isset($data['idLocatie']) ? $data['idLocatie'] : NULL;
        $this->pozitieLocatie = isset($data['pozitieLocatie']) ? $data['pozitieLocatie'] : NULL;
        $this->idxInM = isset($data['idxInM']) ? $data['idxInM'] : NULL;
        $this->idxOutM = isset($data['idxOutM']) ? $data['idxOutM'] : NULL;
        $this->idxBetM = isset($data['idxBetM']) ? $data['idxBetM'] : NULL;
        $this->idxWinM = isset($data['idxWinM']) ? $data['idxWinM'] : NULL;
        $this->idxGamesM = isset($data['idxGamesM']) ? $data['idxGamesM'] : NULL;
        $this->idxInE = isset($data['idxInE']) ? $data['idxInE'] : NULL;
        $this->idxOutE = isset($data['idxOutE']) ? $data['idxOutE'] : NULL;
        $this->idxBetE = isset($data['idxBetE']) ? $data['idxOutE'] : NULL;
        $this->tipJocMetrologii = isset($data['tipJocMetrologii']) ? $data['tipJocMetrologii'] : NULL;
        $this->dtActivare = isset($data['dtActivare']) ? $data['dtActivare'] : NULL;
        $this->dtBlocare = isset($data['dtBlocare']) ? $data['dtBlocare'] : NULL;
        $this->dtInserare = isset($data['dtInserare']) ? $data['dtInserare'] : NULL;
        $this->variabile->exchangeArray($data);
        $this->stareaparate->populate($data['idAparat']);
        $this->avertizari->arrayExchange($data);
    }

    /**
     * Returneaza o copie array a elementului
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }


    /**
     * @param $an
     * @param $luna
     * @return array|ContorMecanicEntity
     */
    public function getContoriZilnici($an, $luna)
    {
        $query = "SELECT *,extract(DAY FROM contormecanic{$an}{$luna}.dtServer) as zi FROM {$this->getDb()->getDatabase()}.contormecanic{$an}{$luna} WHERE idAparat={$this->idAparat} ORDER BY contormecanic{$an}{$luna}.idxInM";
        $stmt  = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contormecanic = new ContorMecanicEntity($this->getDb(),$this->getAppSettings());
                $contormecanic->exchangeArray($row);
                $this->contori[$row['zi']] = clone $contormecanic;
                unset($contormecanic);
            }
        }

        return $this->contori;
    }

    /**
     * @param $an
     * @param $luna
     * @param $zi
     * @return int|string
     */
    public function getIndexPeZi($an, $luna, $zi)
    {
        $data = strtotime($an . '-' . $luna . '-' . $zi);
        /** @var ContorMecanicEntity $contor */
        foreach ($this->contori as $contor) {
            if (strtotime($contor->getDtPic()) == $data) {
                return $this->decorateCastig($contor->getCastig());
            }
        }

        return 0;
    }

    /**
     * @param $an
     * @param $luna
     * @param $zi
     * @return int|mixed
     */
    public function getIndexNeprelucratPeZi($an, $luna, $zi)
    {
        $data = strtotime($an . '-' . $luna . '-' . $zi);
        /** @var ContorMecanicEntity $contor */
        foreach ($this->contori as $contor) {
            if (strtotime($contor->getDtPic()) == $data) {
                return $contor->getCastig();
            }
        }

        return 0;
    }

    /**
     * @param $castig
     * @return string
     */
    public function decorateCastig($castig)
    {
        $milioane = round($castig / 100, 2);

        $parteIntreaga = round($milioane);
        $parteZecimala = round(($milioane - $parteIntreaga) * 100);
        if ($parteZecimala < 0) {
            $parteZecimala *= -1;
        }
        return "<span class='milion'>{$parteIntreaga}</span>,<span class='zecimala'>{$parteZecimala}</span>";
    }

    /**
     * @return string
     */
    public function getTotalLunarAparat()
    {
        $totalAparat = 0;

        /** @var ContorMecanicEntity $contor */
        foreach ($this->contori as $contor) {
            $totalAparat += $contor->getCastig();
        }

        return $this->decorateCastig($totalAparat);
    }

    /**
     * @return ContorMecanicEntity|mixed
     */
    public function getInceputLuna()
    {
        if (count($this->contori) == 0) {
            return $this->getNewContor();
        } else {
            return reset($this->contori);
        }
    }

    /**
     * @return ContorMecanicEntity
     */
    public function getNewContor()
    {
        $contor = new ContorMecanicEntity($this->getDb(),$this->getAppSettings());
        $contor->exchangeArray([]);
        $contor->setIdLocatie($this->getIdLocatie());
        $contor->setIdAparat($this->getIdAparat());
        return $contor;
    }

    /**
     * @return ContorMecanicEntity|mixed
     */
    public function getSfarsitLuna()
    {
        if (count($this->contori) == 0) {
            return $this->getNewContor();
        } else {
            return end($this->contori);
        }
    }

    /**
     * @return VariabileEntity
     */
    public function getVariabile()
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.variabile WHERE idAparat = {$this->getIdAparat()}";

        $variabile = new VariabileEntity();

        $result = $this->getDb()->query($query);

        $info = $result->fetch(PDO::FETCH_ASSOC);
        $variabile->exchangeArray($info);


        return $variabile;
    }

    /**
     * @return array|ContorMecanicEntity
     */
    public function getContoriTeren()
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.contormecanic WHERE idAparat={$this->getIdAparat()} ORDER BY dtServer ASC";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contori = new ContorMecanicEntity($this->getDb(), $this->getAppSettings());
                $contori->exchangeArray($row);
                $this->contoriTeren[] = clone $contori;
                unset($contori);
            }
            $nrContori = count($this->contoriTeren);
            $ultimulContor = end($this->contoriTeren);
            if ($ultimulContor != FALSE) {
                $dataUltimulContor = new DateTime($ultimulContor->getDtServer());
            } else {
                $dataUltimulContor = new DateTime($this->dtActivare);
            }
            $plusOLuna = new DateInterval("P1M");
            $luniActivitate = $this->getNrLuniActivitate();
            while ($nrContori <= ($luniActivitate + 2)) {
                $contor = new ContorMecanicEntity($this->getDb(), $this->getAppSettings());
                $contor->exchangeArray([]);
                $contor->setIdAparat($this->getIdAparat());
                $contor->setIdLocatie($this->getIdLocatie());
                $contor->setIdxInM('000000');
                $contor->setIdxOutM('000000');
                $dataUltimulContor->add($plusOLuna);
                $contor->setDtServer($dataUltimulContor->format("Y-m-d H:i:s"));
                $contor->setDtPic($dataUltimulContor->format("Y-m-d"));
                $contor->insert(NULL, NULL);
                $nrContori++;
                unset($contor);
            }
        }
        return $this->contoriTeren;
    }
//    TODO cronjob pentru mutarea indexilor
    /**
     * @param $luna
     * @return ContorMecanicEntity
     */
    public function getContoriInceputLunaTeren($luna)
    {
        if (!isset($this->contoriTeren[$luna - 1])) {
            $contor = new ContorMecanicEntity($this->getDb(),$this->getAppSettings());
            $contor->exchangeArray([]);
            return $contor;
        } else {
            return $this->contoriTeren[$luna - 1];
        }
    }

    public function getContorByZi($zi){
        if(isset($this->contori[$zi])){
            return $this->contori[$zi];
        }
        $contorNou = new ContorMecanicEntity($this->getDb(),$this->getAppSettings());
        $contorNou->exchangeArray([]);
        return $contorNou;
    }

    /**
     * @param $luna
     * @return ContorMecanicEntity
     */
    public function getContoriSfarsitLunaTeren($luna)
    {
        if (!isset($this->contoriTeren[$luna])) {
            $contor = new ContorMecanicEntity($this->getDb(),$this->getAppSettings());
            $contor->exchangeArray([]);
            return $contor;
        } else {
            return $this->contoriTeren[$luna];
        }
    }

    /**
     * @param $serie
     */



    /**
     *
     */
    public function getErori()
    {
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.errorpk WHERE errorpk.serieAparat = '{$this->getSeria()}' ORDER BY errorpk.dataServer DESC";

        $stmt = $this->getDb()->prepare($query);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eroare = new ErrorPkEntity($row);
                $this->erori[] = clone $eroare;
                unset($eroare);
            }
        }
    }

    /**
     * @return int
     */
    public function getNrEroriAparat()
    {
        return count($this->erori);
    }

    /**
     * @return ErrorPkEntity
     */
    public function getPrimaEroare()
    {
        return end($this->erori);
    }

    /**
     * @return ErrorPkEntity
     */
    public function getUltimaEroare()
    {
        return reset($this->erori);
    }

    private function getNrLuniActivitate()
    {
        $dtActivare = new DateTime($this->dtActivare);
        $dtBlocare = new DateTime($this->dtBlocare);
        if ($this->dtBlocare == '1000-01-01') {
            $dataAzi = new DateTime(date('Y-m-d'));
            $diferenta = $dataAzi->diff($dtActivare);
        } else {
            $diferenta = $dtBlocare->diff($dtActivare);
        }

        return $diferenta->m;
    }


    public function setDataContoriTeren(DataConnection $db)
    {
        $data = new DateTime($this->dtActivare);
        /** @var ContorMecanicEntity $contor */
        foreach ($this->contoriTeren as $contor) {
            $luna = $data->format("n");
            $an = $data->format("Y");
            echo $luna . "<br/>";
            echo $an . "<br/>";
            $contor->setDtServer("{$an}-{$luna}-02 08:00:00");
            $contor->save($db);
            $interval = new DateInterval("P1M");
            $data->add($interval);
        }
    }

    public function getIndexByDate($an, $luna)
    {
        $maxZile = cal_days_in_month(CAL_GREGORIAN, $luna, $an);
        $inceput = new DateTime("{$an}-{$luna}-01");
        $sfarsit = new DateTime("{$an}-{$luna}-{$maxZile}");

        /** @var ContorMecanicEntity $contor */
        foreach ($this->contoriTeren as $contor) {
            $dtServer = new DateTime($contor->getDtServer());
            if ($dtServer >= $inceput AND $dtServer <= $sfarsit) {
                return $contor;
            }
            unset($dtServer);
        }

        return new ContorMecanicEntity($this->getDb(),$this->getAppSettings());
    }


    public function getAvertizari(){
        $query = "SELECT * FROM {$this->getDb()->getDatabase()}.avertizari WHERE idAparat = :idAparat";

        $stmt = $this->getDb()->prepare($query);
        $stmt->bindParam(":idAparat",$this->idAparat,PDO::PARAM_INT);

        $this->avertizari = new AvertizareEntity($this->getDb(),$this->getAppSettings());

        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->avertizari->arrayExchange($row);
        }
    }

    public function returnAvertizari(){
        return $this->avertizari;
    }

    public function expiraLunaAsta(){
        $maxZileInLuna = $this->getAppSettings()->getDaysInCurrentMonth();
        $inceputulLunii = new  DateTime("{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-01");
        $sfarsitulLunii = new DateTime("{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-{$maxZileInLuna}");
        $dataExpirare = new DateTime($this->avertizari->getDtExpMetrologie());
        if(($dataExpirare >= $inceputulLunii) AND ($dataExpirare <= $sfarsitulLunii))
            return TRUE;
        return FALSE;
    }


    public function isActiv(){
        $maxZileInLuna = $this->getAppSettings()->getDaysInCurrentMonth();
        $inceputulLunii = new  DateTime("{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-01");
        $sfarsitulLunii = new DateTime("{$this->getAppSettings()->getAn()}-{$this->getAppSettings()->getLuna()}-{$maxZileInLuna}");
        $dataActivare = new DateTime($this->getDtActivare());
        if($this->getDtBlocare() == "1000-01-01"){
            if($dataActivare >= $inceputulLunii AND $dataActivare <= $sfarsitulLunii)
                return TRUE;
        }else{
            $dataBlocare = new DateTime($this->getDtBlocare());
            if($dataBlocare > $sfarsitulLunii OR ($dataActivare >= $inceputulLunii AND $dataActivare <= $sfarsitulLunii))
                return TRUE;
        }

        return FALSE;
    }
}