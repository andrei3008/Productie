<?php
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');
    $page = new PageClass();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 STRICT//EN" "DTD/xhtml1-strict.dtd">
<link href="style.css" rel="stylesheet" type="text/css">
<meta charset="UTF-8">
<head><title>SC AMPERA SRL</title></head>
<html>
<body><br/>
<?php
    if(isset($_POST['submit'])){
        $post = $db->sanitizePost($_POST);
        foreach($post as $key => $value){
            $_SESSION[$key] = $value;
        }
    }
?>
<div align="center">
    <div id="menu">
        <ul>
            <li><a href="index.php">Status</a></li>
            <li><a href="game.php">Aparat</a></li>
            <li><a href="time.php">Timp</a></li>
            <li class='active'>Server</li>
            <li><a href="record.php">Record</a></li>
        </ul>
    </div>
    <br/>

    <form name="CfgAparat" method="POST" action="server.php">
        <div id="content_cfg">
            <div id="Tbl_02">
                <table>
                    <colgroup>
                        <col style="width:30%">
                        <col style="width:50%">
                        <col style="width:20%">
                    </colgroup>
                    <tr>
                        <th colspan="3" align="center">Configurare Server</th>
                    </tr>
                    <tr>
                        <th>WebServer :</th>
                        <td><input name="NameServer0" type="text" value=""/></td>
                        <td>(Max. 64 Caractere)</td>
                    </tr>
                    <tr>
                        <th>Host Name :</th>
                        <td><input class="InHost" name="HostName" type="text" value=""/></td>
                        <td>(Max. 15 Caractere)</td>
                    </tr>
                    <tr>
                        <th>User Name :</th>
                        <td><input class="InHost" name="UserName" type="text" value=""/></td>
                        <td>(Max. 15 Caractere)</td>
                    </tr>
                    <tr>
                        <th>Password :</th>
                        <td><input class="InHost" name="Password" type="password" value=""/></td>
                        <td>(Max. 15 Caractere)</td>
                    </tr>
                    <tr>
                        <th>DHCP Client :</th>
                        <td><input class="left" type="checkbox" name="DHCP" onclick="EnDHCP()" ~config_dhcp~/>On / Off
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>IP Address :</th>
                        <td><input class="InHost" type="text" name="MyIP" value="" ~EnDHCP~/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Submask :</th>
                        <td><input class="InHost" type="text" name="MyMask" value="" ~EnDHCP~/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Gateway :</th>
                        <td><input class="InHost" type="text" name="MyGateway" value="" ~EnDHCP~/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Primary DNS :</th>
                        <td><input class="InHost" type="text" name="MyPriDNS" value="" ~EnDHCP~/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Secondary DNS :</th>
                        <td><input class="InHost" type="text" name="MySecDNS" value="" ~EnDHCP~/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>MAC :</th>
                        <td>~config_mac~</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"><input type="submit"  name="submit" value="Salvare Configurare" class="BtnCfg"/></td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div>
<div id="footer">Copyright &copy; 2015 SC AMPERA SRL</div>
<script>
    function EnDHCP() {
        e = document.forms.CfgAparat;
        st = e.DHCP.checked;
        e.MyIP.disabled = st;
        e.MyMask.disabled = st;
        e.MyGateway.disabled = st;
        e.MyPriDNS.disabled = st;
        e.MySecDNS.disabled = st;
    }
</script>
</body>
</html>