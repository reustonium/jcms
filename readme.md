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

Copyright (C) 2013 Andrew Ruestow

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.