// ==UserScript==
// @name         LOLESports
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  try to take over the world!
// @author       You
// @match        https://lolesports.com/*
// @icon         data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
// @grant        none
// ==/UserScript==

'use strict';
let lastUrl = location.href;
let region = 'msi';
let channel = 'riotgames';
new MutationObserver(() => {
  const url = location.href;
  if (url !== lastUrl) {
    lastUrl = url;
    onUrlChange();
  }
}).observe(document, {subtree: true, childList: true});
console.log("Timer Set");

function move(){
    window.location.href='https://lolesports.com/live/'+region+'/'+channel;
}


function onUrlChange () {
    var thisurl = location.href;
    if(thisurl.trim() != 'https://lolesports.com/live/'+region+'/'+channel){
        console.log("Timer Set");
        setTimeout(move, 60000);
    }
}
