<?php
require_once('../includes/dbFull.php');
require_once('../classes/PageClass.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 STRICT//EN" "DTD/xhtml1-strict.dtd">
<link href="style.css" rel="stylesheet" type="text/css">
<meta charset="UTF-8">
<head><title>SC AMPERA SRL</title></head>
<html>
<body><br/>

<div align="center">
    <div id="menu">
        <ul>
            <li class='active'>Status</li>
            <li><a href="game.php">Aparat</a></li>
            <li><a href="time.php">Timp</a></li>
            <li><a href="server.php">Server</a></li>
            <li><a href="record.php">Record</a></li>
        </ul>
    </div>
    <br/>

    <div id="content">
        <div id="Tbl_01">
            <table border="1">
                <colgroup>
                    <col style="width:30%">
                    <col style="width:20%">
                    <col style="width:30%">
                    <col style="width:20%">
                </colgroup>
                <tr>
                    <th colspan="2" align="center">Pachet Trimis</th>
                    <th colspan="2" align="center">Pachet Raspuns</th>
                </tr>
                <tr>
                    <th>Data / Timp</th>
                    <td>~vDateTime~</td>
                    <th>Data Server</th>
                    <td>~vDateTimeSRcv~</td>
                </tr>
                <tr>
                    <th>Serie Aparat</th>
                    <td>~vSerieAp~</td>
                    <th>Data Retrimitere</th>
                    <td>~vDateTimePRcv~</td>
                </tr>
                <tr>
                    <th>ID Operator</th>
                    <td>~vIdOperator~</td>
                    <th>Contor Intrare</th>
                    <td>~vIndexInERcv~</td>
                </tr>
                <tr>
                    <th>ID Locatie</th>
                    <td>~vIdLocatie~</td>
                    <th>Contor Iesire</th>
                    <td>~vIndexOutERcv~</td>
                </tr>
                <tr>
                    <th>ID Aparat</th>
                    <td>~vIdAparat~</td>
                    <th>Comanda Joc</th>
                    <td>~vComandaJocRcv~</td>
                </tr>
                <tr>
                    <th>Stare Aparat</th>
                    <td>~vStareAp~</td>
                    <th>CRC</th>
                    <td>~vCRCRcv~</td>
                </tr>
                <tr>
                    <th>Contor Mecanic IN</th>
                    <td>~vIndexInM~</td>
                </tr>
                <tr>
                    <th>Contor Mecanic OUT</th>
                    <td>~vIndexOutM~</td>
                    <th colspan="2" align="center">Informatii Aparat</th>
                </tr>
                <tr>
                    <th>Contor Mecanic TotalBet</th>
                    <td>~vIndexBetM~</td>
                    <th>Aparat</th>
                    <td>~vApPowerOn~</td>
                </tr>
                <tr>
                    <th>Tensiune Sursa 12v</th>
                    <td>~vU12v~</td>
                    <th>Usa Aparat</th>
                    <td>~vUsaAp~</td>
                </tr>
                <tr>
                    <th>Tensiune Sursa 5v</th>
                    <td>~vU5v~</td>
                    <th>Usa UC Aparat</th>
                    <td>~vUsaUC~</td>
                </tr>
                <tr>
                    <th>Tensiune PCB 3.3v</th>
                    <td>~vU33v~</td>
                    <th>SAS</th>
                    <td>~vSASOn~</td>
                </tr>
                <tr>
                    <th>Tensiune PCB Baterie</th>
                    <td>~vUBat~</td>
                    <th>Serie Sistem</th>
                    <td colspan="2">~IdPcb~</td>
                </tr>
                <tr>
                    <th>Contor Electronic IN</th>
                    <td>~vIndexInE~</td>
                </tr>
                <tr>
                    <th>Contor Electronic OUT</th>
                    <td>~vIndexOutE~</td>
                </tr>
                <tr>
                    <th>Contor Electronic TotalBet</th>
                    <td>~vIndexBetE~</td>
                    <th colspan="2" align="center">Informatii Contori Electronici (SAS)</th>
                </tr>
                <tr>
                    <th>CREDIT</th>
                    <td>~vCreditE~</td>
                    <th>Adresa SAS</th>
                    <td>~vAddrSas~</td>
                </tr>
                <tr>
                    <th>BET / Joc</th>
                    <td>~vBetE~</td>
                    <th>Versiune SAS</th>
                    <td>~vVerSas~</td>
                </tr>
                <tr>
                    <th>Tip Joc</th>
                    <td>~vNumarJocE~</td>
                    <th>Games Played</th>
                    <td>~vGamesPlayed~</td>
                </tr>
                <tr>
                    <th>Pornire Aparat</th>
                    <td>~vPowerUpE~</td>
                    <th>Games Won</th>
                    <td>~vGamesWon~</td>
                </tr>
                <tr>
                    <th>Versiune FW</th>
                    <td>~vVersiuneFW~</td>
                    <th>Games Lost</th>
                    <td>~vGamesLost~</td>
                </tr>
                <tr>
                    <th>CRC</th>
                    <td>~vCRC~</td>
                    <th>Total Won</th>
                    <td>~vTotalWon~</td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Salveaza Configuratie"/></td>
                    <td colspan="2"><input type="submit" name="submit" value="Anuleaza Configuratie"/></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="footer">Copyright &copy; 2015 SC AMPERA SRL</div>
</body>
</html>