<?php
require_once('classes/SessionClass.php');
require_once 'includes/dbConnect.php';
$session = new SessionClass();
?>

<?php
$luna = $_POST['luna'];
$an = $_POST['an'];
$idLocatie = $_POST['loc'];
if ($luna == 2) {
    if (($an % 4 == 0 && $an % 100 != 0) OR $an % 400 == 0) {
        $zile = 29;
    } else {
        $zile = 28;
    }
} else {
    switch ($luna) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12: $zile = 31;
            break;
        case 4:
        case 6:
        case 9:
        case 11:$zile = 30;
            break;
        default:
            break;
    }
}
?>
<ul class="pagination pagination-sm">
    <?php
    for ($i = 0; $i < $zile; $i++) {
        $azi = date('Y-m-d');
        $data2 = $an.'-'.$luna.'-'.$i+1;
        $dataDeVerificat = new DateTime($data2);
        ?>
    <li <?php echo (date('d') < ($i+1) AND $luna == date('n')) ? 'class="disabled"':''; ?>><a href="?id=<?php echo $idLocatie; ?>&zi=<?php echo $i+1; ?>&luna=<?php echo $luna ?>&an=<?php echo $an ?>"><?php echo $i + 1; ?><br/>
                <?php
                $ziSaptamana = date('N', strtotime($an . '-' . $luna . '-' . ($i + 1)));
                switch ($ziSaptamana) {
                    case '1': echo 'L';
                        break;
                    case '2': echo 'Ma';
                        break;
                    case '3': echo 'Mi';
                        break;
                    case '4': echo 'J';
                        break;
                    case '5': echo 'V';
                        break;
                    case '6': echo 'S';
                        break;
                    case '7': echo 'D';
                        break;
                    default:
                        break;
                }
                ?></a></li>
    <?php
}
?>
</ul>