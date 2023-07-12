<?php
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

if (function_exists('zen_register_admin_page')) {
    if (!zen_page_key_exists('uploads')) {
        zen_register_admin_page('uploads', 'BOX_CUSTOMERS_UPLOADS','FILENAME_UPLOADS', '', 'customers', 'Y', 17);
    }
}

function download_file($index, $oid) {

//  Look up in the database of upload files, that
//  gives us the original filename the user used.
//  We care about that only to the extent that it
//  gives us an extension, from which we deduce
//  the file type.  We *could* arrange to name the
//  downloaded file per the user's original name,
//  but that is not likely to be helpful to us on
//  the receiving end (who knows what wacky naming
//  convention the user uses?).  Instead, we adopt a uniform
//  naming convention that incorporates the original
//  order ID.  Note: index has already been sanitized.
  $query = "SELECT *
            FROM " . TABLE_FILES_UPLOADED . "
            WHERE files_uploaded_id = " . (int)$index;
  $file = $db->Execute($query);
  if ($file->RecordCount() != 1) {
//  $index has been sanitized and so is safe to echo here.
    die('unknown upload index=' . (int)$index . ' (' . __LINE__ . ')');
  }
  $fileName = $file->fields['files_uploaded_name'];
  $file_extension = strtolower($fext = substr(strrchr($fileName, '.'), 1));
  switch ($file_extension) {
    case 'csv':
      $content = 'text/csv';
      break;
    case 'zip':
      $content = 'application/zip';
      break;
    case 'jpg':
      $content = 'image/jpeg';
      break;
    case 'jpeg':
      $content = 'image/jpeg';
      break;
    case 'gif':
      $content = 'image/gif';
      break;
    case 'png':
      $content = 'image/png';
      break;
    case 'eps':
      $content = 'application/postscript';
      break;
    case 'cdr':
      $content = 'application/cdr';
      break;  //  CorelDRAW
    case 'ai':
      $content = 'application/postscript';
      break;
    case 'pdf':
      $content = 'application/pdf';
      break;
    case 'tif':
      $content = 'image/tiff';
      break;
    case 'tiff':
      $content = 'image/tiff';
      break;
    case 'bmp':
      $content = 'image/bmp';
      break;
    case 'xls':
      $content = 'application/vnd.ms-excel';
      break;
    case 'numbers':
      $content = 'application/vnd.ms-excel';
      break;
    default:
      die('File extension "' . $file_extension . '" not understood (line ' . __LINE__ . ')');
  }
  $fs_path = DIR_FS_CATALOG_IMAGES . 'uploads/' . $index . '.' . $fext;
  if (!file_exists($fs_path))
    die('File "' . $fs_path . '" does not exist (' . __LINE__ . ')');
//  We make a download file name consisting of the characters "zc" followed
//  by the order ID followed by the file index, using "_" as a separator.
//  This makes the file names easily recognized on the receiving computer,
//  and lets the admin know what order the file came from.  Note: $oid is sanitized.
  $nfile = 'zc_order' . $oid . '_' . $index . '.' . $fext;
  header('Content-type: ' . $content);
  header('Content-Disposition: attachment; filename="' . $nfile . '"');
  header('Content-Transfer-Encoding: binary');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
  readfile($fs_path);
}
