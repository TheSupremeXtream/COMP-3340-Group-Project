Instructions on running the website locally:
1. Install XAMPP at https://www.apachefriends.org/
2. In the XAMPP installer, make sure MySQL and Apache are selected.
3. After installing, run the XAMPP Control Panel and start the Apache and MySQL modules.
4. Take the website's folder "COMP-3340-Group-Project" and place it in C:\xampp\htdocs.
5. Open your broswer of choice and paste this link in the address bar: http://localhost/COMP-3340-Group-Project/
6. Go to http://localhost/phpmyadmin/ and then select databases, then create a database called 'store_db'.
7. Inside the store_db database, import the database located at ./COMP-3340-Group-Project/backend/database.sql
8. To create an admin account, register an account normally, then in your store_db database at phpmyadmin, set the account as admin. If you were already signed in, please sign out and then sign back in.