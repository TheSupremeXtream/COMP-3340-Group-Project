# COMP-3340 Group Project

- Ronit Mahajan (110036557)
- Shameer Sheikh (110126392)
- David Woo
- Raphael Ceradoy (110102284)

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

### Image file sources (all royalty free from Wikimedia Commons):
- External HDD https://commons.wikimedia.org/wiki/File:V%C3%A4lise_HDD_sisu.jpg
- Raspberry Pi SSD https://commons.wikimedia.org/wiki/File:Raspberry-Pi-SSD-Feature.jpg
- UXB Thumb Drive https://commons.wikimedia.org/wiki/File:USB-thumb-drive-16-GB.jpg
- Headphones https://commons.wikimedia.org/wiki/File:Sony-WH-1000XM3-kabellose-Bluetooth-Noise-Cancelling-Kopfhoerer.4.jpg
- Gaming Headset https://commons.wikimedia.org/wiki/File:Sound_BlasterX_H5_Gaming_Headset.jpg
- Gaming Mouse https://commons.wikimedia.org/wiki/File:2023_Mysz_komputerowa_Logitech_G903_Lightspeed.jpg
- Microphone https://commons.wikimedia.org/wiki/File:AKG_C214_Condenser_microphone.jpg
- Camera https://commons.wikimedia.org/wiki/File:Joyusing_V500_HDMI_VGA_USB_Document_Camera.jpg
- Ethernet Cable https://commons.wikimedia.org/wiki/File:Ethernet_RJ45_connector_p1160054.jpg
- Micro-SD Card https://commons.wikimedia.org/wiki/File:SD_card_adapters.jpg
- USB-A Cable https://commons.wikimedia.org/wiki/File:USB_Mini-B_and_Standard-A_plugs.jpg
- USB-B Cable https://commons.wikimedia.org/wiki/File:Blu_USB_2.0_%26_Micro-USB_cable,_Hillegersberg,_Rotterdam_(2023)_02.jpg 
- USB-C Cable https://commons.wikimedia.org/wiki/File:USB_Type-C_Cable_-_iPad_USB-C_Charger_(45640822114).jpg
- USB-C Hub https://commons.wikimedia.org/wiki/File:Silicon_vs_GaN_30W_USB-C_chargers.jpg
- USB-C Charging Brick https://commons.wikimedia.org/wiki/File:Silicon_vs_GaN_30W_USB-C_chargers.jpg
- USB-A Charging Brick https://commons.wikimedia.org/wiki/File:USB_AC_Adapters_(cropped).JPG
- USB-C Power Bank https://commons.wikimedia.org/wiki/File:SAMSUNG_BATTERY_PACK_(POWER_BANK)_EB-P4520_(3).jpg
- USB-C Extension Cable https://commons.wikimedia.org/wiki/File:CAB-25579-USB-C-Extension-Cable-with-Power-Switch-Detail-2.jpg
- Controller https://commons.wikimedia.org/wiki/File:Hands_holding_video_game_controller_(50811892858).jpg
- Laptop Stand https://commons.wikimedia.org/wiki/File:Christopher_N%C3%B5mmann_arvuti_stand.jpg
- All Audio Files royalty free with license from Ovani Sound. 'Thank You.png' can be found in the multimedia folder.