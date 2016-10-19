<?php
require_once('classes/SessionClass.php');
require_once('includes/dbFull.php');
$session = new SessionClass();
$get = $db->sanitizePost($_GET);
?>
<OBJECT classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921"
     codebase="http://downloads.videolan.org/pub/videolan/vlc/latest/win32/axvlc.cab"
     width="640" height="480" id="vlc" events="True">
   <param name="src" value="rtsp://192.168.1.10:554/user=admin&password=&channel=<?php echo $get['channel'] ?>&stream=" />
   <param name="ShowDisplay" value="True" />
   <param name="AutoLoop" value="False" />
   <param name="AutoPlay" value="True" />
   <embed id="vlcEmb"  type="application/x-google-vlc-plugin" version="VideoLAN.VLCPlugin.2" autoplay="yes" loop="no" width="640" height="480"
     target="rtsp://192.168.1.10:554/user=admin&password=&channel=1&stream="></embed>
</OBJECT>