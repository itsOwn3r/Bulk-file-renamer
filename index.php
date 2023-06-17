<?php

/**
 * Special thanks to ndeet and xc0d3rz for unzipper.
 * GitHub link: https://github.com/ndeet/unzipper
 * 
 * 
 *              OWN3R
 * 
 * 
 * The Unzipper extracts .zip or .rar archives and .gz files on webservers.
 * It's handy if you do not have shell access. E.g. if you want to upload a lot
 * of files (php framework or image collection) as an archive to save time.
 * As of version 0.1.0 it also supports creating archives.
 *
 * @author  Andreas Tasch, at[tec], attec.at
 * @license GNU GPL v3
 * @package attec.toolbox
 * @version 0.1.1
 */
define('VERSION', '0.1.1');

$timestart = microtime(TRUE);
$GLOBALS['status'] = array();
$download_link = [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mass File Renamer</title>
    <link rel="icon" href="https://own3r.me/icon.ico">
    <style>
        input.select {
            opacity: 0;
            margin-top: -10vh !important;
            z-index: 3;
            position: relative;
            cursor: pointer;
        }

        input[type=file]::file-selector-button {
            align-content: center;
            align-items: center;
            color: black;
            border-radius: 7px;
            cursor: pointer;
            display: inline-flex;
            font-size: 1.2rem;
            line-height: 1.6rem;
            text-align: center;
            white-space: nowrap;
            width: 100%;
        }

        body {
            background: rgb(0, 0, 8);
            background: linear-gradient(90deg, rgba(0, 0, 8, 1) 0%, rgb(18 18 126) 54%, rgb(28 151 177) 100%);
            overflow-x: hidden;
        }

        .download-link {
            text-align: center;
            font-size: 2rem;
            margin-top: 5rem;
            animation: animate 2s infinite ease-in-out;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: #1ac7c7;
            padding: 1rem;
            border: 3px #1bd343 solid;
            border-radius: 10px;
        }

        .download-link a:hover {
            color: #0088ff;

        }

        .download-link:hover {
            animation: none;
        }

        fieldset input {
            display: block;
            margin: 5px auto 35px auto !important;
            border-radius: 5px;
            padding: 5px;
            line-height: 1.4;
            border-color: #4224e7;
            font-size: 20px;
            font-weight: 600;
        }

        .file {
            width: 33%;
            text-align: center;
            margin: 0 auto;
        }

        .filename {
            margin: 25px auto 25px auto !important;
            font-weight: 600;

        }

        .red {
            color: red;
        }

        .file span {
            position: relative;
            z-index: 1;
            display: block;
            user-select: none;
            cursor: pointer;
        }

        header h1 {
            margin: 40px auto;
            text-align: center;
            color: white;
            font-size: 3rem;
            letter-spacing: 5px;
        }

        @keyframes animate {
            0% {
                transform: scale(1);
            }

            70% {
                transform: scale(1.5);
            }
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        <!--
        body {
            font-family: Arial, sans-serif;
            line-height: 150%;
        }

        label {
            display: block;
            margin-top: 20px;
        }

        fieldset {
            border: 0;
            background-color: #EEE;
            margin: 10px 0 10px 0;
            border-radius: 40px;
        }

        .select {
            padding: 4% 0 4% 0;
            margin-bottom: 0 !important;
            width: 98%;
        }

        form {
            text-align: center;
            width: 70%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            min-height: 70vh;
            justify-content: center;
        }

        .status {
            margin: 0;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 80%;
            background: #EEE;
            border: 1px dotted #DDD;
            width: fit-content;
            margin: 0 auto;
            border-radius: 5px;
        }

        .status--ERROR {
            background-color: red;
            color: white;
            font-size: 130%;
            padding: 25px;
        }

        .status--SUCCESS {
            background-color: green;
            font-weight: bold;
            color: white;
            font-size: 120%
        }

        .small {
            font-size: 0.7rem;
            font-weight: normal;
        }

        .version {
            font-size: 80%;
        }

        .form-field {
            border: 1px solid #AAA;
            padding: 8px;
            width: 280px;
        }

        .info {
            margin-top: 0;
            font-size: 80%;
            color: #777;
        }

        .submit {
            background-color: #378de5;
            border: 0;
            color: #ffffff;
            font-size: 20px;
            padding: 12px 30px;
            margin: 10px 0 20px 0;
            text-decoration: none;
            border-radius: 10px;
        }

        .submit:hover {
            background-color: #2c6db2;
            cursor: pointer;
        }
        input.query::placeholder {
                color: grey;
                font-weight: 600;
                text-align: center;
                letter-spacing: 1px;
            }
        @media only screen and (max-width: 992px) {
            body {
                line-height: 170%;
                font-size: 2rem;
            }

            form {
                width: 85%;
            }

            .file {
                width: 75%;
            }

            .file span {
                font-size: 16px;
            }

            label {
                margin-top: 80px;
            }

            input.select {
                margin-top: -5vh !important;
            }

            .status {
                font-size: 94%;
                border-radius: 15px;
                font-weight: 500;
                margin-top: 30px;
            }

            fieldset input {
                padding: 20px 80px;
                margin: 20px auto !important;
                font-size: 25px;
                font-weight: 600;
            }

            input[type=submit] {
                width: 50% !important;
                font-size: 25px;
                padding: 15px 50px;

            }

            input.query::placeholder {
                color: grey;
                font-size: 2em;
                font-weight: 600;
                text-align: center;
                letter-spacing: 4px;
            }
        }
        -->
    </style>
</head>
</head>

<?php

$unzipper = new Unzipper;

if (isset($_POST['query'])) {
    $query = strip_tags($_POST['query']);
}

if (isset($_FILES["zipfile"]["tmp_name"])) {
    $file_name = $_FILES['zipfile']['name'];
    $file_size = $_FILES['zipfile']['size'];
    if ($file_size > 32000000) {
        // 32 million Bytes == 30.5MB
        $GLOBALS['status'] = array('error' => 'Max File Size Allowed: 30 MB');
        $status = strtoupper(key($GLOBALS['status']));
        echo "<div class='center'><p class='status status--$status'>Status: Max File Size Allowed: 30 MB. <br /> </p></div>";
        die;
    }
    $file_tmp = $_FILES['zipfile']['tmp_name'];
    $file_type = $_FILES['zipfile']['type'];
    $extention = explode('.', $file_name);
    $size_of_extention = sizeof($extention);
    $file_ext = strtolower($extention[$size_of_extention - 1]);
    if ($file_ext == "zip" || $file_ext == "ZIP") {
        if (!is_dir("Files")) {
            mkdir("Files");
        }
        move_uploaded_file($_FILES["zipfile"]["tmp_name"], "Files/" . $file_name);
        $unzipper->prepareExtraction("Files/" . $file_name, strip_tags("Files"));
    } else {
        $GLOBALS['status'] = array('error' => 'Only .zip Files Supported!');
        $status = strtoupper(key($GLOBALS['status']));
        echo "<div class='center'><p class='status status--$status'>Status:Only .zip Files Supported!  <br /> </p></div>";
        die;
    }
}

$timeend = microtime(TRUE);
$time = round($timeend - $timestart, 4);

/**
 * Class Unzipper
 */
class Unzipper
{
    public $localdir = '.';
    public $zipfiles = array();

    public function __construct()
    {
        // Read directory and pick .zip, .rar and .gz files.
        if ($dh = opendir($this->localdir)) {
            while (($file = readdir($dh)) !== FALSE) {
                if (
                    pathinfo($file, PATHINFO_EXTENSION) === 'zip'
                ) {
                    $this->zipfiles[] = $file;
                }
            }
            closedir($dh);
            if (!empty($this->zipfiles)) {
                $GLOBALS['status'] = array('info' => 'Only .zip Supported, ready for extraction');
            } else {
                $GLOBALS['status'] = array('info' => 'No .zip or .gz or rar files found. So only zipping functionality available.');
            }
        }
    }

    /**
     * Prepare and check zipfile for extraction.
     *
     * @param string $archive
     *   The archive name including file extension. E.g. my_archive.zip.
     * @param string $destination
     *   The relative destination path where to extract files.
     */
    public function prepareExtraction($archive, $destination = '')
    {
        // Determine paths.
        if (empty($destination)) {
            // print_r($destination);
            $extpath = $this->localdir;
        } else {
            $extpath = $this->localdir . '/' . $destination;
            // print_r($extpath);
            // Todo: move this to extraction function.
            if (!is_dir($extpath)) {
                mkdir($extpath);
            }
        }
        // Only local existing archives are allowed to be extracted.
        // if (in_array($archive, $this->zipfiles)) {
        self::extract($archive, $extpath);
        // }
    }

    /**
     * Checks file extension and calls suitable extractor functions.
     *
     * @param string $archive
     *   The archive name including file extension. E.g. my_archive.zip.
     * @param string $destination
     *   The relative destination path where to extract files.
     */
    public static function extract($archive, $destination)
    {
        $ext = pathinfo($archive, PATHINFO_EXTENSION);
        switch ($ext) {
            case 'zip':
                self::extractZipArchive($archive, $destination);
                break;
        }
    }

    /**
     * Decompress/extract a zip archive using ZipArchive.
     *
     * @param $archive
     * @param $destination
     */
    public static function extractZipArchive($archive, $destination)
    {
        // Check if webserver supports unzipping.
        if (!class_exists('ZipArchive')) {
            $GLOBALS['status'] = array('error' => 'Error: Your PHP version does not support unzip functionality.');
            return;
        }

        $zip = new ZipArchive;
        if ($zip->open($archive) === TRUE) {
            // Check if destination is writable
            if (is_writeable($destination . '/')) {
                global $file_name;
                $zip->extractTo($destination);
                $zip->close();
                $GLOBALS['status'] = array('success' => 'Files unzipped successfully');
                rename_files($file_name);
            } else {
                $GLOBALS['status'] = array('error' => 'Error: Directory not writeable by webserver.');
            }
        } else {
            $GLOBALS['status'] = array('error' => 'Error: Cannot read .zip archive.');
        }
    }
}

/**
 * Class Zipper
 *
 * Copied and slightly modified from http://at2.php.net/manual/en/class.ziparchive.php#110719
 * @author umbalaconmeogia
 */
class Zipper
{
    /**
     * Add files and sub-directories in a folder to zip file.
     *
     * @param string $folder
     *   Path to folder that should be zipped.
     *
     * @param ZipArchive $zipFile
     *   Zipfile where files end up.
     *
     * @param int $exclusiveLength
     *   Number of text to be exclusived from the file path.
     */
    private static function folderToZip($folder, &$zipFile, $exclusiveLength)
    {
        $handle = opendir($folder);

        while (FALSE !== $f = readdir($handle)) {
            // Check for local/parent path or zipping file itself and skip.
            if ($f != '.' && $f != '..' && $f != basename(__FILE__)) {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);

                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     * Zip a folder (including itself).
     *
     * Usage:
     *   Zipper::zipDir('path/to/sourceDir', 'path/to/out.zip');
     *
     * @param string $sourcePath
     *   Relative path of directory to be zipped.
     *
     * @param string $outZipPath
     *   Relative path of the resulting output zip file.
     */
    public static function zipDir($sourcePath, $outZipPath)
    {
        $pathInfo = pathinfo($sourcePath);
        $parentPath = $pathInfo['dirname'];
        $dirName = $pathInfo['basename'];
        $z = new ZipArchive();
        $z->open($outZipPath, ZipArchive::CREATE);
        $z->addEmptyDir($dirName);
        if ($sourcePath == $dirName) {
            self::folderToZip($sourcePath, $z, 0);
        } else {
            self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
        }
        $z->close();
        $GLOBALS['status'] = array('success' => 'Successfully created archive ' . $outZipPath);
    }
}
?>

<body>
    <header>
        <h1><a href="https://own3r.me" target="_blank" style="border: inherit;">Own3r</a></h1>
    </header>
    <form action="" method="POST" enctype="multipart/form-data">
        <fieldset>
            <h1><a href="https://github.com/itsOwn3r/Bulk-file-renamer/" target="_blank" style="border: inherit;color: black;">Mass File Renamer</a></h1>
            <label for="zipfile">Select a .zip file which contains all your files. </label>
            <div class="file"><span class="submit">Select Your File Here... </span><input type="file" accept=".zip" name="zipfile" size="1" class="select"></div>
            <div class="filename"></div>
            <label for="query">Query that should be removed from all file's name:</label>
            <input type="text" name="query" class="query" placeholder="_en">
            <input type="submit" name="dounzip" class="submit" value="Let's Go" />
        </fieldset>
        <p class="status status--<?php echo strtoupper(key($GLOBALS['status'])); ?>">
            Status: <?php echo reset($GLOBALS['status']); ?><br />
            <span class="small">Processing Time: <?php echo $time; ?> seconds</span>
        </p>
    </form>
    <div class="download-link"><?php echo (sizeof($download_link) != 0) ?  $download_link[sizeof($download_link) - 1] : "" ?></div>
    <script>
        document.querySelector("input.select").addEventListener("change", () => {
            let file = document.querySelector("input.select");
            let fileType = file.files.item(0).name.split(".")
            let fileTypeLength = fileType.length - 1;
            let finalFileType = fileType[fileTypeLength]
            let notZip = false;
            if (finalFileType != "zip") {
                alert("Only .zip Files Supported!")
                notZip = true;
            }

            let fileSize = file.files.item(0).size
            if (fileSize > 32000000 || notZip === true) {
                fileSize > 32000000 && alert("Max file size suuported is: 30MB");
                document.querySelector("input.submit").style = "background-color: #53585f;"
                document.querySelector("input.submit").disabled = true
            } else {
                document.querySelector("input.submit").disabled = false
                document.querySelector("input.submit").style = ""
            }
            let fileName = file.files.item(0).name;
            document.querySelector(".filename").innerHTML = `Your File: <span class='red'>${fileName}</span>`
        })
    </script>

    <?php
    function rename_files($file_name){
        global $download_link;
        global $query;
        if (empty($query)) {
            $query = "_en";
        }
        unlink("Files/$file_name"); // removing the extrackted zip file
        $all_files = glob("Files/*");
        foreach ($all_files as $file) {
            if (strpos($file, $query)) {
                $new_name = str_replace($query, "", $file);
                rename($file, $new_name);
            }
        }
        // removing old zip files in the root directory
        array_map('unlink', glob("*.zip"));

        $zipfile = 'Own3r-' . date("Y-m-d--H-i") . '.zip';
        array_push($download_link, "<a href=" . $zipfile . " download>$zipfile</a>");
        Zipper::zipDir("Files", $zipfile);
        // Cleaning the mess
        function removeFiles($path){
            // this is for finding all the files, including the ones that start with "." and removing all of them so the "Files" directory will be removed;
            foreach (glob("$path/{,.}[!.,!..]*", GLOB_MARK | GLOB_BRACE) as $key => $value) {
                if (is_dir($value)) {
                    removeFiles($value);
                } else {
                    unlink($value);
                }
            }
            rmdir($path);
        }
        removeFiles("Files");
    }
    ?>
</body>

</html>