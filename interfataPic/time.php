<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 STRICT//EN" "DTD/xhtml1-strict.dtd">
<link href="style.css" rel="stylesheet" type="text/css"><meta charset="UTF-8">
<head><title>SC AMPERA SRL</title></head>
<html><body><br />
<div align="center" >
<div id="menu">
	<ul>
		<li><a href="index.php">Status</a></li>
		<li><a href="game.php">Aparat</a></li>
		<li class='active'>Timp</li>
		<li><a href="server.php">Server</a></li>
		<li><a href="record.php">Record</a></li>
	</ul>
</div>
<br />
<form name="CfgAparat" method="POST" action="time.php">
<div id="content_cfg">
<div id="Tbl_02">
<table>
	<colgroup>
		<col style="width:30%"></col>
		<col style="width:25%"></col>
		<col style="width:45%"></col>
	</colgroup>
	<tr><th colspan="3" align="center">Configurare Data si Timp</th></tr>
	<tr><th>Data Curenta :</th><td>~vDate~</td><td></td></tr>
	<tr><th>Ora Curenta :</th><td>~vTime~</td><td></td></tr>
	<tr><th>Server Sincronizare Timp :</th><td><select name="TimeServer">~vTimeServer~></select></td><td></td></tr>
	<tr><th>Timp Zona :</th><td><select name="TimeZone">~vTimeZone~</select></td><td></td></tr>
	<tr><th>Perioada Sincronizare Timp :</th><td><select name="TimePerioada">~vTimePerioada~</select></td><td></td></tr>
	<tr><td colspan="3"><input type="submit" value="Salvare Configurare" class="BtnCfg" /></td></tr>
</table>
</div></div>
</form>
</div>
<div id="footer">Copyright &copy; 2015 SC AMPERA SRL</div>
</body>
</html>