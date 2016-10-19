<?php
require_once "../autoloader.php";

$db = new DataConnection();
$appData = new SessionClass();

$locatie = new LocatiiEntity($db, $appData);
$locatie->getLocatieCurenta();
$operator = new OperatorEntity($db, $appData);
$operator->getCurrentOperator();
$aparate = $locatie->getAparateLocatie();
?>
<div class="panel-heading">
    <strong><?php echo $locatie->getDenumire(); ?></strong> |
    <a href="http://maps.google.com?q=<?php echo urlencode($locatie->getAdresa()); ?>"><i class="fa fa-map-marker"
                                                                                          aria-hidden="true"></i><i><?php echo $locatie->getAdresa() ?></i></a>
    |
    <i class="fa fa-info-circle" aria-hidden="true"></i> Deschis la data : <?php echo $locatie->getDtInfiintare(); ?> |
    <?php if ($locatie->getDtInchidere() != "01-Jan-1970") { ?>
        <i class="fa fa-info-circle" aria-hidden="true"></i> Inchis la data : <?php echo $locatie->getDtInchidere(); ?>
    <?php } ?>
    <br/><i class="fa fa-bookmark-o" aria-hidden="true"></i> <?php echo $operator->getDenFirma(); ?>
</div>
<div class="panel-body locatii">
    <div style="min-height : 5px"></div>
    <?php
    /** @var AparatEntity $aparat */
    foreach ($aparate as $aparat) {
        ?>
        <div class="container-fluid">


            <div class="row">

                <div class="col-md-12">

                    <div class="panel panel-default">

                        <div class="panel-heading no-padding">

                            <div class="panel-title">

                                <div class="pull-left">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#" data-toggle="tab" class="padding5"><i
                                                    class="glyphicon glyphicon-print"></i>
                                                <strong><?php echo $aparat->getSeria(); ?></a></strong></li>
                                        <li><a href="#" data-toggle="tab" class="padding5"><i
                                                    class="fa fa-file-pdf-o"></i> Metrologie</a></li>
                                        <li><a href="#" data-toggle="tab" class="padding5"><i
                                                    class="fa fa-file-pdf-o"></i> Autorizatie</a></li>
                                        <li><a href="#" data-toggle="tab" class="padding5"><i class="fa fa-cloud-upload"
                                                                                              aria-hidden="true"></i>
                                                Incarca Poze</a></li>
                                        <li><a href="#" data-toggle="tab" class="padding5"><i class="fa fa-lightbulb-o"
                                                                                              aria-hidden="true"></i>
                                                Versiune Soft : <?php echo $aparat->getStareAparate()->getVerSoft() ?>
                                            </a></li>
                                        <li><a href="#" data-toggle="tab" class="padding5"><i class="fa fa-calendar"
                                                                                              aria-hidden="true"></i>
                                                Activ : <?php echo $aparat->getDtActivare() ?></a></li>
                                    </ul>
                                </div>

                                <div class="btn-group pull-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle padding5"
                                                data-toggle="dropdown">
                                            <i class="glyphicon glyphicon-th-list"></i> Logs
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                        </ul>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                            </div>

                        </div>
                        <?php $aparat->getContoriZilnici($appData->getAn(),$appData->getLuna()) ?>
                        <div class="panel-body padding2">
                            <table class="table table-responsive margin0">
                                <thead>
                                    <tr>
                                        <th>Index In</th>
                                        <th>Index Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo $aparat->getStareAparate()->getLastIdxInM() ?></td>
                                        <td><?php echo $aparat->getStareAparate()->getLastIdxOutM() ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?php
    }
    ?>
</div>