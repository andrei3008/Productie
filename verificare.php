<?php
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
$session = new SessionClass();
?>
<html>
    <head>
        <?php require_once('includes/header.php'); ?>
    </head>

<?php

$get = $db->sanitizePost($_GET);

?>
<form method="POST" action="http://86.122.183.194" id="verificare">
    <input type="hidden" name="infoPc" value="<?php echo $get['session'] ?>"/>
</form>
<script>
    $(document).ready(function(){
        $('#verificare').submit();
    })
</script>
</html>