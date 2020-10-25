<?php
// $Id: index.php,v 1.1 2004/12/06 18:13:29 robekras Exp $
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
/*
  if (function_exists ("DebugBreak")) {
    global $DBGSESSID;
    $DBGSESSID = "1@clienthost:7869";
    DebugBreak ();
  }
*/
include '../../mainfile.php';
require __DIR__ . '/include/language.php';
require __DIR__ . '/include/doctypes.php';
include 'man_func.php';

include $xoopsConfig['root_path'] . 'header.php';
/*
  if (isset ($_POST)) {
    foreach ($_POST as $k => $v) {
      ${$k} = $v;
    }
  }
*/
if (isset($newlang)) {
    $lang = $newlang;
} else {
    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    }
}

if (isset($lang)) {
    if (isset($doc)) {
        /*
        echo "  doc=".$doc .",     ";
        echo "   id=".$id  .",     ";
        echo " file=".$file.",     ";
        echo " page=".$page."<br>";
        */
    }
} else {
    $lang = 'en';
}

if (isset($_GET['doc'])) {
    $doc = $_GET['doc'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

if (isset($doc)) {
    $GLOBALS['xoopsOption']['template_main'] = 'manual_index.html';

    // count the hits

    $sql = sprintf('UPDATE ' . $xoopsDB->prefix('xdocman_hits') . " SET hits = hits+1 WHERE doc='$doc' AND doctype='online' AND language='$lang'");

    $xoopsDB->queryF($sql);

    if (0 == $xoopsDB->getAffectedRows()) {
        $sql = sprintf('INSERT INTO ' . $xoopsDB->prefix('xdocman_hits') . " SET hits=1, doc='$doc', doctype='online', language='$lang'");

        $xoopsDB->queryF($sql);
    }

    BuildDocStartPage($doc, $lang);  // shows the start page of a specific document
} else {
    $GLOBALS['xoopsOption']['template_main'] = 'docs_index.html';

    BuildStartPage($lang);           // shows every available document
}

$xoopsTpl->assign('xoops_module_header', '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/modules/xdocman/style.css">');
$xoopsTpl->assign('change_to_utf8', 1);
$xoopsTpl->assign('modroot', $xoopsConfig['root_path'] . 'modules/xdocman/');
$xoopsTpl->assign('docroot', 'docs');

include $xoopsConfig['root_path'] . 'footer.php';
