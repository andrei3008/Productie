<nav class="navbar navbar-default navbar-fixed-top device-fixed-height" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Brand</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php echo DOMAIN; ?>/main.php">Prima Pagina</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Rapoarte <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo DOMAIN; ?>/filtruresponsabil.php">Aparate / Responsabil</a></li>
                        <li><a href="<?php echo DOMAIN; ?>/depozit.php">Evidenta Depozite</a></li>
                        <li><a href="<?php echo DOMAIN; ?>/logsSql.php">Loguri Inserari / Modificari</a></li>
                        <li><a href='<?php echo DOMAIN; ?>/expirare.php'>Expirare Metrologii / Avertizari</a></li>
                        <li><a href='<?php echo DOMAIN; ?>/auditPic.php'>Audit PIC</a></li>
                        <li><a href='<?php echo DOMAIN; ?>/aparatePic.php'>Lista Aparate cu Pic</a></li>
                        <li><a href="<?php echo DOMAIN; ?>/rapoarte/transferuri.php">Lista Transferuri</a></li>
                        <li><a href="<?php echo DOMAIN; ?>/rapoarte/locatiiInchise.php">Locatii Inchise / Deschise</a>
                        </li>
                        <li><a href="<?php echo DOMAIN; ?>/rapoarte/centralizator.php">Centralizator</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Editari <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo DOMAIN; ?>/responsabili.php">Editare Responsabili</a></li>
                        <li><a href="<?php echo DOMAIN; ?>/users.php">Utilizatori</a></li>
                        <li><a href="<?php echo DOMAIN; ?>/interfataPic/game.php">Config Aparate</a></li>
                        <li><a href="<?php echo DOMAIN; ?>/addElementInventar">Adauga Element de inventar</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo DOMAIN; ?>/cautare.php">Cautare</a></li>
                <li><a href="<?php echo DOMAIN; ?>/cautare-ipmac.php">Cautare IP/MAC</a></li>
                
                <li><a href="<?php echo DOMAIN; ?>/logs.php">Accesari Minister</a></li>
                <li><a href="<?php echo DOMAIN; ?>/contracte.php">Contracte</a></li>
                <li><a href="<?php echo DOMAIN; ?>/pariuri/index.php">Pariuri</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <fieldset class="dataMeniu">
                        <label>
                            <select name="an" id="an" class="form-control" onchange="schimbaAn(this)">
                                <option value="<?php echo $appSettings->getAn() ?>"><?php echo $appSettings->getAn() ?></option>
                                <?php
                                for ($z = 2015; $z < 2020; $z++) {
                                    ?>
                                    <option value="<?php echo $z ?>"><?php echo $z; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </label>
                    </fieldset>
                </li>
                <li>
                    <fieldset class="dataMeniu">
                        <label>
                            <select name="luna" id="luna" class="form-control" onchange="schimbaLuna(this)">
                                <option value="<?php echo $appSettings->getLuna() ?>"><?php echo $appSettings->getLunaInRomana($appSettings->getLuna()) ?></option>
                                <?php
                                for ($i = 1; $i < 13; $i++) {
                                    if ($i != $appSettings->getLuna()) {
                                        ?>
                                        <option
                                            value="<?php echo $i; ?>"><?php echo $page->getLuna($i) ?></option>
                                        <?php
                                    }
                                }
                                ?>

                            </select>
                        </label>
                    </fieldset>
                </li>
                <li><label><input id="searchField" type="text" name="search" placeholder="Cautare" class="form-control"/></label></li>
                <script type="text/javascript">
                    document.getElementById('searchField').onkeydown = function (e) {
                        if (e.keyCode == 13) {
                            var searchString = $("#searchField").val();
                            $.ajax({
                                url: DOMAIN + "/router.php",
                                type: "POST",
                                data: {
                                    'search': searchString
                                },
                                success: function (response) {
                                    location.reload();
//                                    console.log(response);
                                }
                            });
                        }
                    };
                </script>
                <li><a href="<?php echo DOMAIN; ?>/logout.php"><i class="glyphicon glyphicon-off"></i></a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
