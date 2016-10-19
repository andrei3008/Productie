<?php
$handle = fopen('../text/serii.txt', 'a+');
$parti = [];
while ($rand = fgets($handle)) {
    $parti = preg_split('/[^a-z0-9.\']+/i', $rand);
    foreach($parti as $parte){
        $partii[] = $parte;
    }
}
$serii = [];
$errors = [];
$lungimeArray = count($partii);
for ($i = 0; $i < $lungimeArray; $i++) {
    if(!is_numeric($partii[$i]) AND strlen($partii[$i])==2){
        $serii[] = $partii[$i].$partii[$i+1];
        $i++;
    }elseif(!is_numeric($partii[$i]) AND strlen($partii[$i])==6){
        $serii[] = $partii[$i];
    }elseif(!is_numeric($partii[$i]) AND strlen($partii[$i]!=6 OR strlen($partii[$i])!=2)){
        $errors[]=$partii[$i];
    }elseif(is_numeric($partii[$i]) AND strlen($partii[$i])==6 or strlen($partii[$i])==7){
        $serii[]=$partii[$i];
    }else{
        $errors[] = $partii[$i];
    }
}
$j = 1;
foreach ($serii as $serie) {
    echo 'Seria ' . $j . ' : ' . $serie .'<br/>';
    $j++;
}
foreach ($errors as $error) {
    if($error != '')
    echo "Nu am putut stabili seria : " . $error;
}