# Uploaded-Files-List
Customer Uploaded Files - List and Download v 1.4
Version Release Date: 9/26/2018
Publisher: Zen4All
Released for Zen Cart v1.5.5x


This installation is considered a beginner level Zen Cart module installation.


If you need installation, please visit our store here:
https://zen4all.nl/index.php?main_page=contact_us


This module lists all files that customers have uploaded in association with orders.
Show the order number, customer's name, and the file name they assigned.
Provides a single-click secure download for such files. Adds a menu item
"Uploads" to the "Customers" menu in the admin area.


INSTALLATION INSTRUCTIONS

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

1. FIRST MAKE A FULL BACKUP OF YOUR WEBSITE'S FILES AND DATABASE!

2. Rename the /admin/ directory to match your own admin directory folder name.

3. Upload the module files to your admin directory.

4. That's all. You will find your new function in your administration interface
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

Click the download link to download the file. A default name will be
supplied, but depending upon your browser, you will usually be given an
opportunity to change this name to suit your needs.

The download link will NOT work unless you are validly logged in as an
administrator of your store.  Thus, only you, as the store owner, will
be able to download customer's files.

Files in the uploads directory (/catalog/images/uploads/) should occasionally be
pruned for optimal perfomance.


INFORMATION

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Core file Edits: None
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

9/26/2018 Updated & Reformatted by Zen4All
             1. Updated code to be more conform Zen Cart standards
             2. Removed core code overrides