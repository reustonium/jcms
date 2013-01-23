# Johnny Cheeseburger Content Management System (JCMS) #

### Introduction ###
JCMS is a content management system developed for [Johnny Cheeseburgers Facebook Fan Page.](http://www.facebook.com/johnnycheeseburger)  This CMS has three parts: 

- Chrome Extension 
- SQL-DB 
- Webapp

The **Chrome Extension** is available in the chrome-ext/packaged directory as an prepackaged chrome extension which takes image url's and adds the to the JC-SQL database.

The **JC-SQL database** stores image information including source URL, comment, album_ID, number of likes, and number of comments.

The **Webapp** is a php file which handles facebook authentication and database integration.  It allows the user to upload a number of images each day from the JC-SQL db and posts them to an newly created album on the Facebook Fan Page using Facebook Graph API. 

### Installation ###
JCMS is installed in Chrome by going to tools>extensions and draging and droping the jcms.crx file on to the page.

### How to Contribute ###
If you find any bugs or would like to request new features please go to the [Issues](https://github.com/reustonium/jcms/issues) page and [submit your bug or idea](https://github.com/reustonium/jcms/issues/new).

© Reustonium 2013.