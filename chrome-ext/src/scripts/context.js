var imageUrl

var id = chrome.contextMenus.create({"title": "Add to JC Queue", "contexts":["image"], "onclick": genericOnClick});

function genericOnClick(info) {
  imageUrl = info.srcUrl;
    chrome.tabs.create({
      url: chrome.extension.getURL('dialog.html'),
      active: false
      }, function(tab) {
      // After the tab has been created, open a window to inject the tab
      chrome.windows.create({
        tabId: tab.id,
        type: 'popup',
        focused: true
        });
      });
}