<?php
    require_once "autoloader.php";
    // error_reporting(E_ALL);
    require_once "includes/class.db.php";
    require_once "includes/class.databFull.php";
    require_once('classes/PageClass.php');
    require_once('classes/SessionClass.php');
    $session =  $appSettings = new SessionClass();
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());
    $page = new PageClass();
    $get = $databFull->sanitize($_GET);
    $luna = $get['luna'];
    $an = $get['an'];
    $idAparat = $get['idAparat'];
    $aparat = $databFull->getAparatInfo($idAparat);
    $idlocatie = $aparat->idlocatie;
?>
    <!DOCTYPE>
    <html>

    <head>
        <title>Contori</title>
        <?php require_once('includes/header.php'); ?>
    </head>

    <body>
        <?php require_once('includes/menu.php'); ?>
		
			<div class="modal fade" tabindex="-1" role="dialog" id="modal_contori_reset">
				<div class="modal-dialog modal-lg" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Reset Contori Electronici <strong>Azi: <em><?php echo $azi = date('d.m.Y');?></em></strong></h4>
						</div>
						<div class="modal-body">
							<div class="container">
                                <form class="form-inline">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="reset_indexIn">IndexIn:</label>
                                            <input type="text" class="form-control col-xs-2" id="reset_indexIn" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="reset_indexOut">IndexOut:</label>
                                            <input type="text" class="form-control col-xs-2" id="reset_indexOut" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="save_reset">&nbsp; &nbsp;</label>
                                        <input type="hidden" name="reset_zi" id="reset_zi" value="<?php echo $azi; ?>"/>
                                        <input type="hidden" name="idAparat" id="idAparat" value="<?php echo $idAparat; ?>"/>
                                        <input type="hidden" name="idlocatie" id="idlocatie" value="<?php echo $idlocatie; ?>"/>
                                        <button type="button" class="btn btn-default" id="save_reset">Salveaza</button>
                                    </div>
                                </form>
                                <div id="reset_response"></div>
                            </div>
                        </div>
                        <div class="modal-header">
                            <h4 class="modal-title">Setare Contori Electronici <strong>Ieri: <em><?php echo $ieri = date('d.m.Y', strtotime($azi.' -1 day'));?></em></strong></h4>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <form class="form-inline">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="set_indexIn_ieri">IndexIn:</label>
                                            <input type="text" class="form-control col-xs-2" id="set_indexIn_ieri" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="set_indexOut_ieri">IndexOut:</label>
                                            <input type="text" class="form-control col-xs-2" id="set_indexOut_ieri" placeholder="0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="save_set">&nbsp; &nbsp;</label>
                                        <input type="hidden" name="set_ieri" id="set_ieri" value="<?php echo $ieri; ?>"/>
                                        <button type="button" class="btn btn-default" id="save_set">Salveaza</button>
                                    </div>
                                </form>
                                <div id="set_response"></div>
                            </div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>


            <div class="col-md-12" id="">
                <button type="button" class="btn btn-primary dropdown-toggle" id="btn-reload"> RELOAD </button>
            </div>

            <div class="col-md-12" id="table_content">

            </div>

            <script type="text/javascript" src="<?php echo DOMAIN; ?>/js/contori.js"></script>
            <script type="text/javascript">
                $(document).ready(function () {
                    contori.reset_tabel(<?php echo $luna;?>, <?php echo $an;?>, <?php echo $idAparat;?>);
                    $(document).on('change', '#an', function () {
                        var an = $('#an').val();
                        var luna = $('#luna').val();
                        var idAparat = <?php echo $get['idAparat']; ?>;
                        window.location.href = '?idAparat=' + idAparat + '&luna=' + luna + '&an=' + an;
                    });
                    $(document).on('change', '#luna', function () {
                        var an = $('#an').val();
                        var luna = $('#luna').val();
                        var idAparat = <?php echo $get['idAparat']; ?>;
                        window.location.href = '?idAparat=' + idAparat + '&luna=' + luna + '&an=' + an;
                    });
                    $("#btn-reload").on('click', function () {
                        var an = $('#an').val();
                        var luna = $('#luna').val();
                        var idAparat = <?php echo $get['idAparat']; ?>;
                        contori.reset_tabel(luna, an, idAparat)
                    });
                    
                    
                });

            </script>
    </body>

    </html>