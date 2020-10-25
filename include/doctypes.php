<?php
// $Id: doctypes.php,v 1.1 2004/12/06 18:13:29 robekras Exp $
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

$docTypes = [
    'chm' => [
        'desc' => _XDM_SP_HTMLHELP,
        'mime' => 'application/octet-stream',
    ],

    'zip' => [
        'desc' => _XDM_SP_XHTML_CHUNK,
        'mime' => 'application/octet-stream',
    ],

    'pdf' => [
        'desc' => _XDM_SP_PDF_A4_DS,
        'mime' => 'application/pdf',
    ],

    'pdf_a4_ss' => [
        'desc' => _XDM_SP_PDF_A4_SS,
        'mime' => 'application/pdf',
    ],

    'pdf_a4_ds' => [
        'desc' => _XDM_SP_PDF_A4_DS,
        'mime' => 'application/pdf',
    ],

    'pdf_lt_ss' => [
        'desc' => _XDM_SP_PDF_LT_SS,
        'mime' => 'application/pdf',
    ],

    'pdf_lt_ds' => [
        'desc' => _XDM_SP_PDF_LT_DS,
        'mime' => 'application/pdf',
    ],
];
