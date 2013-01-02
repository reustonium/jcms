# Johnny Cheeseburger Content Management System (JCMS) #

### Introduction ###
JCMS is a content management system developed for [Johnny Cheeseburgers Facebook Fan Page.](http://www.facebook.com/johnnycheeseburger)  This CMS has three parts: 

- Chrome Extension 
- SQL-DB 
- Server Side Code.

The **Chrome Extension** is available in the chrome-ext/packaged directory as an prepackaged chrome extension which takes image url's and adds the to the JC-SQL database.

The **JC-SQL database** stores image information including source URL, comment, unique ID, and date last posted.

The **Server-Side code** has yet to be developed but will pull 20 images each day from the JC-SQL db and post them to the Facebook Fan Page using Facebook Graph API. 

### Installation ###
JCMS is installed in Chrome by going to tools>extensions and draging and droping the jcms.ext file on to the page.

### How to Contribute ###
If you find any bugs or would like to request new features please go to the [Issues](https://github.com/reustonium/jcms/issues) page and [submit your bug or idea](https://github.com/reustonium/jcms/issues/new).

© Reustonium 2013.