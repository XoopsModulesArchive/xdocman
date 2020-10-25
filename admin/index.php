<?php
// $Id: index.php,v 1.2 2004/12/06 19:21:23 robekras Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

include '../../../mainfile.php';
/*
  if (function_exists ("DebugBreak")) {
    global $DBGSESSID;
    $DBGSESSID = "1@clienthost:7869";
    DebugBreak ();
  }
*/
require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstopic.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
require_once XOOPS_ROOT_PATH . '/class/database/databasefactory.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

require dirname(__DIR__) . '/include/language.php';
require __DIR__ . '/admin_func.php';

$op = $_POST['op'] ?? $_GET['op'] ?? 'default';

if (!isset($language)) {
    $language = 'en';
}

switch ($op) {
    case 'upload_doc':
        xoops_cp_header();
        XDM_UploadDocFileForm($language);
        break;
    case 'upload_style':
        xoops_cp_header();
        XDM_UploadStyleFileForm();
        break;
    case 'upload_doc_file':
        xoops_cp_header();
        XDM_UploadDocFile($language);
        break;
    case 'upload_style_file':
        xoops_cp_header();
        XDM_UploadStyleFile();
        break;
    case 'default':
    default:
        xoops_cp_header();
        echo '<h4>' . _DM_CONFIG . '</h4>';
        echo '<table width="100%" border="0" cellspacing="1" class="outer"><tr><td class="odd">';
        echo " - <b><a href='" . XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid') . "'>" . _DM_GENERALCONF . "</a></b><br><br>\n";
        echo " - <b><a href='index.php?op=upload_doc'>" . _DM_UPLOAD_DOC . "</a></b><br><br>\n";
        echo " - <b><a href='index.php?op=upload_style'>" . _DM_UPLOAD_STYLE . "</a></b><br><br>\n";
        echo '</td></tr></table>';
        break;
}

xoops_cp_footer();
