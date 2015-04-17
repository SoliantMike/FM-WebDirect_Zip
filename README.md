FM-WebDirect_Zip
===========
By combining a little Custom Web Publishing (CWP) with WebDirect, we can extend the capabilities and provide some added functionality for our users.

Using the FileMaker API for PHP, we can retrieve all records with container data, zip them all in one archive and automatically have the users web browser download it.

This example demonstrates how to zip all related records container data into one archive and delivering it to the end user's web browser to download automatically. 

To use:
 1. Host the FM file on your WebDirect enabled FileMaker Server,
 2. Place the PHP file on your web server with PHP,
 3. Update the FileMaker field in the Resources table with the URL where you put the PHP script,
 4. Update the PHP script with your FileMaker Server's info.

Read more here:
http://www.soliantconsulting.com/blog/2015/04/extending-webdirect-automatically-zip-container-fields
