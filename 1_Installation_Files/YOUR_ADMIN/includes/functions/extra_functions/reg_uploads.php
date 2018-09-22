<?php
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

if (function_exists('zen_register_admin_page')) {
    if (!zen_page_key_exists('uploads')) {
        zen_register_admin_page('uploads', 'BOX_CUSTOMERS_UPLOADS','FILENAME_UPLOADS', '', 'customers', 'Y', 17);
    }
}