<?php 
    class rapoarte
    {
    	private $last_day;
    	private $data;
        public function __construct($db, $data) {
            $this->db = $db;
            $this->data = $data;
            $this->last_day = date('t');
        }
        /**
        * afisare zile din luna curenta sau luna selectata
        * params = array
        */
        public function load_zile_luna($params) {
            $an = $params[an];
            $luna = $params[luna];
            $tip = $params[tip];
            $locatie = $params[locatie];
            $firma = $params[firma];
            $thisTime = strtotime(date($an."-".$luna."-1"));
            $endTime = strtotime(date($an."-".$luna."-".$this->last_day));
            $i = 1;
            // parcurgere zi cu zi
            while($thisTime <= $endTime) {
                $thisDate = date('Y-m-d', $thisTime);
                $currentDay = date('Y-m-d');
                $dataa = $this->data->short_zi_ro($thisDate); // varianta scurta in romana a zilei
                $curent = ($thisDate == $currentDay) ? 'active': '';
                $out .= '
                    <div class="month-days '.$curent.'" data-zi="'.$thisDate.'" data-loc="'.$locatie.'">
                        <span>'.$i.'</span>
                        <span>'.$dataa.'</span>
                    </div>';

                $thisTime = strtotime('+1 day', $thisTime); // increment for loop
                $i++;
            }
            $returned = array('err'=> $data, 'out' => $out);
            echo json_encode($returned);
        }

        public function generare_raport($params) {
            $data = $params[data_select];
            $firma = $params[firma_select];
            $loc = $params[locatie];
            $tip = $params[tip];
            $operator = $params[operator];
            $de_unde = $params[de_unde];
            $out = '';
            foreach ($data as $key => $data_sel) {
                $dataa = str_replace("'", "", $data_sel);
                $date_incasari = ($tip == 'siz') ? $this->date_incasari($dataa, $loc, '') : $this->date_incasari_sil($dataa, $loc, '');
                $date_firma = $this->date_firma_id($dataa, $firma, $loc, $operator, $tip);
                // echo $dataa;
                $out .= '
                    
                    <div style="page-break-after:always"><table style="width:100%" id="tabel-raport-head">';
                if (($de_unde != 'export') && ($tip == 'siz')) {
                    $out .= '   <tr>
                                    <td>
                                        <div class="btn-group btn-print-group-current">
                                            <button type="button" class="btn btn-info btn-sm dropdown-toggle btn-print-current" 
                                                data-locatie="'.$loc.'"  
                                                data-firma="'.$firma.'" 
                                                data-tip="'.$tip.'" 
                                                data-dataa="'.$dataa.'" 
                                                data-perioada="zilnic" 
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" aria-expanded="false">
                                                Printeaza zi curenta
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-ext="pdf">PDF</a></li>
                                                <li><a href="#" data-ext="xls">.xls</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>';
                }
                 $out .= $date_firma.'
                        <tr id="head">
                            <td>Nr crt</td>
                            <td>Seria</td>
                            <td colspan="3">Indexul contoarelor de inceput (Si)</td>
                            <td colspan="3">Indexul contoarelor de sfarsit (Sf)</td>
                            <td colspan="3">Factor de multiplicare (F)</td>
                            <td colspan="3">Diferenta dintre indexurile contoarelor <br />(D) = (Sf-Si)xF</td>
                            <td>Soldul impulsurilor</td>
                            <td>Pret / impuls</td>
                            <td>Taxa de participare colectata (T)</td>
                            <td>Total plati efectuate catre jucatori (P) </td>
                            <td>Incasari (venituri) (lei)
                        </tr>
                        <tr id="inc">
                            <td></td>
                            <td></td>
                            <td>I</td>
                            <td>Ej</td>
                            <td>Ei</td>
                            <td>I</td>
                            <td>Ej</td>
                            <td>Ei</td>
                            <td>I</td>
                            <td>Ej</td>
                            <td>Ei</td>
                            <td>I</td>
                            <td>Ej</td>
                            <td>Ei</td>
                            <td>= 11-12-13</td>
                            <td>lei</td>
                            <td>= 11 * 15</td>
                            <td>13 * 15</td>
                            <td> = 14 * 15 <br />= 16 - 17</td>
                        </tr>
                        <tr id="inc2">   
                            <td>0</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                            <td>11</td>
                            <td>12</td>
                            <td>13</td>
                            <td>14</td>
                            <td>15</td>
                            <td>16</td>
                            <td>17</td>
                            <td>18</td>
                        </tr>'.$date_incasari.'
                    </table></div><pagebreak/>
                ';
            }
           
        	
            $returned = array('err'=> $data, 'out' => $out);
            if ($de_unde == 'export') {
                return json_encode($returned);
            } else {
                echo json_encode($returned);
            }
            
        }

        /**
         * DATE INCASARI SITUATIE ZILNICA
         *
         * @param      string   $data     Data selectata pentru raport
         * @param      int      $loc      id localitate
         * @param      string   $de_unde  de unde se face apelul (export/'')
         *
         * @return     <type>  ( description_of_the_return_value )
         */
        public function date_incasari($data, $loc, $de_unde = '') {
            $data_sh = date('n', strtotime($data));
            $an_sh = date('Y', strtotime($data));
            $zi_sh = date('d', strtotime($data));

            $data_anterioara = date('Y-m-d', strtotime($data .' -1 day'));
            $an_ant = date('Y', strtotime($data_anterioara));
            $data_ant = date('n', strtotime($data_anterioara));

            $tabel = 'contormecanic'.$an_sh.$data_sh;
            $tabel_ant = 'contormecanic'.$an_ant.$data_ant;
            // echo $tabel;
            $total_incasari = 0;
            $sql = 'SELECT 
                        c.*,
                        l.denumire AS locatie, l.regiune AS loc_reg, l.localitate AS loc_oras, l.adresa AS loc_adrsa,
                        a.seria as serie_aparat,
                        v.fm_mec,
                        v.pi_mec
                    FROM
                        '.$tabel.' c,
                        locatii l,
                        aparate a,
                        variabile v
                    WHERE
                        l.idLocatie = c.idLocatie AND
                        c.idAparat = a.idAparat AND
                        v.idaparat = c.idAparat AND
                        c.idLocatie = ? AND
                        c.dtPic = ?';

            $stmt = $this->db->datab->prepare($sql); 
            $stmt->execute(array($loc, $data));
            $rows = $stmt->fetchAll();
            $i = 1;
            foreach ($rows as $key => $val) {
                $bk = ($i % 2 == 0) ? 'bk1' : 'bk2';
                
                $serie_aparat = $val[serie_aparat];
                $row2 = $this->db->getRows($tabel_ant,'idxInM, idxOutM', 'WHERE dtPic=? and idAparat=? ORDER BY idmec DESC LIMIT 1', array($data_anterioara, $val[idAparat]));
                $fm_mec = $val[fm_mec];
                $pi_mec = $val[pi_mec];


                $sii = $row2[0][idxInM];
                $siej = 0;
                $siei = $row2[0][idxOutM];

                $sfi = $val[idxInM];
                $sfej = 0;
                $sfei = $val[idxOutM];

                $di = ($sfi - $sii) * $fm_mec;
                $dej = ($sfej - $siej) * $fm_mec;
                $dei = ($sfei - $siei) * $fm_mec;

                $si = $di - $dej - $dei;
                $t = $di * $pi_mec;
                $p = $dei * $pi_mec;
                
                $venit = $si * $pi_mec;
                $venit2 = $t * $p;
                $jackpot = 0;

                $pt_excel[$i] = array(
                    'serie_aparat' => $serie_aparat,
                    'sii' => $sii,
                    'siej' => $siej,
                    'siei' => $siei,
                    'sfi' => $sfi,
                    'sfej' => $sfej,
                    'sfei' => $sfei,
                    'fm_mec_i' => $fm_mec,
                    'fm_mec_ej' => 0,
                    'fm_mec_ei' => $fm_mec,
                    'di' => $di,
                    'di' => $di,
                    'dej' => $dej,
                    'dei' => $dei,
                    'si' => $si,
                    'pi_mec' => $pi_mec,
                    't' => $t,
                    'p' => $p,
                    'venit' => $venit,
                    'jackpot' => $jackpot,
                );
                $out .= "
                    <tr class='$bk'>
                        <td> $i         </td>       <!-- 0 -->
                        <td> $serie_aparat  </td>   <!-- 1 -->
                        <td> $sii  </td>       <!-- 2 -->
                        <td> $siej  </td>   <!-- 3 -->
                        <td> $siei   </td>  <!-- 4 -->
                        <td> $sfi    </td>  <!-- 5 -->
                        <td> $sfej   </td>  <!-- 6 -->
                        <td> $sfei   </td>  <!-- 7 -->
                        <td> $fm_mec</td>   <!-- 8 -->
                        <td> 0      </td>   <!-- 9 -->
                        <td> $fm_mec</td>   <!-- 10 -->
                        <td> $di    </td>   <!-- 11 -->
                        <td> $dej   </td>   <!-- 12 -->
                        <td> $dei   </td>   <!-- 13 -->
                        <td> $si    </td>   <!-- 14 -->
                        <td> $pi_mec</td>   <!-- 15 -->
                        <td> $t     </td>   <!-- 16 -->
                        <td> $p     </td>   <!-- 17 -->
                        <td> $venit </td>   <!-- 18 -->
                    </tr>
                ";
                $total_incasari += $venit;
                $jackpot += $jackpot;
                $i++;
            }
            $total = $total_incasari + $jackpot;
            $out .= "
                    <tr>
                        <td colspan='18' class='center'>INCASARI (VENITURI) ZILNICE SLOT MACHINE </td>
                        <td class='center'> $total_incasari  </td>
                    </tr>
                    <tr>
                        <td colspan='18' class='center'>TOTAL CASTIGURI JACKPOT ACORDATE ZILNIC</td>
                        <td class='center'> $jackpot  </td>
                    </tr>
                    <tr>
                        <td colspan='18' class='center'>TOTAL INCASARI (VENITURI) ZILNICE </td>
                        <td class='center'> $total  </td>
                    </tr>
                ";
            if ($de_unde != 'export') {
                return $out;
            } else {
                return $pt_excel;
            }

        }

         /**
         * DATE INCASARI SITUATIE LUNARA
         *
         * @param      string   $data     Data selectata pentru raport
         * @param      int      $loc      id localitate
         * @param      string   $de_unde  de unde se face apelul (export/'')
         *
         * @return     <type>  ( description_of_the_return_value )
         */
        public function date_incasari_sil($data, $loc, $de_unde = '') {
            $data_sh = date('n', strtotime($data));
            $an_sh = date('Y', strtotime($data));
            $zi_sh = date('d', strtotime($data));

            $luna_anterioara = date('Y-m-t', strtotime($data .' -1 month'));
            $luna_curenta = date('Y-m-t', strtotime($data));

            $tabel = 'contormecanic'.$an_sh.$data_sh;
            // echo $tabel;
            $total_incasari = 0;
            $sql = 'SELECT 
                        a.idmec,
                        a.idAparat,
                        a.idLocatie,
                        MAX(a.idxInM) AS sii,
                        a.idxOutM AS sei,
                        a.dtServer AS data1,
                        MAX(b.idxInM) AS sfi,
                        b.idxOutM AS sfei,
                        b.dtServer AS data2,
                        
                        l.denumire AS locatie, l.regiune AS loc_reg, l.localitate AS loc_oras, l.adresa AS loc_adrsa,
                        ap.seria as serie_aparat,
                        v.fm_mec,
                        v.pi_mec
                    FROM
                        contormecanic20167 a
                            LEFT JOIN
                        contormecanic20167 b ON a.idAparat = b.idAparat
                            AND b.dtServer <= "'.$luna_curenta.' 00:00:00",
                        locatii l,
                        aparate ap,
                        variabile v
                    WHERE
                        a.dtServer <= "'.$luna_anterioara.' 23:59:59" AND
                        l.idLocatie = a.idLocatie AND
                        a.idAparat = ap.idAparat AND
                        v.idaparat = a.idAparat AND
                        a.idLocatie = ?
                    GROUP BY a.idAparat
                    ORDER BY a.idAparat';
            $stmt = $this->db->datab->prepare($sql); 
            $stmt->execute(array($loc));
            $rows = $stmt->fetchAll();
            $i = 1;
            foreach ($rows as $key => $val) {
                $bk = ($i % 2 == 0) ? 'bk1' : 'bk2';
                
                $serie_aparat = $val[serie_aparat];
                $fm_mec = $val[fm_mec];
                $pi_mec = $val[pi_mec];


                $sii = $val[sii];
                $siej = 0;
                $siei = $val[sei];

                $sfi = $val[sfi];
                $sfej = 0;
                $sfei = $val[sfei];

                $di = ($sfi - $sii) * $fm_mec;
                $dej = ($sfej - $siej) * $fm_mec;
                $dei = ($sfei - $siei) * $fm_mec;

                $si = $di - $dej - $dei;
                $t = $di * $pi_mec;
                $p = $dei * $pi_mec;
                
                $venit = $si * $pi_mec;
                $venit2 = $t * $p;
                $jackpot = 0;

                $pt_excel[$i] = array(
                    'serie_aparat' => $serie_aparat,
                    'sii' => $sii,
                    'siej' => $siej,
                    'siei' => $siei,
                    'sfi' => $sfi,
                    'sfej' => $sfej,
                    'sfei' => $sfei,
                    'fm_mec_i' => $fm_mec,
                    'fm_mec_ej' => 0,
                    'fm_mec_ei' => $fm_mec,
                    'di' => $di,
                    'di' => $di,
                    'dej' => $dej,
                    'dei' => $dei,
                    'si' => $si,
                    'pi_mec' => $pi_mec,
                    't' => $t,
                    'p' => $p,
                    'venit' => $venit,
                    'jackpot' => $jackpot,
                );
                $out .= "
                    <tr class='$bk'>
                        <td> $i         </td>       <!-- 0 -->
                        <td> $serie_aparat  </td>   <!-- 1 -->
                        <td> $sii  </td>       <!-- 2 -->
                        <td> $siej  </td>   <!-- 3 -->
                        <td> $siei   </td>  <!-- 4 -->
                        <td> $sfi    </td>  <!-- 5 -->
                        <td> $sfej   </td>  <!-- 6 -->
                        <td> $sfei   </td>  <!-- 7 -->
                        <td> $fm_mec</td>   <!-- 8 -->
                        <td> 0      </td>   <!-- 9 -->
                        <td> $fm_mec</td>   <!-- 10 -->
                        <td> $di    </td>   <!-- 11 -->
                        <td> $dej   </td>   <!-- 12 -->
                        <td> $dei   </td>   <!-- 13 -->
                        <td> $si    </td>   <!-- 14 -->
                        <td> $pi_mec</td>   <!-- 15 -->
                        <td> $t     </td>   <!-- 16 -->
                        <td> $p     </td>   <!-- 17 -->
                        <td> $venit </td>   <!-- 18 -->
                    </tr>
                ";
                $total_incasari += $venit;
                $jackpot += $jackpot;
                $i++;
            }
            $total = $total_incasari + $jackpot;
            $out .= "
                    <tr>
                        <td colspan='18' class='center'>INCASARI (VENITURI) LUNARE SLOT MACHINE </td>
                        <td class='center'> $total_incasari  </td>
                    </tr>
                    <tr>
                        <td colspan='18' class='center'>TOTAL CASTIGURI JACKPOT ACORDATE LUNAR</td>
                        <td class='center'> $jackpot  </td>
                    </tr>
                    <tr>
                        <td colspan='18' class='center'>TOTAL INCASARI (VENITURI) LUNARE </td>
                        <td class='center'> $total  </td>
                    </tr>
                ";
            if ($de_unde != 'export') {
                return $out;
            } else {
                return $pt_excel;
            }

        }
         /**
         * DATE OPERATOR si LOCATIE
         *
         * @param      string   $data       Data selectata pentru raport
         * @param      int      $id         id localitate
         * @param      int      $loc        id localitate
         * @param      int      $operator   id operator
         * @param      string   $tip        SIZ / SIL
         *
         * @return     <type>  ( description_of_the_return_value )
         */
        public function date_firma_id($data, $id, $loc, $operator, $tip) {
            $data_sel = date('d-m-Y', strtotime($data));
            
            $date_locatie = $this->get_date_locatie(array('id' => $loc));
            $date_locatie = json_decode($date_locatie);

            $date_operator = $this->get_date_operator(array('id' => $operator));
            $date_operator = json_decode($date_operator);

            $tit = ($tip == 'siz') ? 'SITUATIA INCASARILOR (VENITURILOR) ZILNICE' : 'SITUATIA INCASARILOR (VENITURILOR) LUNARE';
            $dat = ($tip == 'siz') ? $data_sel : $this->data->luna_ro(date('m', strtotime($data_sel))).' '.date('Y', strtotime($data));
            $out = "
                <tr style='border: 1px solid; border-top: 0'>
                    <td colspan=\"17\" id=\"date-firma\">
                        Organizator: <span>".$date_operator->denumire."</span> <br />
                        Domiciliul fiscal: <span> ".$date_operator->adresa."</span><br />
                        Cod de identitate fiscala: <span> ".$date_operator->cui."</span><br />
                        Nr. Reg Com: <span> ".$date_operator->reg."</span><br />
                        Capital Social: <span> ".$date_operator->capital."</span><br />
                        Licenta: <span> ".$date_operator->licenta."</span><br />
                        Adresa punct lucru: <span> ".$date_locatie->adresa."</span>
                    </td>
                    <td colspan=\"2\" id=\"data-raport\">
                        Data: <span>".$dat."</span>
                    </td>
                </tr>
                <tr style='border: 1px solid'>
                    <td colspan=\"19\" class=\"center\" id=\"title\">
                        ".$tit." <br />
                        obtinute din activitatea de exploatare a jocurilor de noroc slot machine (lei)
                    </td>
                </tr>
            ";
            return $out;
        }

        public function get_nume_firma($params) {
            $id = $params[id];
            $row = $this->db->getRows('firme','denumire', 'WHERE idfirma=?', array($id));
            $returned = array('denumire'=> $row[0][denumire]);
            echo json_encode($returned);
        }

        public function get_date_firma($params) {
            $id = $params[id];
            $row = $this->db->getRows('firme','denumire, adresa', 'WHERE idfirma=?', array($id));
            $returned = array('denumire'=> $row[0][denumire], 'adresa' => $row[0][adresa]);
            return json_encode($returned);
        }

        public function get_nume_locatie($params) {
            $id = $params[id];
            $row = $this->db->getRows('locatii','denumire', 'WHERE idlocatie=?', array($id));
            $returned = array('denumire'=> $row[0][denumire]);
            echo json_encode($returned);
        }

        public function get_date_locatie($params) {
            $id = $params[id];
            $row = $this->db->getRows('locatii','denumire, adresa, idOperator', 'WHERE idlocatie=?', array($id));
            $returned = array('denumire'=> $row[0][denumire], 'adresa' => $row[0][adresa]);
            return json_encode($returned);
        }

        public function get_date_operator($params) {
            $id = $params[id];
            $row = $this->db->getRows('operatori','domiciliuFiscal, denFirma, cui, regComert, capitalSocial, licenta', 'WHERE idoperator=?', array($id));
            $returned = array(
                            'denumire'=> $row[0][denFirma], 
                            'adresa' => $row[0][domiciliuFiscal], 
                            'cui' => $row[0][cui],
                            'reg' => $row[0][regComert],
                            'capital' => $row[0][capitalSocial],
                            'licenta' => $row[0][licenta]
                        );
            return json_encode($returned);
        }
    }
?>