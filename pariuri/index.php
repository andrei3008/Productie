<?php

require_once '../autoloader.php';

$appSettings = $session = new SessionClass();

$page = new PageClass();
$page->checkLogin($session->getUsername(), $session->getOperator());

$db = new dbFull(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);
$database = new DataConnection();

$personalMapper = new PersonalMapper($database,$session);

$personalPariuri = $personalMapper->getColaboratori($database);

?>
<html lang="ro">
<head>
    <title>Locatii Pariuri</title>
    <?php require_once "../includes/header.php"; ?>
</head>
<body>
<?php require_once '../includes/menu.php' ?>
<?php
/** @var PersonalEntity $personal */
foreach ($personalPariuri as $personal) {
    $locatiiPariuri = $personal->getAgentiiPariuri($database);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            Locatiile lui : <?php echo $personal->getNick(); ?>
            <span class="width-100 inline">&nbsp;</span>
            Total Locatii Pariuri : <?php echo $personal->getNumarAgentiiPariuri(); ?>
            <span class="width-100 inline">&nbsp;</span>
            <button class="btn btn-primary buton-afiseaza" data-id="<?php echo $personal->getIdpers(); ?>">Afiseaza
                Locatii
            </button>
            <form method="POST" action="genereazaPDF.php" target="_blank" class="inline">
                <button class="btn btn-info" value="<?php echo $personal->getIdpers() ?>" name="responsabil">Printeaza Locatii
                    Colaborator
                </button>
            </form>
        </div>
        <div class="panel-body" style="display: none;" id="body-<?php echo $personal->getIdpers(); ?>">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Nr Crt.</th>
                    <th>Denumire</th>
                    <th>Analitic</th>
                    <th>Adresa</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $i = 1;
                /** @var AgentiiEntity $locatie */
                foreach ($locatiiPariuri as $locatie) {
                    ?>
                    <tr>
                        <td><?php echo $i;
                            $i++; ?></td>
                        <td><?php echo $locatie->getDenumire(); ?></td>
                        <td><?php echo $locatie->getAnalitic(); ?></td>
                        <td><?php echo $locatie->getAdresa(); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $(".buton-afiseaza").click(function (event) {
            event.preventDefault();
            var info = $(this).attr("data-id");
            $('#body-' + info).toggle(300);
        });
    });
</script>
</body>
</html>
