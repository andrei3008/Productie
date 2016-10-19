<?php
    require_once "../autoloader.php";
    require_once "../includes/class.db.php";
    $datab = new datab('', DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $appSettings = new SessionClass();
    $db = new DataConnection();

    $locatiiMapper = new LocatiiMaper($db, $appSettings);

    $locatie = $locatiiMapper->getCurrentLocation();
    $macsMapper = new MacPicMapper($db, $appSettings);
    $stareAparateMapper = new StareAparateMapper($db, $appSettings);

    $aparate = $locatie->getAparateActive();
    // print_r($locatie);
    $macuri = $macsMapper->getMacs2();

    if (count($macuri) > 0) {
?>
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true">Atentie!!! Au fost montate <strong><?php echo count($macuri); ?></strong> picuri:</span>
            <span class="sr-only"></span>
            <a href="#" class="btn btn-sm btn-primary" id="button-vezi-tabel">Vezi mai multe</a> 
            <button class="btn btn-sm btn-primary" id="buton-sters">Sterge toate macurile de test</button>
        </div>
<?php 
    } 
?>  
<script type="text/javascript">var domain = "<?php echo DOMAIN; ?>";</script>
<script type="text/javascript" src="<?php echo DOMAIN; ?>/js/macuri.js"></script>