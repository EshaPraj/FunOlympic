## INSTRUCTIONS FOR RUNNING SECURE AUTH WEB APPLICATION,

Step 1: Install XAMPP,<br />
Step 2: Find XAMPP installation path. For windows the default path is (C:\xampp) for linux its (/opt/lampp),<br />
Step 3: Copy and paste the provided secure-auth folder inside (C:\xampp\htdocs) or (/opt/lampp/htdocs),<br />
Step 4: Start XAMPP Apache and MySQL server. (Type sudo /opt/lampp/lampp start in case of linux) or in windows use XAMPP GUI to click and start the server,<br />
Step 4: Once the server is up and running, navigate to "localhost/phpmyadmin" in your preferred web browser,<br />
Step 5: Click on the "Import" tab present at the top most part of the screen,<br />
Step 6: Click on "Choose File" and select the provided "query.sql" file,<br />
Step 7: Navigate to the bottom of the page and click on Import. If all is well it should create a "secure_auth" database on the left sidebar,<br />
Step 8: Now navigate to "localhost/secure-auth" and you should see a login page.