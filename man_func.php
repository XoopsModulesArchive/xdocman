<?php
// $Id: man_func.php,v 1.1 2004/12/06 18:13:29 robekras Exp $
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

/***** BuildStartPage (...)                  *********************************
 * @param $lang
 */
/*
 */
function BuildStartPage($lang)
{
    global $xoopsTpl;

    global $docTypes;

    global $xoopsDB;

    /* $docList will be in the following form:
     * $docList['xu-001']['title']       the title to show (language dependent)
     * $docList['xu-001']['version']     the version of this document
     * $docList['xu-001']['date']        the date of this document
     * $docList['xu-001']['lang']        the language of the document
     * $docList['xu-001']['langlist']    the list of available languages for that document
     * $docList['xu-001']['chm']         we have it as compressed html help file
     * $docList['xu-001']['zip']         we have a zipped chunked xhtml file
     * $docList['xu-001']['gz']          we have a tar.gz chunked xhtml file
     * $docList['xu-001']['pdf']         we have a normal ds A4 pdf file (should be dropped in the future)
     * $docList['xu-001']['pdf_a4_ss']   we have a single sided A4 pdf file (future version)
     * $docList['xu-001']['pdf_a4_ds']   we have a double sided A4 pdf file (future version)
     * $docList['xu-001']['pdf_lt_ss']   we have a single sided letter pdf file (future version)
     * $docList['xu-001']['pdf_lt_ds']   we have a double sided letter pdf file (future version
     *
     * $langList['en']                   number of documents for that language
     */

    $xoopsTpl->assign('doctypes', $docTypes);  // provide the document info list to smarty

    $docList = [];

    $langList = [];

    $textList = [];

    $textList['ohits'] = _XDM_SP_ONLINEHITS;

    $textList['downloads'] = _XDM_SP_DLHITS;

    GetDocumentList($docList, $lang);

    GetDocumentLanguages($docList, $langList);

    GetDocumentInfo($docList/*, $lang*/);

    CreateProgressInfoImage($docList);

    $xoopsTpl->assign('langbar', BuildStartPageLanguageBar($lang, $langList));  // provide the document info list to smarty

    foreach ($docList as $key => $doc) {
        $filename = 'docs/' . $doc['lang'] . "/$key/$key";

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xdocman_hits') . " WHERE doc='" . $key . "' AND language='" . $doc['lang'] . "'";

        $result = $xoopsDB->queryF($sql);

        $myrow = [];

        while (false !== ($hitrow = $xoopsDB->fetchArray($result))) {
            $myrow[$hitrow['doctype']] = $hitrow['hits'];
        }

        foreach ($docTypes as $dtkey => $doctype) {
            if (file_exists($filename . '.' . $dtkey)) {
                $docList[$key][$dtkey]['size'] = number_format(filesize($filename . '.' . $dtkey) / 1000, 0, '', ',');

                if (array_key_exists($dtkey, $myrow)) {
                    $docList[$key][$dtkey]['hits'] = $myrow[$dtkey];
                } else {
                    $docList[$key][$dtkey]['hits'] = 0;
                }
            }
        }

        if (array_key_exists('online', $myrow)) {
            $docList[$key]['online']['hits'] = $myrow['online'];
        } else {
            $docList[$key]['online']['hits'] = 0;
        }
    }

    $xoopsTpl->assign('doclist', $docList);    // provide the document info list to smarty
    $xoopsTpl->assign('langlist', $langList);  // provide the document language list to smarty
    $xoopsTpl->assign('textlist', $textList);  // provide the text list to smarty
}

/***** BuildStartPageLanguageBar (...)       *********************************
 * @param $lang
 * @param $langList
 * @return string
 */
/*
 */
function BuildStartPageLanguageBar($lang, $langList)
{
    global $xdm_language_list;

    $html = '<form method="post" action="index.php" class="thin" name="newlanguage">' . "\n";

    $html .= '<input type="hidden" name="lang" value="' . $lang . '">' . "\n";

    $html .= '<select name="newlang" class="small">' . "\n";

    foreach ($xdm_language_list as $lkey => $lvalue) {
        if (isset($langList[$lkey])) {
            if ($lkey == $lang) {
                $html .= '  <option selected value="' . $lkey . '">' . $lvalue . "</option>\n";
            } else {
                $html .= '  <option value="' . $lkey . '">' . $lvalue . "</option>\n";
            }
        }
    }

    $html .= '</select>' . "\n";

    $html .= '<input type="submit" name="Select" value="Select">' . "\n";

    $html .= '</form>' . "\n";

    return ($html);
}

/***** BuildLanguageBar (...)                *********************************
 * @param $lang
 * @param $langList
 * @return string
 */
/*
 */
function BuildLanguageBar($lang, $langList)
{
    global $xdm_language_list;

    $html = '<form method="post" action="manual.php" class="thin" name="newlanguage">' . "\n";

    $html .= '<input type="hidden" name="lang" value="' . $lang . '">' . "\n";

    $html .= '<select name="newlang" class="small">' . "\n";

    foreach ($xdm_language_list as $lkey => $lvalue) {
        if (isset($langList[$lkey])) {
            if ($lkey == $lang) {
                $html .= '  <option selected value="' . $lkey . '">' . $lvalue . "</option>\n";
            } else {
                $html .= '  <option value="' . $lkey . '">' . $lvalue . "</option>\n";
            }
        }
    }

    $html .= '</select>' . "\n";

    $html .= '<input type="submit" name="Select" value="Select">' . "\n";

    $html .= '</form>' . "\n";

    return ($html);
}

/***** BuildDocStartPage (...)               *********************************
 * @param $doc
 * @param $lang
 */
/* Builds the start page of a specific document
 */
function BuildDocStartPage($doc, $lang)
{
    global $xoopsTpl;

    $xoopsTpl->assign('langbar', BuildHeaderBar($doc, 'index.html', $lang, ''));

    $xoopsTpl->assign('content', file_get_contents("docs/$lang/" . $doc . '/index.html'));

    $xoopsTpl->assign('commentbar', BuildFooterBar($doc, 'index.html', $lang, ''));

    BuildComments($doc, 'index.html', $lang, '');
}

/***** BuildDocPage (...)                    *********************************
 * @param        $doc
 * @param        $file
 * @param        $lang
 * @param        $id
 * @param string $msg
 */
/*
 */
function BuildDocPage($doc, $file, $lang, $id, $msg = '')
{
    global $xoopsTpl;

    $xoopsTpl->assign('langbar', BuildHeaderBar($doc, $file, $lang, $id, $msg));

    $xoopsTpl->assign('content', file_get_contents("docs/$lang/$doc/$file"));

    $xoopsTpl->assign('commentbar', BuildFooterBar($doc, $file, $lang, $id));

    //echo file_get_contents ("docs/$lang/$doc/$file");

    // and now get the comments for that page

    BuildComments($doc, $file, $lang, '');
}

/***** BuildHeaderBar (...)                  *********************************
 * @param        $doc
 * @param        $file
 * @param        $lang
 * @param        $id
 * @param string $msg
 * @return string
 */
/*
 */
function BuildHeaderBar($doc, $file, $lang, $id, $msg = '')
{
    global $xdm_language_list;

    $html = '<form method="post" action="manual.php" class="thin" name="newlanguage">' . "\n";

    $html .= '<input type="hidden" name="lang" value="' . $lang . '">' . "\n";

    $html .= '<input type="hidden" name="doc" value="' . $doc . '">' . "\n";

    $html .= '<input type="hidden" name="file" value="' . $file . '">' . "\n";

    $html .= '<input type="hidden" name="id" value="' . $id . '">' . "\n";

    $html .= '<select name="newlang" class="small">' . "\n";

    foreach ($xdm_language_list as $lkey => $lvalue) {
        if ($lkey == $lang) {
            $html .= '  <option selected value="' . $lkey . '">' . $lvalue . "</option>\n";
        } else {
            $html .= '  <option value="' . $lkey . '">' . $lvalue . "</option>\n";
        }
    }

    $html .= '</select>' . "\n";

    $html .= '<input type="submit" name="Select" value="Select">' . "\n";

    $html .= $msg;

    $html .= '</form>' . "\n";

    return ($html);
}

/***** BuildFooterBar (...)                  *********************************
 * @param $doc
 * @param $file
 * @param $lang
 * @param $id
 * @return string
 */
/*
 */
function BuildFooterBar($doc, $file, $lang, $id)
{
    $html = '<form method="post" action="comment.php" class="thin" name="comment">' . "\n";

    $html .= '<input type="hidden" name="lang" value="' . $lang . '">' . "\n";

    $html .= '<input type="hidden" name="doc" value="' . $doc . '">' . "\n";

    $html .= '<input type="hidden" name="file" value="' . $file . '">' . "\n";

    $html .= '<input type="hidden" name="id" value="' . $id . '">' . "\n";

    $html .= '<input type="submit" name="comment" value="add a new comment">' . "\n";

    $html .= '</form>' . "\n";

    return ($html);
}

/***** GetDocumentList (...)                 *********************************
 * @param $docList
 * @param $lang
 */
/*
 */
function GetDocumentList(&$docList, $lang)
{
    // build the document list for en directory

    $dirList = glob('docs/en/x*', GLOB_ONLYDIR);

    foreach ($dirList as $key => $dir) {
        $path = explode('/', $dir);

        $docList[$path[2]] = [];

        $docList[$path[2]]['lang'] = 'en';
    }

    // now check whether we have the documents for the selected language

    if ('en' != $lang) {
        $dirList = glob("docs/$lang/x*", GLOB_ONLYDIR);

        foreach ($dirList as $key => $dir) {
            $path = explode('/', $dir);

            if (isset($docList[$path[2]])) {         // if this doc is within the original (english) list
                $docList[$path[2]]['lang'] = $lang;     // then overwrite the language info
            }
        }
    }
}

/***** GetDocumentLanguages (...)            *********************************
 * @param $docList
 * @param $langList
 */
/*
 */
function GetDocumentLanguages(&$docList, &$langList)
{
    $lang = glob('docs/*', GLOB_ONLYDIR);                        // get the language dirs

    foreach ($lang as $key => $languageDir) {                     // for eache language dir
        $ld = explode('/', $languageDir);                     // get the language (->ld[1])
        $dirList = glob('docs/' . $ld[1] . '/x*', GLOB_ONLYDIR);       // get the documents within the language dir

        foreach ($dirList as $key => $dir) {                        // for every document
            $path = explode('/', $dir);

            if (!isset($docList[$path[2]]['langlist'])) {            // do we have already the langlist array?
                $docList[$path[2]]['langlist'] = [];              // no, then create it.
            }

            $docList[$path[2]]['langlist'][] = $path[1];    // append the language

            if (isset($langList[$ld[1]])) {                          // is there already an entry?
                $langList[$ld[1]] += 1;                                 // increment the number of documents for that language
            } else {
                $langList[$ld[1]] = 1;                                  // set it to one
            }
        }
    }
}

/***** GetDocumentInfo (...)                 *********************************
 * @param $docList
 */
/* Read the title, revnumber, revdate and status of documents
 * and will be read into the $doclist
 */
function GetDocumentInfo(&$docList)
{
    foreach ($docList as $doc => $val) {
        $lang = $val['lang'];

        $infofile = "docs/$lang/$doc/docinfo.xml";

        if (file_exists($infofile)) {
            $data = file_get_contents($infofile);

            //$parser   = xml_parser_create ("UTF-8");
            $parser = xml_parser_create('ISO-8859-1');  // at the moment we can only encode for ISO-8859-1

            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

            xml_parse_into_struct($parser, $data, $values, $tags);       // parse the xhtml file

            xml_parser_free($parser);

            $docList[$doc]['title'] = $values[$tags['title'][0]]['value'];

            if (isset($tags['revnumber'])) {
                $docList[$doc]['version'] = $values[$tags['revnumber'][0]]['value'];
            }

            if (isset($tags['revdate'])) {
                $docList[$doc]['date'] = $values[$tags['revdate'][0]]['value'];
            }

            if (isset($tags['status'])) {
                $docList[$doc]['status'] = $values[$tags['status'][0]]['value'];
            }
        }
    }
}

/***** CreateProgressInfoImage (...)         *********************************
 * @param $docList
 */
/* Read the title, revnumber, revdate and status of documents
 * and will be read into the $doclist
 */
function CreateProgressInfoImage($docList)
{
    foreach ($docList as $doc => $val) {
        if (isset($docList[$doc]['status'])) { // if we have a status for the doc
            $imagename = 'images/progress/' . $docList[$doc]['lang'] . '_' . $doc . '.png';

            $im = imagecreatetruecolor(104, 18);

            $color = imagecolorallocate($im, 140, 140, 140);

            $color_red = imagecolorallocate($im, 250, 60, 60);

            $color_green = imagecolorallocate($im, 60, 250, 60);

            $color_text = imagecolorallocate($im, 0, 0, 0);

            imagefilledrectangle($im, 0, 0, 103, 17, $color);

            imagefilledrectangle($im, 2, 2, 101, 15, $color_red);

            if (ctype_digit($docList[$doc]['status'])) {  // if the status is a number
                $progress = (int)$docList[$doc]['status'];

                if ($progress > 100) {
                    $progress = 100;
                }

                imagefilledrectangle($im, 2, 2, 2 + $progress, 15, $color_green);

                imagestring($im, 1, 40, 5, $progress . ' %', $color_text);
            } elseif ('final' == $docList[$doc]['status']) {
                imagefilledrectangle($im, 2, 2, 2 + 100, 15, $color_green);

                imagestring($im, 1, 40, 5, 'final', $color_text);
            }

            imagepng($im, $imagename);

            imagedestroy($im);
        }
    }
}

/***** GetDocIdList (...)                    *********************************
 * @param $idlist
 * @param $doc
 * @param $file
 * @param $lang
 */
/*
 * Get the id list for the specified file
 */
function GetDocIdList(&$idlist, $doc, $file, $lang)
{
    $infofile = "docs/$lang/$doc/docinfo.xml";

    if (file_exists($infofile)) {
        $data = file_get_contents($infofile);

        //$parser   = xml_parser_create ("UTF-8");

        $parser = xml_parser_create('ISO-8859-1');

        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

        xml_parse_into_struct($parser, $data, $values, $tags);       // parse the xhtml file

        xml_parser_free($parser);

        $state = 0;

        foreach ($values as $index => $tag) {
            if (('file' == $tag['tag']) && ('close' == $tag['type'])) {
                $state = 0;

                unset($element);

                unset($text);
            }

            switch ($state) {
                case 0:
                    if (('file' == $tag['tag']) && ('open' == $tag['type'])) {
                        $state = 1;         // now look whether this is the right file name
                    }
                    break;
                case 1:                 // look for the right file
                    if ('name' == $tag['tag']) {
                        if ($tag['value'] == $file) {
                            $state = 2;       // it's the right file
                        } else {
                            $state = 0;       // it's not the right file
                        }
                    }
                    break;
                case 2:                 // collect the ids
                    if ('entry' == $tag['tag']) {
                        unset($element);

                        unset($text);

                        $idfound = false;
                    }

                    if ('id' == $tag['tag']) {
                        $id = $tag['value'];
                    }

                    if ('text' == $tag['tag']) {
                        $text = $tag['value'];
                    }

                    if ('element' == $tag['tag']) {
                        $element = constant('_XDM_DIID_' . mb_strtoupper($tag['value']));
                    }

                    if (isset($element) && isset($text)) {
                        $idlist[$id] = $element . ' : ' . $text;
                    }
                    break;
            }
        }
    }
}

/***** GetDocIdTitleText (...)               *********************************
 * @param $doc
 * @param $file
 * @param $lang
 * @param $refid
 * @return string
 */
/*
 * Get the title text for the specified id
 */
function GetDocIdTitleText($doc, $file, $lang, $refid)
{
    $infofile = "docs/$lang/$doc/docinfo.xml";

    if (file_exists($infofile)) {
        $data = file_get_contents($infofile);

        //$parser   = xml_parser_create ("UTF-8");

        $parser = xml_parser_create('ISO-8859-1');

        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

        xml_parse_into_struct($parser, $data, $values, $tags);       // parse the xhtml file

        xml_parser_free($parser);

        $state = 0;

        foreach ($values as $index => $tag) {
            if (('file' == $tag['tag']) && ('close' == $tag['type'])) {
                $state = 0;

                $idfound = false;

                unset($element);

                unset($text);
            }

            switch ($state) {
                case 0:
                    if (('file' == $tag['tag']) && ('open' == $tag['type'])) {
                        $state = 1;         // now look whether this is the right file name
                    }
                    break;
                case 1:                 // look for the right file
                    if ('name' == $tag['tag']) {
                        if ($tag['value'] == $file) {
                            $state = 2;       // it's the right file
                        } else {
                            $state = 0;       // it's not the right file
                        }
                    }
                    break;
                case 2:                 // collect the ids
                    if ('entry' == $tag['tag']) {
                        unset($element);

                        unset($text);

                        $idfound = false;
                    }

                    if ('id' == $tag['tag']) {
                        $idfound = ($tag['value'] == $refid);
                    }

                    if ('text' == $tag['tag']) {
                        $text = $tag['value'];
                    }

                    if ('element' == $tag['tag']) {
                        $element = constant('_XDM_DIID_' . mb_strtoupper($tag['value']));
                    }

                    if ($idfound && isset($element) && isset($text)) {
                        $idtitletext = $element . ' : ' . $text;

                        return ($idtitletext);
                    }
                    break;
            }
        }
    }

    return ('');
}

/***** BuildComments (...)                   *********************************
 * @param $doc
 * @param $file
 * @param $lang
 */
/*
 */
function BuildComments($doc, $file, $lang)
{
    global $xoopsDB;

    global $xoopsModule;

    global $xoopsTpl;

    global $xoopsModuleConfig;

    $myts = MyTextSanitizer::getInstance();

    $comments = [];

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_modid = ' . $xoopsModule->mid();

    $sql .= " AND com_exparams LIKE \"doc=$doc&amp;file=$file&amp;lang=$lang&amp;id=%\" ORDER BY com_id DESC;";

    $result = $xoopsDB->query($sql);

    $rowset = [];

    while (false !== ($row = $xoopsDB->fetchArray($result))) {
        $rowset[] = $row;
    }

    foreach ($rowset as $key => $val) {
        $comment = [];

        $comment['title'] = $val['com_title'];

        $comment['text'] = $myts->displayTarea($val['com_text']);

        $comment['date'] = formatTimestamp($val['com_created']);

        $comment['name'] = '';

        $comment['uid'] = $val['com_uid'];

        $comment['id'] = '';

        $comment['idtext'] = '';

        $exparams = explode('&amp;id=', $val['com_exparams']); // get the id part of com_exparams

        if (isset($exparams[1])) {
            $exparams = explode('&amp;', $exparams[1]);     // strip the &amp; from the end

            $comment['id'] = $exparams[0];

            $comment['idtext'] = GetDocIdTitleText($doc, $file, $lang, $exparams[0]); // get the real title text for the id
        }

        // var_dump ($comment);

        $comments[] = $comment;                       // append the comment to the list
    }

    $xoopsDB->freeRecordSet($result);

    // todo: optimize the foreach loop

    foreach ($comments as $index => $comment) {
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('users') . ' WHERE uid = ' . $comment['uid'] . ';';

        $result = $xoopsDB->query($sql);

        $rowset = [];

        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $rowset[] = $row;
        }

        foreach ($rowset as $key => $val) {
            $comments[$index]['name'] = $val['uname'];
        }

        $xoopsDB->freeRecordSet($result);
    }

    $xoopsTpl->assign('comments', $comments);
}

/******************************************************************************/
