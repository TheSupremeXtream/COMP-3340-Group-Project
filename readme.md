# COMP-3340 Group Project

- Ronit Mahajan (110036557)
- Shameer Sheikh
- David Woo
- Raphael Ceradoy

## Local Setup Instructions

### Requirements
- XAMPP installed
- Apache and MySQL enabled in XAMPP
- A web browser

### Project Placement
Move the project folder into your XAMPP `htdocs` directory.

Example:
`C:\xampp\htdocs\COMP-3340-Group-Project`

> Note: The project folder should be placed directly inside `htdocs`, not inside another subfolder like `htdocs\xampp\`.

### Running the Project
1. Open the XAMPP Control Panel.
2. Start **Apache** and **MySQL**.
3. Open your browser and go to:

   `http://localhost/COMP-3340-Group-Project/backend/install.php`

4. The installer will automatically:
   - create the `store_db` database if it does not already exist
   - import `backend/database.sql` if the required tables are missing
   - ensure the default theme setting exists

5. After the installer finishes, open the website at:

   `http://localhost/COMP-3340-Group-Project/`

### Admin Account Setup
To make an admin account:

1. Register a normal account through the website.
2. Open phpMyAdmin:

   `http://localhost/phpmyadmin/`

3. Open the `store_db` database.
4. Open the `users` table.
5. Change the user’s `role` value to `admin`.
6. If the user was already logged in, sign out and sign back in.

### Notes
- The installer only needs to be run once on a new setup.
- If the database is already installed, the installer will simply confirm that everything is ready.
- If your folder name is different, replace `COMP-3340-Group-Project` in the URLs with your actual folder name.