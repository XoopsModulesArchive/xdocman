<?php

/***** XDM_UploadDocFileForm ()              *********************************
 * @param $langpreselect
 */

function XDM_UploadDocFileForm($langpreselect)
{
    global $xoopsModuleConfig;

    global $xdm_language_list;

    //$path          = $xoopsModuleConfig['xoops_path']."/modules/";

    $modules_form = new XoopsThemeForm('Modules form', 'modules_form', 'index.php');

    $modules_form->setExtra('enctype="multipart/form-data"');

    $lang_select = new XoopsFormSelect('Language', 'language');

    foreach ($xdm_language_list as $lkey => $lvalue) {
        $lang_select->addOption($lkey, $lkey . ' -> ' . $lvalue);
    }

    if (!empty($langpreselect)) {
        $lang_select->setValue($langpreselect);
    }

    $file_select = new XoopsFormFile('Doc file to upload', 'doc_file', 5000000);

    $submit_button = new XoopsFormButton('Upload Document File', 'poll_submit', _SUBMIT, 'submit');

    $op_hidden = new XoopsFormHidden('op', 'upload_doc_file');

    $modules_form->addElement($lang_select);

    $modules_form->addElement($file_select);

    $modules_form->addElement($submit_button);

    $modules_form->addElement($op_hidden);

    $modules_form->display();
}

/***** XDM_UploadDocFile ()                  *********************************
 * @param $langpreselect
 */

function XDM_UploadDocFile($langpreselect)
{
    global $xoopsModuleConfig;

    global $MAX_FILE_SIZE;

    global $doc_file;

    global $poll_submit;

    global $op;

    global $xoops_upload_file;

    global $HTTP_POST_FILES;

    $dirname = '';

    $is_online = false;

    if (!empty($HTTP_POST_FILES['doc_file']['name'])) {
        $destfilename = $HTTP_POST_FILES['doc_file']['name'];

        $srcfilename = $HTTP_POST_FILES['doc_file']['tmp_name'];

        if (XDM_ParseFilename($destfilename, $dirname, $is_online)) {
            XDM_Uploading($srcfilename, $destfilename, $langpreselect, $dirname, $is_online);
        } else {
            echo "$destfilename is an invalid file for uploading<br>\n";
        }
    }

    XDM_UploadDocFileForm($langpreselect);
}

/***** XDM_ParseFilename ()                  *********************************
 * @param $filename
 * @param $dirname
 * @param $is_online
 * @return bool
 */

function XDM_ParseFilename($filename, &$dirname, &$is_online)
{
    $file_is_ok = true;

    $pieces = explode('.', $filename);

    $name = explode('_', $pieces[0]);

    $test = explode('-', $name[0]);

    if (isset($name[1])) {
        if ('dm' == $name[1]) {
            $is_online = true;
        }
    }

    $dirname = $name[0];

    if (('chm' == $pieces[1]) || ('pdf' == $pieces[1])
        || ('zip' == $pieces[1])
        || ('tar' == $pieces[1])) {
    } else {
        $file_is_ok = false;
    }

    if (2 != mb_strlen($test[0])) {
        $file_is_ok = false;
    }

    if (3 != mb_strlen($test[1])) {
        $file_is_ok = false;
    }

    return ($file_is_ok);
}

/***** XDM_Uploading ()                      *********************************
 * @param $srcfile
 * @param $file
 * @param $language
 * @param $dirname
 * @param $is_online
 */

function XDM_Uploading($srcfile, $file, $language, $dirname, $is_online)
{
    $pathSep = mb_strstr(PHP_OS, 'WIN') ? '\\' : '/';

    $destdir = '..' . $pathSep . 'docs' . $pathSep . $language . $pathSep . $dirname;

    XDM_CreateDirectory($destdir);

    $destfile = '../docs/' . $language . '/' . $dirname . '/' . $file;

    if (copy($srcfile, $destfile)) {
        if ($is_online) {
            $filename = explode('.', $file);

            $gzfile = '../docs/' . $language . '/' . $dirname . '/' . $filename[0] . '.tar.gz';

            $tarfile = '../docs/' . $language . '/' . $dirname . '/' . $filename[0] . '.tar';

            $cmd_gzip = "gzip -f -d $gzfile >" . $destdir . $pathSep . 'gunzip.txt';

            $z_error = system($cmd_gzip);

            $cmd_tar = 'tar --directory=' . $destdir . ' -xvf ' . $tarfile . ' >' . $destdir . $pathSep . 'tar.txt';

            $t_error = system($cmd_tar);

            echo "<b> $file was successfully uploaded and unzipped </b> <br>";

        // echo "z_error = $z_error = $cmd_gzip<br>\n";
            // echo "t_error = $t_error = $cmd_tar<br>\n";
        } else {
            echo "<b> $file was successfully uploaded</b> <br>";
        }
    } else {
        echo "Could not copy file <br>\n";

        echo "Check access rights <br>\n";
    }
}

/***** XDM_StyleUploading ()                 *********************************
 * @param $srcfile
 * @param $file
 */

function XDM_StyleUploading($srcfile, $file)
{
    $pathSep = mb_strstr(PHP_OS, 'WIN') ? '\\' : '/';

    $destfile = '../' . $file;

    if (copy($srcfile, $destfile)) {
        echo "<b> $file was successfully uploaded</b> <br>";
    } else {
        echo "Could not copy file <br>\n";

        echo "Check access rights <br>\n";
    }
}

/***** XDM_CreateDirectory (...)             *********************************
 * @param $dirname
 * @return int
 */

function XDM_CreateDirectory($dirname)
{
    $path = '';

    $dir = preg_split('[/|\\]', $dirname);

    for ($i = 0, $iMax = count($dir); $i < $iMax; $i++) {
        $path .= $dir[$i] . '/';

        if (!is_dir($path)) {
            @mkdir($path, 0777);

            @chmod($path, 0777);
        }
    }

    if (is_dir($dirname)) {
        return 1;
    }

    return 0;
}

/***** XDM_UploadStyleFileForm ()            **********************************/

function XDM_UploadStyleFileForm()
{
    global $xoopsModuleConfig;

    $modules_form = new XoopsThemeForm('Modules form', 'modules_form', 'index.php');

    $modules_form->setExtra('enctype="multipart/form-data"');

    $file_select = new XoopsFormFile('Stylesheet file to upload', 'style_file', 100000);

    $submit_button = new XoopsFormButton('Upload Stylesheet File', 'poll_submit', _SUBMIT, 'submit');

    $op_hidden = new XoopsFormHidden('op', 'upload_style_file');

    $modules_form->addElement($file_select);

    $modules_form->addElement($submit_button);

    $modules_form->addElement($op_hidden);

    $modules_form->display();
}

/***** XDM_UploadStyleFile ()                **********************************/

function XDM_UploadStyleFile()
{
    global $xoopsModuleConfig;

    global $MAX_FILE_SIZE;

    global $doc_file;

    global $poll_submit;

    global $op;

    global $xoops_upload_file;

    global $HTTP_POST_FILES;

    $dirname = '';

    $is_online = false;

    if (!empty($HTTP_POST_FILES['style_file']['name'])) {
        $destfilename = $HTTP_POST_FILES['style_file']['name'];

        $srcfilename = $HTTP_POST_FILES['style_file']['tmp_name'];

        XDM_StyleUploading($srcfilename, $destfilename);
    }

    XDM_UploadStyleFileForm();
}

/******************************************************************************/
