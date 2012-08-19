FTPbox Web-Interface
=============

About
--------------

This repository contains all the files needed for the Web Interface to work. This is mainly for development purposes. If you want to install the WebUI, head to the options and select to use it, the program will install it automatically!


Development
=============

To-Do
--------------

- Open/Show file content inside the UI, no need to use a direct link
- New icons, maybe Fugue Icons ( http://p.yusukekamiyamane.com )
- Option to copy a file's direct link to clipboard
- Fix post_max_size and upload_max_filesize errors when uploading
- Password should be required to access the settings
- Optional: if deleting is publicly enabled for a UI that isn't pass-protected


Installing
=============

Uploading
--------------

The uploading process has been automated with the FTPbox program to save you the trouble. If you have troubles or just want to do it manually, here's how:
You only need to upload all the files to your folder of preference (using an FTP client, etc). If you're using webint.zip from the downloads, you'll have to extract its contents first and then upload them.

*If you want the FTPbox program to ignore the WebUI files during synchronization and save some time, put them in a folder named 'webint'.*

Setup
--------------

To setup the WebUI, launch your browser and visit the HTTP path of the folder in which you uploaded the WebUI files. You'll be prompted with a form to password-protect the interface. 
**It is highly recommend that you use a password to protect your files.**
Finish this simple form and you're ready to use the WebUI.

Requirements
--------------

The requirements to use the WebUI are:

- PHP version 5 or later installed.
- HTTP access to your WebUI folder