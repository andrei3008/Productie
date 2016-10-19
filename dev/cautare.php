<?php
require_once('includes/dbConnect.php');
function printDialog($tip,$mesaj) {
    return "<div class='alert alert-$tip' id='dispare'>
                <strong>Atentie!</strong><br/>$mesaj.
           </div>";
}
?>

<html>
<head>
    <title>Cautare serii</title>
    <?php require_once('includes/header.php'); ?>
</head>
<body>
<div class="col-md-12">
    <a href="main.php" class="btn btn-md btn-primary">Inapoi</a>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="serii" placeholder="Introduceti serrile delimitate de ',' (punct si virgula)"
                       class="form form-control"/>
                <input type="file" name="myFile" class="file"/>
                <button type="submit" name="submit" class="form form-control btn btn-md btn-primary">Cauta Serii
                </button>
            </form>
        </div>
        <div class="panel-body">
            <?php
            if (isset($_POST['submit'])) {
                if ($_POST['serii'] != '') {
                    $query = "SELECT locatii.idlocatie,locatii.adresa, aparate.idAparat, aparate.seria FROM brunersrl.aparate INNER JOIN brunersrl.locatii ON locatii.idLocatie = aparate.idlocatie WHERE ";
                    $valueCurat = mysqli_real_escape_string($con, $_POST['serii']);
                    $valueCurat = str_replace(' ', '', $valueCurat);
                    $serii = explode(',', $valueCurat);
                    $i = 0;
                    if (isset($serii)) {
                        foreach ($serii as $serie) {
                            $query .= " replace(aparate.seria,' ','')='$serie' ";
                            if ($i < count($serii) - 1) {
                                $query .= ' OR ';
                            }
                            $i++;
                        }
                        $query .= " AND aparate.dtBlocare='1000-01-01' ORDER BY locatii.adresa ASC";
                        $result = $con->query($query);
                        $j = 1;
                        $i = 1;
                        $aparate = [];
                        while ($aparat = $result->fetch_object()) {
                            $aparate[$aparat->idlocatie][] = $aparat;
                        }
                        foreach ($aparate as $key => $value) {
                            ?>
                            <h3><?php echo $j . '. ' . $value[0]->adresa;
                                $j++; ?></h3>
                            <table class="table table-bordered" style="background-color: lightgrey">
                            <thead>
                            <tr>
                                <th>Nr. Crt.</th>
                                <th>Seria</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($value as $ap) {
                                ?>
                                <tr>
                                    <td><?php echo $i;
                                        $i++; ?></td>
                                    <td><?php echo $ap->seria; ?></td>
                                </tr>
                                <?php
                            }
                            ?></tbody></table><?php
                        }
                    }
                } elseif (isset($_FILES)) {
                    define("UPLOAD_DIR", "../text/");

                    if (!empty($_FILES["myFile"])) {
                        $myFile = $_FILES["myFile"];

                        if ($myFile["error"] !== UPLOAD_ERR_OK) {
                            echo "<p>An error occurred.</p>";
                            exit;
                        }

                        // ensure a safe filename
                        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

                        // don't overwrite an existing file
                        $i = 0;
                        $parts = pathinfo($name);
                        if($parts['extension']!='.txt') {
                            while (file_exists(UPLOAD_DIR . $name)) {
                                $i++;
                                $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
                            }

                            // preserve file from temporary directory
                            $success = move_uploaded_file($myFile["tmp_name"],
                                UPLOAD_DIR . $name);
                            if (!$success) {
                                echo "<p>Unable to save file.</p>";
                                exit;
                            }

                            // set proper permissions on the new file
                            chmod(UPLOAD_DIR . $name, 0644);
                        }else{
                            echo "Va rugam sa introduceti numai fisiere formatul txt";
                            exit;
                        }
                        $handle = fopen('../text/'.$name, 'a+');
                        $parti = [];
                        while ($rand = fgets($handle)) {
                            $parti = preg_split('/[^a-z0-9.\']+/i', $rand);
                            foreach($parti as $parte){
                                $partii[] = $parte;
                            }
                        }
                        $serii = [];
                        $errors = [];
                        $lungimeArray = count($partii);
                        for ($i = 0; $i < $lungimeArray; $i++) {
                            if(!is_numeric($partii[$i]) AND strlen($partii[$i])==2){
                                $serii[] = strtoupper($partii[$i]).$partii[$i+1];
                                $i++;
                            }elseif(!is_numeric($partii[$i]) AND strlen($partii[$i])==6){
                                $serii[] = strtoupper($partii[$i]);
                            }elseif(!is_numeric($partii[$i]) AND strlen($partii[$i]!=6 OR strlen($partii[$i])!=2)){
                                $errors[]=$partii[$i];
                            }elseif(is_numeric($partii[$i]) AND strlen($partii[$i])==6 or strlen($partii[$i])==7){
                                $serii[]=$partii[$i];
                            }else{
                                $errors[] = $partii[$i];
                            }
                        }
                        $j = 1;
                        foreach ($errors as $error) {
                            if($error != '')
                                echo printDialog('error',"Seria : " . $error.' este incorecta va rugam sa verificati.<br/>');
                        }
                        $query = "SELECT locatii.idlocatie,locatii.adresa, aparate.idAparat, aparate.seria FROM brunersrl.aparate INNER JOIN brunersrl.locatii ON locatii.idLocatie = aparate.idlocatie WHERE ";
                        $i=0;
                        foreach ($serii as $serie) {
                            $query .= " replace(aparate.seria,' ','')='$serie' ";
                            if ($i < count($serii) - 1) {
                                $query .= ' OR ';
                            }
                            $i++;
                        }
                        $query .= " AND aparate.dtBlocare='1000-01-01' ORDER BY locatii.adresa ASC";
                        echo $query;
                        $result = $con->query($query);
                        $j = 1;
                        $i = 1;
                        $aparate = [];
                        while ($aparat = $result->fetch_object()) {
                            $aparate[$aparat->idlocatie][] = $aparat;
                        }
                        foreach ($aparate as $key => $value) {
                            ?>
                            <h3><?php echo $j . '. ' . $value[0]->adresa;
                                $j++; ?></h3>
                            <table class="table table-bordered" style="background-color: lightgrey">
                            <thead>
                            <tr>
                                <th>Nr. Crt.</th>
                                <th>Seria</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($value as $ap) {
                                ?>
                                <tr>
                                    <td><?php echo $i;
                                        $i++; ?></td>
                                    <td><?php echo $ap->seria; ?></td>
                                </tr>
                                <?php
                            }
                            ?></tbody></table><?php
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
