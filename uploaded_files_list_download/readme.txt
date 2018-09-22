Customer Uploaded Files - List and Download v 1.03
Version Release Date: 2/26/2012
Publisher: PRO-Webs, Inc
Released for Zen Cart v 1.3.x & 1.5.0 (V 12-30-2011)


This installation is considered a intermediate level Zen Cart module installation.


If you need installation, please visit our store here:
http://pro-webs.net/store/index.php?main_page=product_info&cPath=10_11&products_id=244


This module lists all files that customers have uploaded in association with orders. 
Show the order number, customer's name, and the file name they assigned. 
Provides a single-click secure download for such files. Adds a menu item 
"Uploads" to the "Customers" menu in the admin area.


INSTALLATION INSTRUCTIONS
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

1. FIRST MAKE A FULL BACKUP OF YOUR WEBSITE'S FILES AND DATABASE!

2. Locate the proper installation for your Zen Cart version by clicking the
   VERSION link in your Zen Cart administration interface. 1.3.X includes 
   for example: 1.3.5, 1.3.8(anything), 1.3.9 (A B C ETC).

3. Rename the /admin/ directory to match your own admin directory folder name.

4. Upload the module files to your admin directory.

5. Modify Core file configure.php

  The default Zen Cart configure.php file for the administrative area
  does NOT include a file path to the 'uploads' folder (where your customer's
  uploaded files reside).  You will need to add two lines of definitions
  to your administrative configure.php file.

  Reminder: if you followed the Zen Cart recommendations, you changed the
  permissions on your configure.php file so it cannot be written.  So,
  before you can make these changes, you'll need to make it writeable
  (and change it back after you have tested these modifications).

 Open the 'admin/includes/configure.php' file using a text editor that
 handles plain text files.  Search for two lines that look like this:

  define('DIR_FS_EMAIL_TEMPLATES', DIR_FS_CATALOG . 'email/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');

 Between those two lines, add these two lines:

  define('DIR_WS_UPLOADS', DIR_WS_IMAGES . 'uploads/');
  define('DIR_FS_UPLOADS', DIR_FS_CATALOG . DIR_WS_UPLOADS );

 so your configure.php file will now read like this:

  define('DIR_FS_EMAIL_TEMPLATES', DIR_FS_CATALOG . 'email/');
  define('DIR_WS_UPLOADS', DIR_WS_IMAGES . 'uploads/');
  define('DIR_FS_UPLOADS', DIR_FS_CATALOG . DIR_WS_UPLOADS );
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  
 Now reset the permissions (CHMOD) of configure.php to at least 444, 400 is even
 better if that works with your hosted environment.
  
   NOTE****
   These configure.php modifications assume you are using the default
   folder for customer's uploaded files (a folder called 'uploads' located
   within the 'images' folder).  If you are using a non-standard location,
   you will need to make suitable changes to the DIR_WS_UPLOADS and
   DIR_FS_UPLOADS definitions.  These same definitions appear in your
   store's configuration file, at includes/configure.php, so you can
   just copy the DIR_WS_UPLOADS and DIR_FS_UPLOADS from there and paste
   them into your administrative configure.php file.

6. That's all. You will find your new function in your administration interface
   under the customers menu called "Uploads".
   
   ** You will need to refresh your admin interface to load the new menu item.


USAGE
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Once you have copied these files into your Zen Cart installation,
you should see a new menu item "Uploads" in the "Customers" menu of
your administrative area.

Each customer-uploaded file is displayed on a separate line, showing
the order ID, the customer's name, the name the customer used when
uploading the file, and (on the extreme right) a "download" link.

Click the download link to download the file.  A default name will be
supplied, but depending upon your browser, you will usually be given an
opportunity to change this name to suit your needs.

The download link will NOT work unless you are validly logged in as an
administrator of your store.  Thus, only you, as the store owner, will
be able to download customer's files.

Files in the uploads directory (/catalog/images/uploads/) should occasionally be
pruned for optimal perfomance.


INFORMATION
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Core file Edits: admin/includes/configure.php add code only.
Database Changes: None


UNINSTALL
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Simply remove the package files and edits for a full uninstall.


SUPPORT
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Please use Zen Cart forum support thread located here
http://www.zen-cart.com/forum/showthread.php?t=159940


VERSION HISTORY
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Dana Cartwright (ZC forums: decartwr, e-mail: zen@dobbytron.com)

5/25/2011 Updated & Reformatted by PRO-Webs.net
          ## Usability Fix
             1. Rearranged the columns to be more logical
             2. Columns a border to be more usable
             3. Resorted the downloads by the order number instead of the upload ID.
   
2/26/2012 Upgraded for Zen Cart 1.5.0 2/26/2012
             1. Upgraded installation with new files added for 1.5.0
             2. Retained installation for 1.3.X
             3. PCI/Security update removed invalid filename formats
             4. Updated and edited readme for clarity, brevity and conveyance
             5. Added support documentation