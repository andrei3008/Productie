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
                <li><a href="<?php echo DOMAIN; ?>/logout.php">Logout</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
