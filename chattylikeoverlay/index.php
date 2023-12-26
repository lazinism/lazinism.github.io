<?
$curl = curl_init();
if (isset($_GET['ch'])){
	$url = "https://api.chzzk.naver.com/polling/v1/channels/".$_GET['ch']."/live-status";
	curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true, // Return the response as a string instead of outputting it
		CURLOPT_FOLLOWLOCATION => true, // Follow any redirects
		CURLOPT_HTTPGET => true, // Use GET method
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => ['Content-Type: application/json']
	]);
	$response = curl_exec($curl);
	if ($response === true) {
		$chid = json_decode($response)['content']['chatChannelId'];
		$url = "https://comm-api.game.naver.com/nng_main/v1/chats/access-token?channelId=".$chid."&chatType=STREAMING";
		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true, // Return the response as a string instead of outputting it
			CURLOPT_FOLLOWLOCATION => true, // Follow any redirects
			CURLOPT_HTTPGET => true, // Use GET method
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => ['Content-Type: application/json']
		]);
		$response = curl_exec($curl);
		if ($response === true){
			$access_token = json_decode($response)['content']['accessToken'];
		}
	}
}
curl_close($curl)
	
?>

<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="UTF-8">
    <title>채티라이크 오버레이</title>
    <style>
      .badgeImage,
      .emojiImage {
        height: 24px;
      }

      .badgeImage,
      .emojiImage,
      .colon,
      .name,
      .message {
        display: inline-block;
        vertical-align: top
      }

      .name {
        font-weight: 700;
        margin-left: .1em
      }

      .colon,
      .name {
        height: 24px
      }

      body,
      html {
        height: 100%;
        overflow: hidden
      }

      #mainFrame {
        position: absolute;
        bottom: 0;
        left: 0;
        padding: 0 10px 10px;
        width: 100%;
        box-sizing: border-box;
        text-shadow: 0 0 1px #666, 1px 1px 0 #000;
      }

      #mainFrame>div {
        padding-bottom: .25em;
        word-wrap: break-word;
      }

      body {
        background: rgba(0, 0, 0, 0.35);
        color: #FAFAFA;
        font-size: 24px;
      }

      #mainFrame {
        font: 0.8em 'Nanum Gothic', serif;
      }

      #mainFrame>div {
        animation: fadeOut 1s ease 15s forwards;
      }

      .fadeOut {
        opacity: 0;
        transition: opacity .5s ease-out
      }

      .nicknameChzzk {
        color: green
      }

      .nicknameTwitch {
        color: purple
      }
    </style>
  </head>
  <body>
    <div id="mainFrame"></div>
    <script>
      const urlParams = new URLSearchParams(window.location.search);

      function createAndDisplayDiv(e) {
        let a = document.createElement("div"),
          {
            nickname: s,
            message: t,
            from: l,
            badges: d,
            extras: i
          } = e,
          n = `
				<span class="${"chzzk"===l?"nicknameChzzk":"twitch"===l?"nicknameTwitch":""} name">${s}</span>`,
          m = t,
          r = "";
        if ("chzzk" === l) {
          i?.emojis && Object.entries(i.emojis).forEach(([f, j]) => {
            let s = `{:${f}:}`;
            m = m.split(s).join(`
				<img src="${j}" alt="${f}" class="emojiImage" />`);
          });
          if (d && d.length > 0) {
            r = `
				<img src="${d[0]?.imageUrl}" alt="Badge" class="badgeImage"/>`;
          }
        } else if ("twitch" === l && null !== i) {
          let g = i.split("/"),
            p = {};
          g.forEach(f => {
            let [b, s] = f.split(":");
            b = `https://static-cdn.jtvnw.net/emoticons/v2/${b}/default/dark/1.0`, s.split(",").forEach(e => {
              let [s, l] = e.split("-"), c = t.substring(parseInt(s), parseInt(l) + 1);
              p[c] || (p[c] = `
				<img src="${b}" alt="${c}" class="emojiImage" />`)
            })
          }), Object.keys(p).forEach(h => {
            let j = RegExp(h, "g");
            m = m.replace(j, p[h])
          })
        }
        let o = urlParams.get('te').replace(":", '<span class="colon">:</span>').replace("{badge}",r).replace("{time}","").replace("{user}",n).replace("{message}",`<span class="message">${m}</span>`);a.innerHTML=o,a.classList.add("chatHolder"),document.getElementById("mainFrame").appendChild(a),setTimeout(()=>{a.classList.add("fadeOut"),setTimeout(()=>{a.remove()},500)},urlParams.get('di'))}function connectToTwitch() {
              let t = new WebSocket("wss://irc-ws.chat.twitch.tv/");
              t.onopen = function(n) {
                t.send("CAP REQ :twitch.tv/tags twitch.tv/commands twitch.tv/membership"), t.send("PASS SCHMOOPIIE"), t.send("NICK justinfan11276"), t.send("USER justinfan11276 8 * :justinfan11276"), t.send(`JOIN #<?echo $_GET['tw']);?>`)
              }, t.onmessage = function(n) {
                let s = n.data.trim();
                if (s.startsWith("PING")) t.send("PONG");
                else if (s.startsWith("PONG")) t.send("PING");
                else if (!s.startsWith(":tmi.twitch.tv") && s.startsWith("@badge-info=")) {
                  let i = s.split(";display-name="),
                    c = s.match(/emotes=([^;]+)/),
                    a = c ? c[1] : null;
                  if (i.length > 1) {
                    let o = i[1].split(";")[0],
                      l = i[1].split(`PRIVMSG #<?echo $_GET['tw']);?>`);
                    l.length > 1 && createAndDisplayDiv({
                      nickname: o,
                      message: l[1].split(":")[1],
                      extras: a,
                      from: "twitch"
                    })
                  }
                }
              }, t.onclose = function() {
                setTimeout(connectToTwitch, 2e3)
              }
            }

            function connectToNaver() {
              let t = new WebSocket("wss://kr-ss2.chat.naver.com/chat");
              t.onopen = function(n) {
                t.send(JSON.stringify({
                  ver: "2",
                  cmd: 100,
                  svcid: "game",
                  cid: `<?echo $chid;?>`,
                  bdy: {
                    uid: null,
                    devType: 2001,
                    accTkn: `<?echo $access_token;?>}`,
                    auth: "READ"
                  },
                  tid: 1
                }))
              }, t.onmessage = function(n) {
                let c = JSON.parse(n.data);
                if (c.hasOwnProperty("cmd") && 0 === c.cmd) t.send(JSON.stringify({
                  ver: "2",
                  cmd: 1e4
                }));
                else if (93101 === c.cmd) {
                  let s = c.bdy;
                  s.forEach(t => {
                    let n = JSON.parse(t.profile.replace(/\\"/g, '"')),
                      d = n.nickname,
                      e = n.activityBadges,
                      i = t.msg,
                      a = JSON.parse(t.extras.replace(/\\"/g, '"'));
                    createAndDisplayDiv({
                      nickname: d,
                      message: i,
                      from: "chzzk",
                      badges: e,
                      extras: a
                    })
                  })
                } else if (93102 === c.cmd) {
                  let s = c.bdy;
                  s.forEach(t => {
                    let n = JSON.parse(t.profile.replace(/\\"/g, '"')),
                      d = n.nickname,
                      e = n.activityBadges,
                      i = t.msg,
                      a = JSON.parse(t.extras.replace(/\\"/g, '"'));
                    createAndDisplayDiv({
                      nickname: d,
                      message: i,
                      from: "chzzk-donate",
                      badges: e,
                      extras: a
                    })
                  })
                }
              }, t.onclose = function() {
                setTimeout(connectToNaver, 2e3)
              }
            }
            connectToTwitch(), connectToNaver();
    </script>
  </body>
</html>
