<?php

class PageClass
{

    public function getLiteraZilei($ziDinSaptamana){
        $litera ='';
        switch ($ziDinSaptamana){
            case 1 : $litera = 'L';break;
            case 2 : $litera = 'Ma';break;
            case 3 : $litera = 'Mi';break;
            case 4 : $litera = 'J';break;
            case 5 : $litera = 'V';break;
            case 6 : $litera = 'S';break;
            case 0 : $litera = 'D';break;
        }
        return $litera;
    }
    public function setLocale()
    {
        $locales = array('ro.utf-8', 'ro_RO.UTF-8', 'ro_RO.utf-8', 'ro', 'ro_RO', 'ro_RO.ISO8859-2');
        setlocale(LC_TIME, $locales);
    }

    public function createDayPicker($luna, $an, $selectors, $elementHref = [])
    {
        $numarZile = cal_days_in_month(CAL_GREGORIAN,$luna,$an);
        $daypicker = '';
        $daypicker .= $this->getDayPickerStartTag($selectors);
        $i = 0;
        while ($i < $numarZile) {
            $i++;
            if ($an < date('Y')) {
                $daypicker .= $this->createDayPickerElement($i, 'not disabled', $elementHref);
            } elseif ($an == date('Y') AND $luna < date('n')) {
                $daypicker .= $this->createDayPickerElement($i, 'not disabled', $elementHref);
            } elseif ($an == date('Y') AND $luna == date('n') AND $i <= date('j')) {
                $daypicker .= $this->createDayPickerElement($i, 'not disabled', $elementHref);
            } else {
                $daypicker .= $this->createDayPickerElement($i, null, $elementHref);
            }
        }


        $daypicker .= $this->getDayPickerEndTag();
        return $daypicker;
    }

    public function print_r($variabila){
        $sirNou =  '<pre>';
        $sirNou .= print_r($variabila);
        $sirNou .= '</pre>';
        return $sirNou;
    }

    public function afiseazaData($date, $tip = 'full') {
        $time = strtotime($date);
        if ($tip == 'full') {
            $newformat = date('d M y &#8226; H:i:s', $time);
        }else{
            $newformat = date('d M y',$time);
        }

        return $newformat;
    }


    public function getWell($text){
        return "<div class='well well-sm'>$text</div>";
    }


    public function getDayPickerStartTag($selectors)
    {
        return "<ul $selectors>";
    }

    public function createDayPickerElement($zi, $disabled = null, $elementHref = [])
    {
        $element = "<li " . (($disabled == null) ? 'class="disabled"' : '') . ">"
            . "<a href='?";
        $element .= 'zi=' . $zi . '&luna=' . $elementHref['luna'] . '&an=' . $elementHref['an'];
        $element .= "'>$zi<br/>" . strftime("%a", strtotime($elementHref['an'] . "-" . $elementHref['luna'] . "-" . $zi)) . "</a></li>";
        return $element;
    }

    public function getDayPickerEndTag()
    {
        return "</ul>";
    }
    
    public function niceIndex($index){
        $raspuns = '';
        for($i=0;$i<strlen($index);$i++){
            $raspuns.= "<span class=''>".$index[$i]."</span> &nbsp;";
        }
        return $raspuns;
    }

    /**
     *
     * @param string $name
     * @param string $identifiers
     * @param array $options
     * @return string
     */
    public function createSelectElement($name, $identifiers, $options)
    {
        $select = $this->getSelectOpen($name, $identifiers);
        foreach ($options as $key => $value) {
            $select .= $this->insertOption($key, $value);
        }
        $select .= $this->getSelectCloser();
        return $select;
    }

    public function getSelectOpen($name, $class)
    {
        return "<select name='$name' $class>";
    }

    public function insertOption($value, $name)
    {
        return "<option value='$value'>$name</option>";
    }

    public function getSelectCloser()
    {
        return "</select>";
    }

    public function getLuniArray()
    {
        return $luni = array(
            '1' => 'Ianuarie',
            '2' => 'Februarie',
            '3' => 'Martie',
            '4' => 'Aprilie',
            '5' => 'Mai',
            '6' => 'Iunie',
            '7' => 'Iulie',
            '8' => 'August',
            '9' => 'Septembrie',
            '10' => 'Octombrie',
            '11' => 'Noiembrie',
            '12' => 'Decembrie'
        );
    }

    public function checkPasswords($oldPassDb, $oldPass, $newPass1, $newPass2)
    {
        if ($oldPassDb != $oldPass)
            return 'Vechea Parola este incorecta!!!';
        if ($newPass1 != $newPass2)
            return 'Cele doua parole nu sunt identice!!!!';
        if (strlen($newPass1) < 4)
            return 'Parola are mai putin de 4 (patru) caractere!!!';
        return TRUE;
    }

    function printDialog($tip, $mesaj)
    {
        return "<div class='alert alert-$tip'>
                <strong>Atentie!</strong><br/>$mesaj.
           </div>";
    }

    public function verifyIndexLength($index)
    {
        if (strlen($index) > 6) {
            return substr($index, -6, 6);
        } else {
            $final = '';
            for ($i = strlen($index); $i < 6; $i++) {
                $final .= '0';
            }
            $final .= $index;
            return $final;
        }
    }

    public function maxText($string, $maxDimension)
    {
        if (strlen($string) > $maxDimension) {
            return substr($string, 0, $maxDimension) . '...';
        }
        return $string;
    }

    public function testReset($index)
    {
        $indexP = [];
        if (strlen($index) > 6) {
            $indexP['reset'] = substr($index, 0, 1);
            $indexP['index'] = substr($index, 1, 5);
        } else {
            return $index;
        }
        return $indexP;
    }

    public function getIndexReset($index) {
        if (strlen($index) <= 6) {
            return '<span class="reset">&nbsp;</span>';
        } else {
            return '<span class="reset">' . substr($index, 0, 1) . '</span>';
        }
    }

    public function getFirma($idOperator)
    {
        if ($idOperator == 1) {
            return "Ampera Games SRL";
        } elseif ($idOperator == 2) {
            return "Redlong";
        }
        return "Total";
    }

    public function createModal($id, $title, $content, $footer)
    {
        $modal = $this->setModalTirle($id, $title);
        $modal .= $this->setModalContent($content);
        $modal .= $this->setModalFooter($footer);
        return $modal;
    }

    public function setModalTirle($id, $title)
    {
        return "<div class=\"modal fade\" id=\"$id\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\">
                    <div class=\"modal-dialog\" role=\"document\">
                        <div class=\"modal-content\">
                            <div class=\"modal-header\" id='messageReturn'>
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                                <h4 class=\"modal-title\" id=\"myModalLabel\">$title</h4>
                            </div>";
    }

    public function setModalContent($content)
    {
        return "<div class=\"modal-body\">
                $content
               </div>";
    }

    public function setModalFooter($content)
    {
        return "<div class=\"modal-footer\">
                      $content
                    </div>
                </div>
            </div>
        </div>";
    }

    public function createLabel($labelName,$labelFor){
        return "<label for='$labelFor'>$labelName</label>";
    }
    public function createFieldset($type, $name, $value, $cssOptions = null,$required=0)
    {
    return "<fieldset>".
                $this->createLabel($value,$name).
                $this->createInput($type,$name,$value,$cssOptions,$required)."
            </fieldset>";
    }

    public function createInput($type, $name, $value, $cssOptions = null,$required=0)
    {
        return "<input type='$type' name='$name' placeholder='$value' $cssOptions ".(($required!=0) ? 'required' : '' ).">";
    }
    public function checkLogin($username,$operator){
        if (!isset($username) AND !isset($operator)) {
            header('location:'.DOMAIN.'/index.php');
        }
    }
    public function getLuna($nrLuna){
        switch($nrLuna){
            case 1 : return 'Ianuarie';break;
            case 2 : return 'Februarie';break;
            case 3 : return 'Martie';break;
            case 4 : return 'Aprilie';break;
            case 5 : return 'Mai'; break;
            case 6 : return 'Iunie'; break;
            case 7 : return 'Iulie'; break;
            case 8 : return 'August'; break;
            case 9 : return 'Septembrie';break;
            case 10 : return 'Octombrie';break;
            case 11 : return 'Noiembrie'; break;
            case 12 : return 'Decembrie'; break;
            default : return 'Valoare incorecta';break;
        }
    }
    public function checkPic($domain)
    {
        //check, if a valid url is provided
        if(!filter_var($domain, FILTER_VALIDATE_URL))
        {
            return false;
        }

        //initialize curl
        $curlInit = curl_init($domain);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,1);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT_MS,300);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

        //get answer
        $response = curl_exec($curlInit);
        curl_close($curlInit);

        if ($response) return true;

        return false;
    }

    public function getAssociativeArrayFromStringArray(array $array,$delimiter){
        $arrayRetur = [];
        foreach($array as $key => $value){
            if(strlen($value) > 3) {
                $parti = explode($delimiter, $value);
                $arrayRetur[$parti[0]] = $parti[1];
            }
        }
        return $arrayRetur;
    }

    public function transformaInAnSiLuni($nrLuni){
        $ani = floor($nrLuni/12);
        $luni = $nrLuni - ($ani * 12);
        $string = "{$ani} ani si {$luni} luni";
        return $string;
    }
    
    public function verificaExistenta($ceva){
        if(isset($ceva)){
            return $ceva;
        }
        return '';
    }

    public function getBinariFromDecimal($number)
    {
        $binary = base_convert($number,10,2);

        $final = [];
        for ($j = strlen($binary); $j < 32; $j++) {

            $final[$j] = '0';
        }
        $binary = array_reverse(str_split($binary));
        $final = array_merge($binary, $final);
        return $final;
    }

    public function zileInLuna($luna,$an)
    {
        return cal_days_in_month(CAL_GREGORIAN,$luna,$an);
    }

    public function getSemnificatieBiti($pozitie)
    {
        $reprezinta = '';
        $bazaDate = '';
        $numeFormular = '';
        switch ($pozitie) {
            case 1 :
            case 2 :
            case 3 :
            case 4 :
            case 5 :
            case 6 :
            case 7 :
            case 8 :
                $reprezinta = 'Rezervati pentru implementari ulterioare!';
                $bazaDate = '1';
                $numeFormular = '';
                break;
            case 9 :
                $reprezinta = 'Diff In';
                $bazaDate = 'difIn';
                $numeFormular = '';
                break;
            case 10 :
                $reprezinta = 'Diff Out';
                $bazaDate = 'difOut';
                $numeFormular = '';
                break;
            case 11 :
                $reprezinta = 'Trimite Audit';
                $bazaDate = 'skip';
                $numeFormular = '';
                break;
            case 12 :
                $reprezinta = 'Trimite Record';
                $bazaDate = 'skip';
                $numeFormular = '';
                break;
            case 13 :
                $reprezinta = 'Activeaza / Dezactiveaza Cititorul de bani!';
                $bazaDate = 'skip';
                $numeFormular = 'activeazaCititor';
                break;
            case 14 :
                $reprezinta = 'Porneste / Opreste Aparatul!';
                $bazaDate = 'skip';
                $numeFormular = 'bit14';
                break;
            case 15 :
                $reprezinta = 'Modifica parola la interfata pic';
                $bazaDate = 'passPic';
                $numeFormular = 'bit15';
                break;
            case 16 :
                $reprezinta = 'Modifica utilizatorul la interfata PIC';
                $bazaDate = 'userPic';
                $numeFormular = 'bit16';
                break;
            case 17 :
                $reprezinta = 'Modifica hostname pic';
                $bazaDate = 'hostNamePic';
                $numeFormular = 'bit17';
                break;
            case 18 :
                $reprezinta = 'Modifica adresa server pentru pachet 3';
                $bazaDate = 'adrPachet3';
                $numeFormular = 'bit18';
                break;
            case 19 :
                $reprezinta = 'Modifica adresa server pentru pachet 2';
                $bazaDate = 'adrPachet2';
                $numeFormular = 'bit19';
                break;
            case 20 :
                $reprezinta = 'Modifica adresa server pentru pachet 1';
                $bazaDate = 'adrPachet1';
                $numeFormular = 'bit20';
                break;
            case 21 :
                $reprezinta = 'Modifica perioada trimitere la server pachet 2 (Tact)';
                $bazaDate = 'timpPachet2';
                $numeFormular = 'bit21';
                break;
            case 22 :
                $reprezinta = 'Modifcia perioada trimitere la server pachet 1 ';
                $bazaDate = 'timpPachet1';
                $numeFormular = 'bit22';
                break;
            case 23 :
                $reprezinta = 'Modifica contor mecanic timp off';
                $bazaDate = 'timpOff';
                $numeFormular = 'bit23';
                break;
            case 24 :
                $reprezinta = 'Modifica "Trimite daca se modifica Contor Electronic (SAS)"';
                $bazaDate = 'skip';
                $numeFormular = 'bit24';
                break;
            case 25 :
                $reprezinta = 'Modifica "Trimite daca se modifica Index Bet"';
                $bazaDate  = 'skip';
                $numeFormular = "bit25";
                break;
            case 26 :
                $reprezinta = 'Modifica "Trimite daca se modifica Index Out"';
                $bazaDate = 'skip';
                $numeFormular = "bit26";
                break;
            case 27 :
                $reprezinta = 'Modifica "Trimite daca se modifica Index In"';
                $bazaDate = 'skip';
                $numeFormular = "bit27";
                break;
            case 28 :
                $reprezinta = 'Modifica Index Bet Mecanica';
                $bazaDate = 'idxBetMRet';
                $numeFormular = "bit28";
                break;
            case 29 :
                $reprezinta = 'Modifica Index Out Mecanic';
                $bazaDate = 'idxOutMRet';
                $numeFormular = "bit29";
                break;
            case 30 :
                $reprezinta = 'Modifica Index In Mecanic';
                $bazaDate = 'idxInMRet';
                $numeFormular = "bit30";
                break;
            case 31 :
                $reprezinta = 'Modifica Id Aparat';
                $bazaDate = 'idApRetur';
                $numeFormular = "bit31";
                break;
        }
        $rezultat['semnificatie'] = $reprezinta;
        $rezultat['bazaDate'] = $bazaDate;
        $rezultat['denumireCamp'] = $numeFormular;
        return $rezultat;
    }

    public function instantiazaBitiComanda(){
        $biti = [];
        for($i=0;$i<=31;$i++){
            $biti[$i] = 0;
        }
        return $biti;
    }
}
