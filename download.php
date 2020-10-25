<?php
// $Id: download.php,v 1.1 2004/12/06 18:13:29 robekras Exp $
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

$GLOBALS['xoopsOption']['template_main'] = 'manual_index.html';
include $xoopsConfig['root_path'] . 'header.php';

$id = 0;
$lang = 'en';
$doc = '';
$file = 'index.php';

if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
}

if (isset($_POST['doc'])) {
    $doc = $_POST['doc'];
}

if (isset($_POST['doctype'])) {
    $doctype = $_POST['doctype'];
}

$html = '';

if (isset($_GET['doctype'])) {
    $doctype = $_GET['doctype'];
}

if (isset($_GET['doc'])) {
    $doc = $_GET['doc'];
}

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
}

// count the hit

$sql = sprintf('UPDATE ' . $xoopsDB->prefix('xdocman_hits') . " SET hits = hits+1 WHERE doc='$doc' AND doctype='$doctype' AND language='$lang'");
$xoopsDB->queryF($sql);

if (0 == $xoopsDB->getAffectedRows()) {
    $sql = sprintf('INSERT INTO ' . $xoopsDB->prefix('xdocman_hits') . " SET hits=1, doc='$doc', doctype='$doctype', language='$lang'");

    $xoopsDB->queryF($sql);
}

// and now send it

$file = "docs/$lang/$doc/$doc.$doctype";

header('Content-type: application/force-download');
header('Content-Transfer-Encoding: Binary');
header('Content-length: ' . filesize($file));
header('Content-disposition: attachment; filename=' . $doc . '.' . $doctype);
readfile($file);
