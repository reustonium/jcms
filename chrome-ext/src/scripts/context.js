// A generic onclick callback function.
function genericOnClick(info, tab) {
  console.log("item " + info.menuItemId + " was clicked");
  console.log("info: " + JSON.stringify(info));
 // console.log("tab: " + JSON.stringify(tab));
}

var title = "Add to JC Queue";
var id = chrome.contextMenus.create({"title": title, "contexts":["image"], "onclick": genericOnClick});