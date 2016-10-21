<?php
    
    require_once('../autoloader.php');
    // error_reporting(E_ALL);
    $_SESSION['locatii_tip'] = 'T';
    // require_once('../router.php');
    require_once "../includes/class.db.php";
    require_once "../includes/class.databFull.php";
    
    $databFull = new databFull(DOMAIN, DB_USER, DB_PASS, DB_HOST, DB_DATABASE, array());

    $_post = $databFull->sanitize($_POST);
    $luna = (isset($_POST['luna'])) ? intval($_post['luna']) : date('n');
    $an = (isset($_POST['an'])) ? intval($_post['luna']) : date('Y');
  
    $idresp = intval($_POST['idresp']);
    $operator = intval($_POST['operator']);
    // $id_locatie = $session->getIdLocatie();

    $row_resp = $databFull->getResponsabiliLocatiiAparate($an.'-'.$luna.'-01', $an.'-'.$luna.'-31', $idresp );
    $responsabil = $row_resp[0];

?>

    <strong><span class="glyphicon  glyphicon glyphicon-user"></span><?php echo $responsabil->nick; ?> </strong>
    <italic style="display:block;">
<?php
        if ($operator == 1) {
            echo 'A' . '(' . $responsabil->locatiiAmpera. 'L / ' . $responsabil->aparateAmpera . 'A / ' . $responsabil->depozitAmpera . 'AD ) P(0/0)';
        } elseif ($operator == 2) {
            echo 'R' . '(' . $responsabil->locatiiRedlong. 'L / ' . $responsabil->aparateRedlong . 'A / ' . $responsabil->depozitRedlong . 'AD ) P(0/0)';
        } else {
            echo 'T' . '(' . $responsabil->totalLocatii. 'L / ' . $responsabil->totalAparate . 'A / ' . $responsabil->totalDepozitAparate . 'AD ) P(0/0)';
        }
?>
    </italic>
    <span style="display: inline-block;width: 68%;">
        <input type="text" name="locatie" class="form-control" placeholder="Numele Locatiei"  id="locatieAuto"/>
    </span>
    <span style='display: inline-block;width :32%; float:right; '>
        <a  data-type="culoareAparat"
            data-sort="DESC"
            data-resp="<?php echo $idresp;  ?>"
            data-operator="<?php echo $operator; ?>" 
            class="locatii-sort">
                <img  src='css/images/green_light.png' style='width:20px; height: 20px;' />
        </a>
        <a  data-type="culoareAparat"
            data-sort="ASC"
            data-resp="<?php echo $idresp;  ?>"
            data-operator="<?php echo $operator; ?>" 
            class="locatii-sort">
                <img  src='css/images/red_light.png' style='width:20px; height: 20px;'/>
        </a>

        <?php if ($locatiiCuErori != 0) { ?><a
            href="?id_resp=<?php echo $idresp;  ?>&operator=<?php echo $operator; ?>&type=error&sort=DESC" >
                <img src='css/images/triangle_red.png' style='width:20px; height: 20px;'/>
            </a>
            <?php
            echo $locatiiCuErori;
        }
        ?>
    </span>
    <input type="hidden" id="id_pers" value="<?php echo $idresp;?>"/>