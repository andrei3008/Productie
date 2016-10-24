<?php
/**
 * Class dbFull
 */
class databFull extends datab {

    public function __construct($url, $username, $password, $host, $dbname, $options=array()) {
        parent::__construct($url, $username, $password, $host, $dbname, $options=array());
    }

/********************************************************************************************************************
 *              HOMEPAGE                                                                                            *
 *                                                                                                                  *
 *              RESPONSABILI                                                                                        *
 *                                                                                                                  *
********************************************************************************************************************/
    /**
     * RAPOARTE - APARATE/RESPONSABILI
     * 
     * @param $operator
     * @param null $idResponsabil
     * @return object|stdClass
     */
    public function getNrLocatiiResponsabili($operator, $idResponsabil = null) {
        $where = (isset($idResponsabil)) ? " WHERE locatii.idresp=" . $idResponsabil . " AND " : ' WHERE ' ;
        $queryNrLocatii = ' SELECT count(idlocatie) AS nrLocatii FROM locatii '.$where.' idOperator=?';
        $stmt = $this->datab->prepare($queryNrLocatii);
        $stmt->execute(array($operator));
        $obj = $stmt->fetchObject();
        return $obj;
    }

    /**
     * RAPOARTE - APARATE/RESPONSABILI
     * 
     * @param $idResponsabil
     * @param $operator
     * @return object|stdClass
     */
    public function getNumarAparateResponsabil($idResponsabil, $operator)
    {
        if (!isset($idResponsabil)) {
            $queryNrAparate = "SELECT count(a.idAparat) AS nrAparate FROM aparate a INNER JOIN locatii l on l.idlocatie = a.idLocatie WHERE l.idOperator=?";
            $array = array($operator);
        } else {
            $queryNrAparate = "
                SELECT count(a.idAparat) AS nrAparate
                FROM aparate a 
                    INNER JOIN locatii l ON l.idlocatie = a.idLocatie  
                WHERE l.idresp = ? AND l.idOperator=?";
                $array = array($idResponsabil, $operator);
        }
        $stmt = $this->datab->prepare($queryNrAparate);
        $stmt->execute($array);
        $obj = $stmt->fetchObject();
        return $obj;
    }

    /**
     * RAPOARTE - APARATE/RESPONSABILI
     * 
     * @param $idResponsabil
     * @param $operator
     * @return object|stdClass
     */
    public function getAllAparateResponsabil($idResponsabil, $operator)
    {
        $aparate = [];
        $array = array('1000-01-01');
        if (isset($idResponsabil)) {
            $where  = "l.idresp = ? AND l.idOperator = ? AND ";
            $array = array($idResponsabil, $operator, '1000-01-01');
        }
        $queryAparateResponsabil = "SELECT
                ap.seria,
                ap.idLocatie,
                ap.tip,
                av.dtExpAutorizatie,
                av.dtExpMetrologie
                FROM aparate ap
                    INNER JOIN avertizari av ON av.idAparat = ap.idAparat
                    INNER JOIN locatii l  ON ap.idLocatie = l.idlocatie
                WHERE " . $where ." dtBlocare = ? ";
        $stmt = $this->datab->prepare($queryAparateResponsabil);
        $stmt->execute($array);
        while ($obj = $stmt->fetchObject()) {
            $aparate[] = $obj;
        }
        return $aparate;
    }

    /**
     * RAPOARTE - APARATE/RESPONSABILI
     * 
     * @param $idResponsabil
     * @param $operator
     * @return object|stdClass
     */
    public function allLocatiiResponsabil($idResponsabil, $operator)
    {
        $locatii = [];
        $where = (isset($idResponsabil)) ? "  l.idresp = ? AND " : '';
        $array = (isset($idResponsabil)) ? array($idResponsabil, $operator) : array($operator);
        $query = "SELECT
            l.denumire,
            l.idlocatie,
            l.adresa,
            f.denumire as denumireFirma
            FROM locatii l
            INNER JOIN firme f
            ON l.idfirma = f.idfirma WHERE " . $where ." l.idOperator=?";
        $stmt = $this->datab->prepare($query);
        $stmt->execute($array);
        while ($obj = $stmt->fetchObject()) {
            $locatii[] = $obj;
        }
        return $locatii;
    }


    /**
     *  RAPOARTE - DEPOZIT
     *  
     * @return array
     */
    public function getAparateDepozitByResponsabili()
    {
        $aparate = [];
        $query = "SELECT
                    p.nick,
                    ap.seria,
                    ap.idAparat,
                    s.lastIdxInM,
                    s.lastIdxOutM,
                    s.dtLastM,
                    ap.dtActivare,
                    l1.denumire as denumireLocActual,
                    l1.idOperator as operator,
                    l2.denumire as denumireLocVechi,
                    l2.idlocatie

                 FROM (((aparate ap INNER JOIN locatii l1 ON ap.idLocatie = l1.idlocatie)
                INNER JOIN stareaparate s ON s.idAparat =  ap.idAparat)
                inner join locatii l2 on ap.idLocVechi = l2.idlocatie)
                INNER JOIN personal p ON p.idPers = l2.idresp where l1.denumire LIKE ? AND ap.dtBlocare=? ORDER by p.nick, l2.denumire";
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array('%depozit%', '1000-01-01'));
        while ($obj = $stmt->fetchObject()) {
            $aparate[] = $obj;
        }
        return $aparate;
    }
    /**
     * MAIN - RESPONSABILI - CONTORIZARE LOCATII & APARATE
     *
     * @param $inceputluna
     * @param $sfarsitLuna
     * @param $idResponsabil
     * @return array
     *
     */
    public function getResponsabiliLocatiiAparate($inceputluna, $sfarsitLuna, $idResponsabil = NULL) {
        $array = $result = array();
        if ($idResponsabil) {
            $where_aditional = 'P.idpers = '.$idResponsabil.' AND';
        } else {
            $where_aditional = 'P.idpers != 0 AND';
        }
        $stmt = $this->datab->query('SET sql_mode = ""');
        $query = '
                SELECT
                    P.idpers,
                    P.nick,
                    L.idOperator,
                    SUM(IF(L.idOperator = 1,1,0)) as locatiiAmpera,
                    SUM(IF(L.idOperator = 2,1,0)) as locatiiRedlong,
                    COUNT(L.idlocatie) as totalLocatii,
                    SUM(IF(L.idOperator = 1,nrAparate.nrAparateAmpera,0)) as aparateAmpera,
                    SUM(IF(L.idOperator = 2,nrAparate.nrAparateRedlong,0)) as aparateRedlong,
                    SUM(IF(L.idOperator =1,nrAparate.nrAparateAmpera,nrAparate.nrAparateRedlong)) as totalAparate,
                    SUM(IF(L.idOperator = 1,nrAparate.nrAparateDepozitAmpera,0)) as depozitAmpera,
                    SUM(IF(L.idOperator = 2,nrAparate.nrAparateDepozitRedlong,0)) as depozitRedlong,
                    SUM(IF(L.idOperator =1,nrAparate.nrAparateDepozitAmpera,nrAparate.nrAparateDepozitRedlong)) as totalDepozitAparate
                FROM personal as P
                INNER JOIN locatii as L ON L.idresp = P.idpers AND L.idlocatie != 792
                LEFT JOIN (
                    SELECT
                        L2.idlocatie,
                        SUM(IF(L2.idOperator=1,1,0)) as nrAparateAmpera,
                        SUM(IF(L2.idOperator=2,1,0)) as nrAparateRedlong,
                        SUM(IF(L2.idOperator=1 AND L2.denumire LIKE "%depozit%" AND dtBlocare = "1000-01-01",1,0)) as nrAparateDepozitAmpera,
                        SUM(IF(L2.idOperator=2 AND L2.denumire LIKE "%depozit%" AND dtBlocare = "1000-01-01",1,0)) as nrAparateDepozitRedlong
                    FROM locatii as L2
                    INNER JOIN aparate as A ON A.idLocatie = L2.idlocatie
                    WHERE (A.dtBlocare = "1000-01-01")
                    GROUP BY L2.idlocatie
                ) as nrAparate on L.idlocatie = nrAparate.idlocatie
                WHERE '.$where_aditional.' (L.dtInchidere = "1000-01-01" OR (L.dtInchidere >= '.$inceputluna.' AND L.dtInchidere <= '.$sfarsitLuna.' )) 
                        AND (L.idOperator = 1 OR L.idOperator = 2)
                GROUP BY L.idresp
                ORDER BY P.idpers ASC;';
        $stmt = $this->datab->prepare($query);
        $stmt->execute($array);
        while ($obj = $stmt->fetchObject()) {
            $result[] = $obj;
        }
        return $result;
    }

    /**
     * MAIN - STANGA - LISTARE LOCATII RESPONSABIL SELECTAT 
     * CULOARE LOCATIE - ROSU/VERDE
     *
     * @param $idLocatie
     * @return string
     *
     */
    public function getCuloareLocatieResponsabil($idLocatie) {
        $now = new DateTime();
        $now->format("Y-m-d H:i:s");
        $query = "  SELECT 
                        S.idaparat, S.ultimaConectare 
                    FROM stareaparate S
                        INNER JOIN aparate A ON S.idAparat = A.idAparat
                    WHERE A.idLocatie = ?";
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array($idLocatie));
        while ($obj = $stmt->fetchObject()) {
            $last = new DateTime($obj->ultimaConectare);
            $last->format("Y-m-d H:i:s");
            $diferenta = $now->diff($last);
            $ore = $diferenta->format('%h');
            $zile = $diferenta->format('%a');
            if ($ore < 1 AND $zile < 1) {
                return 'verde';
            }
        }
        return 'rosu';
    }

    /**
     * MAIN - STANGA - LISTARE LOCATII RESPONSABIL SELECTAT 
     * Nr aparate locatie
     *
     * @param $idLocatie
     * @return string
     *
     */
    public function getNrAparateByDateResponsabil($idLocatie, $an, $luna) {
        $query = "  SELECT 
                        count(idAparat) AS nr_aparate 
                    FROM aparate 
                    WHERE
                        idLocatie = ? AND (dtBlocare=? OR dtBlocare >= ?) AND dtActivare <= ?";
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array($idLocatie, '1000-01-01', $an.'-'.$luna.'-01', $an.'-'.$luna.'-31'));
        $obj = $stmt->fetchObject();        
        return $obj->nr_aparate;
    }

    /**
     * @param $idLocatie
     * @return mixed
     */
    public function getNumeOperatorLocatie($idLocatie)
    {
        $query = "SELECT L.idOperator, O.denFirma FROM locatii L, operatori O where L.idlocatie = ? AND L.idOperator = O.idoperator";
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array($idLocatie));
        $rows = $stmt->fetchAll();
        return $rows[0]['denFirma'];
    }

/********************************************************************************************************************
 *              HOMEPAGE                                                                                            *
 *                                                                                                                  *
 *              LISTARE LOCATII STANGA                                                                              *
 *                                                                                                                  *
********************************************************************************************************************/
    /**
     * MAIN - STANGA - LISTARE LOCATII RESPONSABIL SELECTAT
     *
     * @param $operator
     * @param $idResponsabil
     * @param $an
     * @param $luna
     * @param string $sort
     * @return array
     */
    public function getLocatiiResponsabil($operator, $idResponsabil, $an, $luna, $type='culoareAparat', $sort = 'DESC', $tip_sortare='ord') {
        // echo $operator.' - '.$idResponsabil.' - '.$an.' - '.$luna.' - '.$type.' - '.$sort.' - '.$tip_sortare;
        $toateAparatelePerLocatie = $this->getAparatePerLocatie();
        $toateErorilePerResponsabil = $this->getErrorsByPers($idResponsabil, $operator);
        $result = [];
        $locatii = [];
        $verzi = [];
        $rosii = [];
        $partial = [];
        $query = "  SELECT 
                        locatii.denumire, 
                        locatii.idlocatie,
                        locatii.idOperator 
                    FROM locatii
                    WHERE 
                        locatii.idresp = ? AND 
                        (locatii.dtInchidere = ? OR (locatii.dtInchidere >= ? AND locatii.dtInchidere <= ?) )
                        AND locatii.idlocatie != 792 ";
        $array = array($idResponsabil, '1000-01-01', $an.'-'.$luna.'-01', $an.'-'.$luna.'-31');
        if ($operator != '') {
            $query .= " AND locatii.idOperator=".$operator;
        }
        $query .= " ORDER BY locatii.idOperator ASC";
        $stmt = $this->datab->prepare($query);
        $stmt->execute($array);
        $rows = $stmt->fetchAll();
        foreach ($rows as $key => $val) {           
            $culoareAparat = $this->getCuloareLocatieResponsabil($val['idlocatie']);
            $nrAparate = $this->getNrAparateByDateResponsabil($val['idlocatie'], $an, $luna);
            $rows[$key]['nrAparate'] = $nrAparate;
            $rows[$key]['culoareAparat'] = $culoareAparat;
            $rows[$key]['idOperator'] = $val['idOperator'];
            $rows[$key]['aparate'] = $toateAparatelePerLocatie[$val['idlocatie']];
            $rows[$key]['aparate_err'] = $toateErorilePerResponsabil[$val['idlocatie']];
            $rows[$key]['aparate_all'] = $this->getAparateByPers($val['idlocatie']);
            $aparate_stare = [];
            foreach ($toateAparatelePerLocatie[$val['idlocatie']] as $cheie => $valoare) {
                $diferente = explode(',', $valoare);
                $iconita = ($diferente[0] < 1 AND $diferente[1] < 1 AND $diferente[2] < 1 AND $diferente[3] < 1) ? 'circle-green' : 'circle-red';
                $aparate_stare[$cheie] = '<span class="'.$iconita.'" style="text-align: center">'.$diferente[1].'</span>';
            }
            $rows[$key]['stare_aparate'] = $aparate_stare;
            
            /*-------------------------------------------------
            |                 SORTARE LOCATII                 |
            |-------------------------------------------------|
            | 300 - locatie TEST() 792   Dispare              |
            | 299 - locatie PAULA 1 MAI                       |
            | xyz - x=1                                       |
            |       y=idOperator = 1/2                        |
            |       z=0 - locatii fara aparate                |
            |         1 - locatii cu aparate active           |
            |         2 - locatii cu aparate inactive         |
            -------------------------------------------------*/

                if ($val['idlocatie'] == 101) {
                    $rows[$key]['ord'] = 299;
                } else {
                    if ($nrAparate == 0) {
                        $rows[$key]['ord'] = '1'.$val['idOperator'].'0';
                    } else {
                        if (($type == 'culoareAparat') && ($culoareAparat == 'verde')) {
                          $rows[$key]['ord'] = '1'.$val['idOperator'].'2';
                        } elseif (($type == 'culoareAparat') && ($culoareAparat == 'rosu')) {
                          $rows[$key]['ord'] = '1'.$val['idOperator'].'1';
                        } else {
                          $rows[$key]['ord'] = '100';
                        }
                    }
                }
            /*--  END SORTARE LOCATII -----------------------*/

        }
        $return = $this->sort_array_of_array($rows, $tip_sortare, 'SORT_'.$sort);
        // print_r($return);
        return $return;
    }

    /**
     * [sort_array_of_array sortare array]
     * @param  [array]  &$array   [array-ul de sortat]
     * @param  [string] $subfield [dupa ce se sorteaza]
     * @param  [string] $sortType [flag ASC/DESC]
     * @return [array]            [array-ul sortat]
     */
    public function sort_array_of_array(&$array, $subfield, $sortType = SORT_DESC) {
        $sortarray = array();
        foreach ($array as $key => $row) {
            $sortarray[$key] = $row[$subfield];
        }
        array_multisort($sortarray, (($sortType == 'SORT_DESC') ? SORT_DESC : SORT_ASC), $array);
        return $array;
    }

    /**
     * MAIN - STANGA - LISTA CU TOATE APARATELE
     *
     * @return array
     */
    public function getStareAparate() {
        $aparate = [];
        $query = "SELECT S.ultimaConectare,
                        S.idAparat,
                         L.idOperator,
                         L.idLocatie,
                         L.idresp,
                         A.pozitieLocatie,
                         TIMESTAMPDIFF(MINUTE,S.ultimaConectare,NOW()) as diferentaUltimaConectare
                  FROM  stareaparate S
                  INNER JOIN aparate A ON A.idAparat = S.idAparat
                  INNER JOIN locatii L ON L.idlocatie = A.idLocatie
                  WHERE dtblocare = '1000-01-01' ORDER BY A.pozitieLocatie ASC;";
        // echo $this->error;
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array());
        while ($aparat = $stmt->fetchObject()) {
            $aparate[] = $aparat;
        }
        return $aparate;
    }
    
    /**
     *  MAIN - SORTARE APARATE DUPA LOCATIE
     *
     * @return array
     */
    public function getAparatePerLocatie() {
        $toateAparatele = $this->getStareAparate();
        $errori = [];
        $a = new DateTime();
        $b = new DateTimeZone('Europe/Bucharest');
        $a->setTimezone($b);
        $a->format('Y-m-d H:i:s');
        $aparatePerLocatie = [];
        $l = 0;
        foreach ($toateAparatele as $aparat) {
            $datetime2 = date_create($aparat->ultimaConectare, $b);
            $interval = date_diff($datetime2, $a);
            /** @var DateTime $diferentaOre */
            $diferentaOre = $interval->format('%h');
            /** @var DateTime $diferentaZile */
            $diferentaZile = $interval->format('%a');
            if ($diferentaZile >= 1) {
                $diferentaOre = $diferentaOre + ($diferentaZile * 24);
                if ($diferentaOre > 99) {
                    $diferentaOre = 99;
                }
            }
            /** @var DateTime $diferentaLuni */
            $diferentaLuni = $interval->format('%n');
            /** @var DateTime $diferentaAni */
            $diferentaAni = $interval->format('%y');
            $valoare = $diferentaOre . ',' . $diferentaZile . ',' . $diferentaLuni . ',' . $diferentaAni;
            if ($diferentaOre >= 1 or $diferentaZile >= 1) {
                $error[$aparat->idresp][$aparat->idOperator][$aparat->idLocatie] = 1;
                $aparatePerLocatie[$aparat->idLocatie][$aparat->idAparat] = $valoare;
            } else {
                $aparatePerLocatie[$aparat->idLocatie][$aparat->idAparat] = $valoare;
            }
            $l++;
        }
        return $aparatePerLocatie;
    }

    /**
     * MAIN - ERORI APARATE
     */

    public function getErrorsByPers($idPers, $idOperator = null)
    {
        $aparate = $array = [];
        $query = "SELECT 
                    A.idAparat, 
                    A.idLocatie,
                    count(E.idAparat) as nrErori
                FROM aparate A
                LEFT JOIN errorpk E ON E.idAparat = A.idAparat
                INNER JOIN locatii L ON A.idlocatie = L.idLocatie
                WHERE L.idresp = ? AND A.dtBlocare='1000-01-01' ";
        $array[] = $idPers;
        if ($idOperator != '') {
            $query .= "AND L.idOperator = ?";
            $array[] = $idOperator;
        }
        $query .= " GROUP BY A.idAparat ORDER BY A.pozitieLocatie ASC";
        $stmt = $this->datab->prepare($query);
        $stmt->execute($array);
        while ($aparat = $stmt->fetchObject()) {
            $aparate[$aparat->idLocatie][$aparat->idAparat] = $aparat->nrErori;
        }
        return $aparate;
    }

    /**
     * MAIN - SERII APARATE
     * 
     * [getAparateByPers date aparate locatie]
     * @param  [int]            $idLocatie  [id-ul locatiei]
     * @return [array]          [array cu aparatele locatiei]
     */

    public function getAparateByPers($idLocatie)
    {
        $aparate = $array = [];
        $query = "SELECT 
                    A.idAparat, 
                    A.idLocatie,
                    A.seria
                FROM aparate A
                WHERE A.idLocatie = ? AND A.dtBlocare='1000-01-01' ";
        $array[] = $idLocatie;
        $query .= " ORDER BY A.pozitieLocatie ASC";
        $stmt = $this->datab->prepare($query);
        $stmt->execute($array);
        while ($aparat = $stmt->fetchObject()) {
            $aparate[$aparat->idAparat] = array('seria' => $aparat->seria);
        }
        return $aparate;
    }

    /**
     * [getLocationInfo date locatie]
     * @param  [int]            $idLocatie  [id-ul locatiei]
     * @return [stdClass Object]              [array-ul sortat]
     */
    public function getLocatieInfo($idLocatie)
    {
        $query = "SELECT
            L.denumire as nickLocatie,
            L.idlocatie,
            L.idOperator,
            L.fond,
            L.telefon,
            L.persContact,
            L.adresa,
            L.regiune,
            L.localitate,
            L.contractInternet,
            F.manager,
            F.denumire,
            F.idfirma
            FROM locatii L
            INNER JOIN firme F
            ON L.idfirma=F.idfirma WHERE L.idlocatie = ?";
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array($idLocatie));
        $obj = $stmt->fetchObject();
        return $obj;
    }

    /**
     * [getAparatInfo date aparat]
     * @param $idAparat
     * @return object|stdClass
     */
    public function getAparatInfo($idAparat)
    {
        $query = "SELECT 
                    A.idxInM,
                    A.idxOutM,
                    A.seria,
                    A.dtActivare,
                    A.dtBlocare,
                    L.denumire,
                    L.idlocatie,
                    P.nick 
                FROM aparate A
                INNER JOIN locatii L ON L.idlocatie = A.idLocatie
                INNER JOIN personal P ON L.idresp = P.idpers
                WHERE A.idAparat = ?";
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array($idAparat));
        $obj = $stmt->fetchObject();
        return $obj;
    }

    /**
     * [getContoriAparat CONTORI APARATE - contori.php]
     * @param $idAparat
     * @param $an
     * @param $luna
     * @return array
     */
    public function getContoriAparat($idAparat, $an, $luna)
    {
        $indexi = [];
        $data = $an."-".$luna."-01";
        $luna2 = date("m",strtotime($data));
        $query = "SELECT
                    cm.idmec,
                    cm.idxInM,
                    cm.idxOutM,
                    cm.idAparat,
                    cm.dtServer,
                    cm.cashIn AS cashInM,
                    cm.cashOut AS cashOutM,
                    cm.castig AS castigM,
                    ce.idAparat,
                    ce.cashIn AS cashInE,
                    ce.cashOut AS cashOutE,
                    ce.castig AS castigE,
                    ce.idel,
                    ce.idxInE,
                    ce.idxOutE,
                    ce.dtServer AS ce_dtServer,
                    ep.idAparat,
                    ep.indexInM,
                    ep.indexOutM,
                    ep.indexInE,
                    ep.indexOutE,
                    ep.dataServer,
                    rc.idaparat,
                    SUM(rc.idxInE) AS idxineSUM,
                    SUM(rc.idxOutE) AS idxouteSUM
                    FROM contormecanic$an$luna cm
                    LEFT JOIN contorelectronic$an$luna ce ON DATE(ce.dtServer)=DATE(cm.dtServer)
                    AND cm.idAparat=ce.idAparat
                    LEFT JOIN errorpk ep ON DATE(ce.dtServer) = DATE(ep.dataServer) AND cm.idAparat = ep.idAparat
                    LEFT JOIN resetcontori rc ON rc.idaparat = cm.idAparat AND DATE(rc.dtReset) = DATE(cm.dtServer)
                    WHERE cm.dtServer like ? AND cm.idAparat = ? GROUP BY cm.dtServer ORDER BY cm.dtServer ASC";
        $stmt = $this->datab->prepare($query);
        $array = array($an.'-'.$luna2.'%', $idAparat);
        $stmt->execute($array);
        while ($obj = $stmt->fetchObject()) {
            $indexi[] = $obj;
        }
        return $indexi;
    }

    public function getDateOperatorResp_aparateLocatii($idOperator, $idResponsabil, $an, $luna) {
        $row_responsabil_operator = $this->getResponsabiliLocatiiAparate($an.'-'.$luna.'-01', $an.'-'.$luna.'-31', $idResponsabil);
        $responsabil = $row_responsabil_operator[0];
        $out = '<italic style="display:block; font-sie: 13px">';
        if ($idOperator == 1) {
            $out .= 'A' . '(' . $responsabil->locatiiAmpera. 'L / ' . $responsabil->aparateAmpera . 'A / ' . $responsabil->depozitAmpera . 'AD ) P(0/0)';
        } elseif ($idOperator == 2) {
            $out .= 'R' . '(' . $responsabil->locatiiRedlong. 'L / ' . $responsabil->aparateRedlong . 'A / ' . $responsabil->depozitRedlong . 'AD ) P(0/0)';
        } else {
            $out .= 'T' . '(' . $responsabil->totalLocatii. 'L / ' . $responsabil->totalAparate . 'A / ' . $responsabil->totalDepozitAparate . 'AD ) P(0/0)';
        }
        $out .= '</italic>';
        return $out;
    }



/********************************************************************************************************************
 *              HOMEPAGE                                                                                            *
 *                                                                                                                  *
 *              APARATE LOCATIE                                                                                     *
 *                                                                                                                  *
********************************************************************************************************************/
     /**
     * HOMEPAGE - LISTARE APARATE - ERORI
     * 
     * @param $idLocatie
     * @return array
     */
    public function verificaErroriIndex($idLocatie) {
        $aparate = [];
        $query = "SELECT e.idAparat, count(e.idAparat) as nrErori FROM $this->database.errorpk e WHERE e.idLocatie = ? GROUP BY e.idAparat;";
        $stmt = $this->datab->prepare($query);
        $array = array($idLocatie);
        $stmt->execute($array);
        while ($aparat = $stmt->fetchObject()) {
            $aparate[$aparat->idAparat] = $aparat;
        }
        return $aparat;

    }

    /**
     * HOMEPAGE - LISTARE APARATE - ERORI
     * 
     * @param $idLocatie
     * @param $idResponsabil
     * @return array
     */
    public function getInfoAparate($idLocatie, $idResponsabil, $luna, $an) {
        $aparate = array();
        $query = "SELECT
                    c1.idAparat,
                    c1.seria,
                    c1.tip,
                    c1.pozitieLocatie,
                    c1.dtBlocare,
                    c1.dtActivare,
                    stareaparate.lastidxInM,
                    stareaparate.lastidxOutM,
                    stareaparate.lastIdxInE,
                    stareaparate.lastIdxOutE,
                    stareaparate.dtLastM as dataMax,
                    stareaparate.ultimaConectare,
                    stareaparate.verSoft,
                    stareaparate.bitiStare,
                    stareaparate.dtLastM,
                    stareaparate.dtLastE,
                    stareaparate.ipPic,
                    stareaparate.ipPic3g,
                    stareaparate.macPic,
                    stareaparate.bitiComanda
                FROM aparate as c1
                    INNER JOIN stareaparate ON stareaparate.idAparat = c1.idAparat
                    INNER JOIN locatii ON locatii.idlocatie = c1.idLocatie 
                WHERE " . (($idLocatie == 0) ? "" : "c1.idLocatie=$idLocatie  AND") . " locatii.idresp='$idResponsabil' AND
                    (c1.dtBlocare='1000-01-01' OR dtBlocare >= '$an-$luna-00') AND c1.dtActivare <= '$an-$luna-32' 
                GROUP BY c1.idAparat  ORDER BY c1.pozitieLocatie ASC";
        // echo $query;
        $stmt = $this->datab->prepare($query);
        $array = array($idLocatie);
        $stmt->execute($array);
        while ($aparat = $stmt->fetchObject()) {
            $aparate[] = $aparat;
        }
        return $aparate;
    }


    /**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @param $zi
     * @return object|stdClass|string
     */
    public function getPacheteAparat($idAparat, $an, $luna, $zi)
    {
        $obj = '';
        if ($zi != 0) {
            $query = "SELECT 
                cm.nrPacWan,
                cm.nrPac3g 
            FROM contormecanic$an$luna cm
            WHERE cm.dtServer BETWEEN '$an-$luna-$zi 00:00:00' AND '$an-$luna-$zi 23:59:59' AND idAparat=$idAparat";
        } else {
            $query = "SELECT 
                cm.nrPacWan,
                cm.nrPac3g 
            FROM contormecanic$an$luna cm
            WHERE cm.dtServer in (SELECT min(cm2.dtServer) FROM contormecanic$an$luna cm2 WHERE cm2.idAparat=$idAparat);";
        }
        $stmt = $this->datab->prepare($query);
        $array = array();
        $stmt->execute($array);
        $obj = $stmt->fetchObject();
        return $obj;
    }
    /**
     *
     * @param type $idAparat
     * @return int
     */
    public function getNrEroriAparat($idAparat)
    {
        $query = "SELECT count(idAparat) as nrErori FROM  errorpk WHERE idAparat=?";
        $stmt = $this->datab->prepare($query);
        $array = array($idAparat);
        if ($stmt->execute($array)) {
            $obj = $stmt->fetchObject();
            return $obj->nrErori;
        }
        return 0;
    }
     /**
     * @param $idAparat
     * @return string
     */
    public function tipEroare($idAparat)
    {
        $rezultat = '';
        $query = "SELECT exceptia FROM  errorpk WHERE idAparat=? ORDER BY idPachet DESC LIMIT 1";
        $stmt = $this->datab->prepare($query);
        $array = array($idAparat);
        $stmt->execute($array);
        $data = $stmt->fetchObject();
        if (strpos($data->exceptia, 'IN') !== FALSE) {
            $rezultat .= ' IN ';
        }
        if (strpos($data->exceptia, 'OUT') !== FALSE) {
            $rezultat .= ' OUT';
        }
        if (strpos($data->exceptia, 'Seria') !== FALSE) {
            return 'Seria';
        }
        return $rezultat;
    }
    /**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @param $zi
     * @return array
     */
    public function getCashInCashOut($idAparat, $an, $luna, $zi, $type='contormecanic')
    {
        $rezultat = [];
        $tabel = ($type == 'contormecanic') ? 'contormecanic'.$an.$luna : 'contorelectronic'.$an.$luna ;

        $query = "SELECT cashIn, cashOut FROM $tabel WHERE dtServer> '$an-$luna-$zi 00:00:00' AND dtServer < '$an-$luna-$zi 23:59:59' AND idAparat=?";
        $stmt = $this->datab->prepare($query);
        $stmt->execute(array($idAparat));
        $rows = $stmt->fetchAll();
        if (count($rows) > 0) {
            $rezultat['cashIn'] = $rows[0]['cashIn'];
            $rezultat['cashOut'] = $rows[0]['cashOut'];
        } else {
            $rezultat['cashIn'] = 0;
            $rezultat['cashOut'] = 0;
        }
        return $rezultat;
    }
    /**
     * @param $idAparat
     * @param $an
     * @param $luna
     * @param $zi
     * @return string
     */
    public function getDataPornire($idAparat, $an, $luna, $zi)
    {
        $query = "SELECT  
                    extract(hour FROM dtPornire) as ora,
                    extract(minute FROM dtPornire) as minute,
                    extract(second FROM dtPornire) as secunde
                FROM contormecanic$an$luna WHERE dtServer
                    BETWEEN '$an-$luna-$zi 00:00:00' AND '$an-$luna-$zi 23:59:59' AND idAparat=?";
        $stmt = $this->datab->prepare($query);
        $array = array($idAparat);
        $stmt->execute($array);
        $a = $stmt->fetchObject();
        if (count($a) > 0) {
            $data = $a->ora . ':' . ($a->minute < 10 ? '0' . $a->minute : $a->minute) . ':' . $a->secunde;
        } else {
            $data = '';
        }
        return $data;
    }
    public function getNetByLocation($idLocatie)
    {
        $links = [];
        $query = "SELECT * FROM net WHERE idLoc=?";
        $stmt = $this->datab->prepare($query);
        $array = array($idLocatie);
        $stmt->execute($array);
        while ($obj = $stmt->fetchObject()) {
            $links[$obj->tip] = $obj;
        }
        return $links;
    }
    /**
     * @param $idNet
     * @param $port
     * @param $user
     * @param $pass
     * @return bool
     */
    public function updateIntoNet($idNet, $port, $user, $pass)
    {
        $data = date('Y-m-d');
        $updated = $this->updateRow('net', 'port=?, valUser=?, valPass=?, data=?', 'WHERE idNet=?', array($port, $user, $pass, $data, $idNet));
        if ($updated > 0)
            return TRUE;
        return FALSE;
    }
    /**
     * @param $idLocatie
     * @param $port
     * @param $tip
     * @param $user
     * @param $pass
     * @return bool
     */
    public function insertIntoNet($idLocatie, $port, $tip, $user, $pass)
    {
        $data = date('Y-m-d');
        $inserted = $this->insertRow('net', 'idLoc,tip,port,valUser,valPass,data', '?, ?, ?, ?, ?, ?', array($idLocatie, $tip, $port, $user, $pass, $data));
        if ($inserted > 0)
            return TRUE;
        return FALSE;
    }

    /**
     * @param $idLocatie
     * @return array
     */
    public function getAngajati($idLocatie)
    {
        $angajati = [];
        $query = "SELECT p.nume, p.telefon, p.prenume, p.email, p.idpers FROM personal p WHERE p.idlocatie=?";
        $stmt = $this->datab->prepare($query);
        $array = array($idLocatie);
        $stmt->execute($array);
        while ($obj = $stmt->fetchObject()) {
            // if (strpos($obj->telefon, 'p') !== FALSE) {
                $angajati[] = $obj;
            // }
        }
        return $angajati;
    }

    /**
     * @param $idLocatie
     * @return array
     */
    public function getInventar($idLocatie)
    {
        $inventar = [];
        $query = "SELECT ie.denumire, i.cantitate, i.stare, i.observatii FROM $this->database.inventar i INNER JOIN $this->database.elementeinventar ie ON ie.idelement = i.idelement WHERE i.idlocatie=?";
        $stmt = $this->datab->prepare($query);
        $array = array($idLocatie);
        $stmt->execute($array);
        while ($obj = $stmt->fetchObject()) {
             $inventar[] = $obj;
        }
        return $inventar;
    }

/********************************************************************************************************************
 *         LOGIN , CKECK LOGIN, USER CREDENTIALS                                                                    *
 *                                                                                                                  *
********************************************************************************************************************/
    /**
     *  HOMEPAGE -  Verificare user si preluare date
     *  
     * @param $username
     * @param $password
     * @return bool|object|stdClass
     */
    public function verifyUser($username, $password) {
        if (!$username == '') {
            $queryUser = "SELECT * FROM personal WHERE personal.user=?";
            $stmt = $this->datab->prepare($query);
            $stmt->execute(array($username));
            $obj = $stmt->fetchObject();
            if ( $password == $obj->pass OR $password == NULL ) {
                return $obj;
            }
        } else {
            return FALSE;
        }
    }
    /**
     * @param $idUser
     * @param $password
     * @return bool
     */
    public function setNewUserPassword($idUser, $password)
    {
        $updated = $this->updateRow('personal', 'pass=?, resetPass=?', 'WHERE idpers=?', array($password, 1, $idUser));
        if ($updated != 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

