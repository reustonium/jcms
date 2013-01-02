// A generic onclick callback function.
function genericOnClick(info) {
  console.log(info.srcUrl);
}

var title = "Add to JC Queue";
var id = chrome.contextMenus.create({"title": title, "contexts":["image"], "onclick": genericOnClick});