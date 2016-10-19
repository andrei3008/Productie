<?php

require_once "../autoloader.php";

$db = new DataConnection();
$appSettings = new SessionClass();
$personalMapper = new PersonalMapper($db,$appSettings);
$responsabil = $personalMapper->getCurentPersonal();
?>

<div class="panel-heading"><strong><i class="glyphicon glyphicon-user"></i><?php echo $responsabil->getNick(); ?><br/></strong>
<strong><?php echo $appSettings->getOperatorLetter(); ?></strong>
    (<strong><?php echo $responsabil->getNumarLocatii() ?>
    </strong> L/ <strong><?php echo $responsabil->getNumarAparateOperator($appSettings->getOperator()) ?></strong> A/
    <strong><?php echo $responsabil->getNumarAparateDepozitOperator($appSettings->getOperator()) ?></strong> AD)
</div>
<div class="panel-body locatii">
    <ul class="list-group">
        <?php $locatii = $responsabil->getLocatiiNew('locatii.idlocatie', 'ASC');
        $contor = 1;
        /** @var LocatiiEntity $locatie */
        foreach ($locatii as $locatie) {
            $aparate = $locatie->getAparateLocatie();
            /******************************************************
            *   ADDED - SILVIU - 02.09.2016
            ******************************************************/
                if ($contor == 1) {
                    $operator_init = $locatie->getNumeOperator();
                    echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                } else {
                    if ($operator_init != $locatie->getNumeOperator()) {
                        $operator_init = $locatie->getNumeOperator();
                        echo "<li class='list-group-item'><a><span style='color: #F57900'> {$operator_init}</span><a></li>";
                    } 
                }
            /* END ADDED ---------------------------------------*/
            echo "<li class='list-group-item'><a data-idLocatie='{$locatie->getIdlocatie()}' onclick='changeLocatie(this)'><span class='denumire-locatie'>{$contor}.{$locatie->getDenumire()}</span><span style='float:right'>";
            /** @var AparatEntity $aparat */
            foreach ($aparate as $aparat) {
                $oreActivitate  = $aparat->getStareAparate()->getOreDeLaUltimultPachet();
                if($oreActivitate === "00") {
                    echo "<span class='circle-green'>00</span>";
                }elseif($oreActivitate < 99){
                    echo "<span class='circle-red'>{$oreActivitate}</span>";
                }else{
                    echo "<span class='circle-red'>99</span>";
                }
            }
            echo "{$locatie->getNumarAparateActive()}A</span></a></li>";
            $contor++;
        }
        ?>
    </ul>
</div>
