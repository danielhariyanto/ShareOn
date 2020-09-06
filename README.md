How to make this project work:
1. xampp (windows):
-install xampp
-open xampp
-start apache (you can close xampp if you want now)
-open the xampp folder wherever it is 
-open htdocs
-put my project in there. It will run on localhost/ShareOn/ but partially
1. xampp (mac):
-install xampp
-open xampp
-start it
-click on volumes
-click on mount
-click on explore
-open htdocs
-put my project in there. It will run on ip_address/ShareOn/ but partially
2. arangodb:
-install arangodb
-set the root password to '1a2b3c4d'
-open arangodb management interface here: http://127.0.0.1:8529/_db/_system/_admin/aardvark/index.html#login
-your username should be root and password '1a2b3c4d'
-enter
-create a database named 'ShareOn' for root user
-go to the ShareOn database
for the purpose of you helping me out Bob smoothly, I'll only add the necessary stuff for the debugging to save both of us time:
-create the following collections: sessions, users
