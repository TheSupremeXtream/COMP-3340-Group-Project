# Local Setup Instructions

## Requirements
- XAMPP installed with **Apache** and **MySQL**
- A web browser

## Steps to run the website locally

1. Install XAMPP from https://www.apachefriends.org/

2. During installation, make sure **Apache** and **MySQL** are selected.

3. Open the **XAMPP Control Panel** and start both **Apache** and **MySQL**.

4. Place the project folder named `COMP-3340-Group-Project` inside:

   `C:\xampp\htdocs\`

5. Open your browser and go to:

   `http://localhost/COMP-3340-Group-Project/backend/install.php`

6. The installer will automatically:
   - create the `store_db` database if it does not already exist
   - import the database from `backend/database.sql` if the required tables are missing
   - ensure the theme setting is configured correctly

7. After the installer finishes, open the website at:

   `http://localhost/COMP-3340-Group-Project/`

## Admin account setup
To create an admin account:

1. Register a normal account on the website.
2. Open phpMyAdmin at:

   `http://localhost/phpmyadmin/`

3. Open the `store_db` database, then open the `users` table.
4. Change the user's role to `admin`.
5. If the account was already signed in, sign out and sign back in.

## Notes
- The installer only needs to be run once on a new setup.
- If the database and required tables already exist, the installer will simply confirm that the project is ready to use.
- After setup is complete, the installer file can be left unused or removed before final submission if desired.