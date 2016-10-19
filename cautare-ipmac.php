<?php
    require_once "autoloader.php";
    require_once 'classes/SessionClass.php';
    require_once "includes/class.db.php";
    require_once "includes/class.databFull.php";
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $session =  $appSettings = new SessionClass();
    $page = new PageClass();
    $page->checkLogin($session->getUsername(), $session->getOperator());

    // $thiss = true;
    // $that = false;

    // var_dump($truthiness = $thiss and $that);
?>
<!DOCTYPE>
<html>
    <head>
        <title>Cautare MAC/IP</title>
        <?php require_once('includes/header.php'); ?>
        <link rel="stylesheet" href="js/dataTable/dataTable.css">
        <script src="js/dataTable/dataTables.js"></script>
    </head>
    <body>
        <?php require_once('includes/menu.php'); ?>
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Parametrii de cautare :</div>
                    <div class="panel-body">
                        <form method="POST" class="form-group">
                            <div class='row'>
                                <div class="col-md-6">
                                    <fieldset>
                                        <label for="serie">MAC</label>
                                        <input type="text" name="mac" placeholder="Cauta dupa MAC" class="form-control" id="mac"/>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <label for="serie">IP</label>
                                        <input type="text" name="ip" placeholder="Cauta dupa IP" class="form-control" id="ip"/>
                                    </fieldset>
                                </div>
                            </div>

                            <div class='row' style='margin-top: 20px'>
                                <div class="col-md-6">
                                    <fieldset>
                                        <label for="serie">Id Aparat</label>
                                        <input type="text" name="idAparat" placeholder="Cauta dupa Id-ul Aparatului" class="form-control" id="idAparat"/>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <label for="serie">Judet</label>
                                        <input type="text" name="judet" placeholder="Cauta dupa Judetul Locatiei" class="form-control" id="judet"/>
                                    </fieldset>
                                </div>
                            </div>

                            <div class='row' style='margin-top: 20px'>
                                <div class="col-md-6">
                                    <fieldset>
                                        <label for="serie">Serie Aparat</label>
                                        <input type="text" name="seria" placeholder="Cauta dupa Seria Aparatului" class="form-control" id="seria"/>
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset>
                                        <label for="serie">Adresa</label>
                                        <input type="text" name="adresa" placeholder="Cauta dupa Adresa Locatiei" class="form-control" id="adresa"/>
                                    </fieldset>
                                </div>
                            </div>
                           

                            <div class='row' style='margin-top: 20px'>
                                <div class="col-md-6">
                                    <fieldset>
                                        <label for="serie">Responsabil</label>
                                        <input type="text" name="responsabil" placeholder="Cauta dupa Nick-ul Responsabilului" class="form-control" id="responsabil"/>
                                    </fieldset>
                                </div>
                            </div>

                             <div class='row' style='margin-top: 20px'>
                                <div class="col-md-12 margined-top">
                                    <input type="button" name="search" value="Cauta" class="btn btn-primary btn-md right" id="search"/>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="loading"><img src="css/AjaxLoader.gif" /></div>
                    <div class="panel-heading">Rezultate Cautare</div>
                    <div class="panel-body table-responsive" id="search-responsive"></div>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    $(document).ready(function() {
        $.extend( true, $.fn.dataTable.defaults, {
            "bJQueryUI": true,
            "bAutoWidth": false,
            "sPaginationType": "full_numbers",
            "sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
            "oLanguage": {
                "sLengthMenu": "<span>Show entries:</span> _MENU_",
                "oPaginate": { "sFirst": "First", "sLast": "Last", "sNext": ">", "sPrevious": "<" }
            }
        });
        $('#search').on('click', function() {
            $('.loading').show();
            var ip = $('#ip').val();
            var mac = $('#mac').val();
            var idAparat = $('#idAparat').val();
            var seria = $('#seria').val();
            var judet = $('#judet').val();
            var adresa = $('#adresa').val();
            var responsabil = $('#responsabil').val();
            $.ajax({
                type: "POST",
                url: 'ajax/cautareMacIp.php',
                data: {
                    'ip': ip,
                    'mac': mac,
                    'idAparat': idAparat,
                    'seria': seria,
                    'judet': judet,
                    'adresa': adresa,
                    'responsabil': responsabil,
                },
                success: function (result) {
                    $('#search-responsive').html(result);
                    oDataTable = $('#dataTables').dataTable();
                    $('.loading').hide();
                    
                }
            });
        })
    });
</script>