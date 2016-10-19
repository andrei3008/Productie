<?php
require_once('../classes/SessionClass.php');
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
$session = new SessionClass();
$session->exchangeArray($_SESSION);
/*
 * Helper Class
 */
$page = new PageClass();

/**
 * Verifica daca cererea este corecta
 */
$page->checkLogin($_SESSION['username'], $_SESSION['operator']);

/**
 * Pregateste datele pentru prelucrare
 */
$post = $db->sanitizePost($_POST);

/**
 * Preia expirarile pe luna respectiva
 */
$expirari = $db->getAvertizariByLuna($post['tip'], $post['an'], $post['luna'], $post['operator']);


/**
 * generarea tabelului
 */
?>
<div class="pull-right col-md-12"><h4 class="inline">In luna <?php echo $page->getLuna($post['luna']) ?> expira <?php echo count($expirari) ?> pe firma
    <?php echo ($post['tip'] == "dtExpMetrologie") ? "metrologii" : "autorizari"; ?> <?php echo ($post['operator']==1) ? "Ampera" : "Redlong" ?></h4>
    <form method="post" action="<?php echo DOMAIN."/generatoarePDF/expirari.php"; ?>" id="printform" class="inline">
        <label>
            <input type="hidden" name="an" value="<?php echo $post['an']; ?>">
        </label>
        <label>
            <input type="hidden" name="luna" value="<?php echo $post['luna']; ?>">
        </label>
        <label>
            <input type="hidden" name="operator" value="<?php echo $post['operator'] ?>">
        </label>
        <label>
            <input type="hidden" name="tip" value="<?php echo $post['tip']; ?>">
        </label>
        <button type="submit" name="submit" class="btn btn-primary btn-sm">PRINT</button>
    </form>
</div>
<table class="table table-responsive table-bordered">
    <thead>
    <tr>
        <th>Nr. Crt</th>
        <th>Responsabil</th>
        <th>Locatie</th>
        <th>Serie</th>
        <th>Tip</th>
        <th>Data Expirare Metrologie</th>
        <th>Data Expirare Autorizatie</th>
        <th>Metrologie</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    $responsabil = '';
    $locatie = '';
    foreach ($expirari as $expirare) {
        ?>
        <tr>
            <td><?php echo $i;
                $i++; ?></td>
            <td><?php
                if ($expirare->nick != $responsabil) {
                    echo $expirare->nick;
                    $responsabil = $expirare->nick;
                }
                ?></td>
            <td><?php
                if ($expirare->denumire != $locatie) {
                    echo $expirare->denumire;
                    $locatie = $expirare->denumire;
                }
                ?></td>
            <td><?php echo $expirare->seria; ?></td>
            <td><?php echo $expirare->tip; ?></td>
            <td><?php $dataMetrologie = new DateTime($expirare->dtExpMetrologie); echo $dataMetrologie->format("d M Y"); ?></td>
            <td><?php $dataAutorizatie = new DateTime($expirare->dtExpAutorizatie); echo $dataAutorizatie->format("d M Y"); ?></td>
            <td><a href="ftp://acte:acte77@rodiz.ro/metrologii/curente/<?php echo $expirare->seria ?>.pdf"
                   target="_new"
                   class="btn btn-sm btn-primary metrologii">Metrologie</a></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
<button class="btn btn-primary btn-sm" id="secondary_button">PRINT</button>
<script type="text/javascript">
    $(document).on("click","#secondary_button",function(event){
        event.preventDefault();
        document.getElementById("printform").submit.click();
    });
    $('.metrologii').on("click",function(event){
        event.preventDefault();
        var url = $(this).attr("href");
        window.open(url,"_blank");
    });
</script>