<?php
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
// ------------------------------------------------------------------------- //
// Author: Robert Kraske                                                     //
// Site: https://www.xoops.org                                                //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

$modversion['name'] = _MI_DOCUMENTS_NAME;
$modversion['version'] = 0.2;
$modversion['author'] = 'Robert Kraske';
$modversion['description'] = _MI_DOCUMENTS_DESC;
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GPL see LICENSE';
$modversion['help'] = '';
$modversion['official'] = 0;
$modversion['image'] = 'images/xdocman_logo.png';
$modversion['dirname'] = 'xdocman';

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'xdocman_hits';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Search
$modversion['hasSearch'] = 0;

// Menu
$modversion['hasMain'] = 1;

// Smarty
$modversion['use_smarty'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'docs_index.html';
$modversion['templates'][1]['description'] = 'Start Page';

$modversion['templates'][2]['file'] = 'manual_index.html';
$modversion['templates'][2]['description'] = 'Manual Page';

// General settings configuration

// Blocks
$modversion['blocks'][1]['file'] = 'doc_menu.php';
$modversion['blocks'][1]['name'] = 'Doc_Links';
$modversion['blocks'][1]['description'] = 'Builds the navigation';
$modversion['blocks'][1]['show_func'] = 'show_menu';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'lid';
$modversion['comments']['pageName'] = 'manual.php';              // redirect file
$modversion['comments']['extraParams'] = ['doc', 'file', 'lang', 'id'];
// Comment callback functions
$modversion['comments']['callbackFile'] = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'docs_com_approve';
$modversion['comments']['callback']['update'] = 'docs_com_update';

// Notification

$modversion['hasNotification'] = 0;
