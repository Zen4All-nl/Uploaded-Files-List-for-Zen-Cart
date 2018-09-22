<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2010 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// |                                                                      |
// | Author: Dana Cartwright (Dobbytron Inc.)                             |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
//

/*
	This module keys on finding a Product Option with the name "File", and then finding all orders
	that contain products that have an attribute corresponding to the File product option.  This works
	for an out-of-the-box Zen Cart installation, but there are various ways a store could be set
	up that would break this code.  And it's a roundabout and painful way to dig up the info.
	
	The proper solution would involve changing the core code.  A good fix would be to
	record the products_attributes_id value from the products_attributes table into a
	field in the files_uploaded table.  That would provide a direct and simple link tying
	the uploaded file to a specific attribute of a specific product within a specific order.
	Since there isn't any reason to record the customers_id in the files_uploaded table, that field
	would be better used to record the products_attributes_id.
	
	Revision History
	
	V1.01 (9/14/2010) added  onload="init()"  to the <body> statement.  This fixes a small
	mis-alignment in the CSS menu hovers, and a stray line that appears while hovering.
	
*/
	require( ('includes/application_top.php') );
	
	define( 'MAX_DISPLAY_SEARCH_RESULTS_UPLOADS', 25 );
	
	$get = (isset($_GET['get']) ? (int)$_GET['get'] : '');
	$oid = (isset($_GET['oid']) ? (int)$_GET['oid'] : '');
	
	//	If the URL points to a specific file to download,
	//	go do that now and abandon further processing.
	if ( zen_not_null( $get) ) {
		download_file( $get, $oid );
		exit;
		}
	
	//	Find the products_option_id corresponding to a customer uploaded file.
	$query_opt = 'SELECT products_options_types_id AS optid FROM ' . TABLE_PRODUCTS_OPTIONS_TYPES .
		' WHERE products_options_types_name = "' . PRODUCTS_OPTIONS_TYPES_NAME . '" ';
    $opts = $db->Execute( $query_opt );
    if ( $opts->RecordCount() != 1 )
    	//	If you get to thie die statement, you probably have something mis-configured.
    	//	You don't have a product option of type "File" which is unusual (although it's
    	//	not technically wrong, it's certainly unusual).  Or, you set the value of
    	//	PRODUCTS_OPTIONS_TYPES_NAME to something other than "File".
    	die( 'Cannot find a product option of type "' . PRODUCTS_OPTIONS_TYPES_NAME . '" (' . __LINE__ . ')' );
	$optid = $opts->fields[ 'optid' ];
	
	//	Determine which page we are displaying, build display list.
	$offset = ($page - 1) * MAX_DISPLAY_SEARCH_RESULTS_UPLOADS;		
	$query_files = 'SELECT ' .
		'opa.products_options_values AS fname, ' .
		'opa.orders_id AS oid, ' .
		'tor.customers_name AS cname ' .
		'FROM ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' AS opa ' .
		'LEFT JOIN ' . TABLE_PRODUCTS_OPTIONS . ' AS po ON po.products_options_id = opa.products_options_id ' .
		'LEFT JOIN ' . TABLE_ORDERS . ' AS tor ON opa.orders_id = tor.orders_id ' .
		'WHERE po.products_options_type = "' . $optid . '" ' .
		'ORDER BY opa.orders_id DESC ';

    $splitter = new splitPageResults( $_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_UPLOADS, $query_files, $files_query_numrows );
    $files = $db->Execute( $query_files );
    $tolist = array();
    while ( !$files->EOF ) {
    	$oid = $files->fields[ 'oid' ];
    	//	The file ID and NAME are combined within the products_options_values field
    	//	in the TABLE_ORDERS_PRODUCTS_ATTRIBUTES table.  So we have to parse that out.
    	$opt = $files->fields[ 'fname' ];
    	$m = preg_match( '/^([0-9]+)\.\s*(.*)$/', $opt, $matches );
    	if ( count( $matches ) == 3 ) {
    		$fname = $matches[ 2 ];
    		$upid = $matches[ 1 ];
    		$link = '<a href="' . zen_href_link( basename( $PHP_SELF ), 'get=' . $upid . '&oid=' . $oid, 'NONSSL' ) . '">' . TABLE_DOWNLOAD . '</a>';
    		$status = true;
    		}
    	else {
    		$fname = $opt;
    		$upid = '';
    		$status = false;
    		$link = '';
    		}
    	$tolist[] = array(
    		'cname' => $files->fields[ 'cname' ],
			'oid' => $files->fields[ 'oid' ],
    		'fname' => $fname,
    		'upid' => $upid,
    		'status' => $status,
    		'link' => $link
    			);
    	$files->MoveNext();
    	}
	
	//	Emit the HTML for the page.
	$html_params = HTML_PARAMS;
	$charset = CHARSET;
	$title = TITLE;
	$heading = TITLE;
	echo <<<eot
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html $html_params>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$charset">
<title>$title</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body onload="init()">
eot;
	require( (DIR_WS_INCLUDES . 'header.php' ) );
	$th_oid = TABLE_HEADING_ORDER_ID;
	$th_name = TABLE_HEADING_NAME;
		$th_id = TABLE_HEADING_ID;
	$th_download = TABLE_HEADING_DOWNLOAD;
	$th_file = TABLE_HEADING_FILE;
	
	$listing = '';
	foreach( $tolist as $d ) {
		$listing .= '<tr>' . "\r";
		$listing .= '<td class="dataTableContent">' . $d[ 'oid' ] . '</td>' . "\r";
		$listing .= '<td class="dataTableContent">' . $d[ 'cname' ] . '</td>' . "\r";
		$listing .= '<td class="dataTableContent">' . $d[ 'link' ] . '</td>' . "\r";
		$listing .= '<td class="dataTableContent">' . $d[ 'fname' ] . '</td>' . "\r";

		$listing .= '<td class="dataTableContent">' . $d[ 'upid' ] . '</td>' . "\r";
		$listing .= '</tr>' . "\r";
		}
		
	$pagesplit =
	'<table border="0" width="100%" cellspacing="0" cellpadding="2" id="pagesplit">' .
	'<tr>' .
	'<td class="smallText" valign="top">' .
		$splitter->display_count( $files_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_UPLOADS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_UPLOADS ) .
	'</td>' .
	'<td class="smallText">' .
		$splitter->display_links( $files_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_UPLOADS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params( array( 'page' ))) .
	'</td>' .
	'</tr>' .
	'</table>' . "\r";

	echo <<<eot
<table border="0" width="100%" cellspacing="2" cellpadding="2" id="content"><!-- 1 -->
<tr width="100%" valign="top"><!-- 1 -->
<td width="100%" valign="top">

<table cellspacing="0" cellpadding="0" border="0" width="100%"><!-- 2 -->
<tr><!-- 2 -->
<td><!-- 2 -->

<table cellspacing="0" cellpadding="0" border="0" width="100%"><!-- 3a -->
<tr><td class="pageHeading" style="height:40px;">Uploaded Files</td></tr><!-- 3a -->
</table><!-- 3a -->
</td><!-- 2 -->
</tr><!-- 2 -->

<tr><!-- 2 -->
<td><!-- 2 -->
<table cellspacing="0" cellpadding="0" border="1" width="100%" bordercolorlight="#000000"><!-- 3b -->
<tr class="dataTableHeadingRow"><!-- 3b -->
<td class="dataTableHeadingContent">$th_oid</td><!-- 3b -->
<td class="dataTableHeadingContent">$th_name</td><!-- 3b -->
<td class="dataTableHeadingContent">$th_download</td><!-- 3b -->
<td class="dataTableHeadingContent">$th_file</td><!-- 3b -->
<td class="dataTableHeadingContent">$th_id</td><!-- 3b -->


</tr><!-- 3b -->
$listing
</table><!-- 3b -->
</td><!-- 2 -->
</tr><!-- 2 -->

<tr><!-- 2 -->
<td><!-- 2 -->
$pagesplit
</td><!-- 2 -->
</tr><!-- 2 -->
</table><!-- 2 -->

</td><!-- 1 -->
</tr><!-- 1 -->
</table><!-- 1 -->
eot;
	require( (DIR_WS_INCLUDES . 'footer.php' ) );
	echo '<br></body></html>';
	require( (DIR_WS_INCLUDES . 'application_bottom.php' ) );
	
//	Download the user's uploaded file corresponding to $index.
//	A visitor cannot arrive here unless they are validly logged
//	in as the admin, so only the admin of the store should be able
//	to download a file via this mechanism.
function download_file( $index, $oid ) {
	global		$db;
	//	Look up in the database of upload files, that
	//	gives us the original filename the user used.
	//	We care about that only to the extent that it
	//	gives us an extension, from which we deduce
	//	the file type.  We *could* arrange to name the
	//	downloaded file per the user's original name,
	//	but that is not likely to be helpful to us on
	//	the receiving end (who knows what wacky naming
	//	convention the user uses?).  Instead, we adopt a uniform
	//	naming convention that incorporates the original
	//	order ID.  Note: index has already been sanitized.
	$query = 'SELECT * FROM ' . TABLE_FILES_UPLOADED . ' WHERE `files_uploaded_id`="' . $index . '"';
    $file = $db->Execute( $query );
    if ( $file->RecordCount() != 1 )
    	//	$index has been sanitized and so is safe to echo here.
    	die( 'unknown upload index=' . $index . ' (' . __LINE__ . ')' );
    $fileName = $file->fields[ 'files_uploaded_name' ];
    $file_extension = strtolower( substr( strrchr( $fileName, '.' ), 1) );
    switch( $file_extension ) {
		case 'csv':		$content = 'text/csv';						break;
		case 'zip':		$content = 'application/zip';				break;
		case 'jpg':		$content = 'image/jpeg';					break;
		case 'jpeg':	$content = 'image/jpeg';					break;
		case 'gif':		$content = 'image/gif';						break;
		case 'png':		$content = 'image/png';						break;
		case 'eps':		$content = 'application/postscript';		break;
		case 'cdr':		$content = 'application/cdr';				break;		//	CorelDRAW
		case 'ai':		$content = 'application/postscript';		break;
		case 'pdf':		$content = 'application/pdf';				break;
		case 'tif':		$content = 'image/tiff';					break;
		case 'tiff':	$content = 'image/tiff';					break;
		case 'bmp':		$content = 'image/bmp';						break;
		case 'xls':		$content = 'application/vnd.ms-excel';		break;
		case 'numbers':	$content = 'application/vnd.ms-excel';		break;
		default:
			die( 'File extension "' . $file_extension . '" not understood (line ' . __LINE__ . ')' );
    	}
	$fs_path = DIR_FS_UPLOADS . $index . '.' . $file_extension;
	if ( !file_exists( $fs_path ) )
		die( 'File "' . $fs_path . '" does not exist (' . __LINE__ . ')' );
	//	We make a download file name consisting of the characters "zc" followed
	//	by the order ID followed by the file index, using "_" as a separator.
	//	This makes the file names easily recognized on the receiving computer,
	//	and lets the admin know what order the file came from.  Note: $oid is sanitized.
	$nfile = 'zc_order' . $oid . '_' . $index . '.' . $file_extension;
	header( 'Content-type: ' . $content );
	header( 'Content-Disposition: attachment; filename="' . $nfile . '"' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Cache-Control: no-cache, must-revalidate' );
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	readfile( $fs_path );
}
//	Ending PHP tag deliberately omitted.