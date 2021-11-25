<?php
$item = array(
'https://clips-media-assets2.twitch.tv/AT-cm%7C823646048.mp4',
'https://clips-media-assets2.twitch.tv/39487666430-offset-880.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C847871382.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C854688046.mp4',
'https://clips-media-assets2.twitch.tv/39652665694-offset-9356.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C871062952.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C888969454.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C903104828.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C910837128.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C915889697.mp4',
'https://clips-media-assets2.twitch.tv/40319885454-offset-5652.mp4',
'https://clips-media-assets2.twitch.tv/40796892558-offset-10174.mp4',
'https://clips-media-assets2.twitch.tv/39963056637-offset-24106.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C987308002.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C988150047.mp4',
'https://clips-media-assets2.twitch.tv/40239638477-offset-632.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1018707980.mp4',
'https://clips-media-assets2.twitch.tv/41488202094-offset-542.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1150933332.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1161045542.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1177970064.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1182030969.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1194275516.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1199642065.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1216755565.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1216347268.mp4',
'https://clips-media-assets2.twitch.tv/42353242253-offset-672.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1219091773.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1221208662.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1221236002.mp4',
'https://clips-media-assets2.twitch.tv/39458341739-offset-6304.mp4',
'https://clips-media-assets2.twitch.tv/AT-cm%7C1289159158.mp4'
);


?>
<!html>
<html>
    <head>
        <title>Video Embedder</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script>
            function tbxkey(){
                if(event.keyCode==13){
                    document.getElementById("tbtn").click();
                }
            }
            function changeVidS(i){
                console.log(i.value);
                document.getElementById("outVideo").playbackRate = i.value;
            }
            function doAction(){
                var text = document.getElementById('tbx').value;
                var out = document.getElementById('output');
                if(text.endsWith(".jpg")){
                    text = text.replace("-preview-480x272.jpg", ".mp4");
                    document.getElementById('tbx').value = text;
                }
                out.innerHTML='<video id="outVideo" src="'+text+'" style=" width: 50%;" controls>';
            }
            function preset(i){
                var preset;
                switch(i){
<?
                    for($i=1;$i<=count($item);$i++){
                        echo '                    case '.$i.":\n";
                        echo '                        preset = "'.$item[$i-1]."\";\n";
                        echo '                        break;'."\n";
                    }
                    ?>
                    default:
                }
                document.getElementById('tbx').value = preset;
                document.getElementById('number').innerHTML = "#" + i;
                document.getElementById('tbtn').click();
                document.getElementById('outVideo').play();
                document.getElementById('vidspeed').value = "1.0";
                document.getElementById('vidout').innerHTML = "1.0";
            }
            window.onload = function() {document.getElementById("tbx").focus();};
        </script>
        <style>
            div{
                margin-top: 2px;
            }
            div.inline{
                margin-top: 0;
                display: inline-block;
                virtical-align: center;
            }
            input.buttons{
                margin-right: 1px;
                width: 32px;
            }
            input#tbx{
                width: 300px;
            }
            div#number{
                margin-bottom: 0;
                margin-top: 0;
            }
            video{
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <div class="inline items">
            <?php
                $j = 1;
                for($i=1;$i<=count($item);$i++){
                    echo "<input class=\"buttons\" type=\"button\" id=\"bt$i\" value=\"$i\" onclick=\"preset($i);\">";
                    $j = $j + 1;
                    if($j > 10){
                        echo "<br>";
                        $j = 1;
                    }
                }
            ?>
        </div>
        <div id='input' class="inline ui"  style="margin-bottom: 3px">
            <div id="number"></div>
            <input type="text" id="tbx" onkeydown="tbxkey(this);">
            <input type="button" id="tbtn" value="DO IT!" onclick="doAction();"><br>
            <form oninput="vidout.value=vidspeed.value">
                <input type="range" id="vidspeed" min="0.5" max="2.0" step="0.1" value="1.0" onchange="changeVidS(this);"/>
                <output id="vidout" name="vidout" for="vidspeed">1.0</output>
            </form>
        </div>
        <div id='output'></div>
    </body>
</html>