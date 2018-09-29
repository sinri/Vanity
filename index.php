<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/29
 * Time: 21:05
 */

//if(empty($_REQUEST['act']) || $_REQUEST['act']!='list')die("Believe in Jesus Christ, or Everlasting Death!");

require __DIR__ . '/config.php';

$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
if (!isset($config['permission'][$token])) {
    http_response_code(404);
    exit();
}

$permittedPaths = $config['permission'][$token];

$forbiddenPaths = null;
if (isset($config['forbidden'][$token])) {
    $forbiddenPaths = $config['forbidden'][$token];
}

$title = (isset($config['title']) ? $config['title'] : 'Vanity System');
$base_dir = (isset($config['store']) ? $config['store'] : '.');

function runDir($dir, $permittedPaths = null, $forbiddenPaths = null)
{
    global $token;
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                //echo "<p>filename: $file : filetype: " . filetype($dir . $file) . "</p>\n";
                //echo "<p>".$file."</p>";

                $targetPath = $dir . '/' . $file;
                if ($forbiddenPaths !== null) {
                    $forbidden = false;
                    foreach ($forbiddenPaths as $forbiddenPath) {
                        if (isPathMatched($targetPath, $forbiddenPath)) {
                            $forbidden = true;
                            break;
                        }
                    }
                    if ($forbidden) continue;
                }
                if ($permittedPaths !== null) {
                    $permitted = false;
                    foreach ($permittedPaths as $permittedPath) {
                        if (isPathMatched($targetPath, $permittedPath)) {
                            $permitted = true;
                            break;
                        }
                    }
                    if (!$permitted) continue;
                }

                echo "<div style='margin: 20px;font-family: monospace;'>";
                if ($file == '.' || $file == '..') {
                } else {
                    if (filetype($dir . "/" . $file) == 'dir') {
                        echo "<p>[+]&nbsp;";
                        echo "<a href='index.php?token=" . urlencode($token) . "&path=" . $dir . '/' . $file . "'>" . $file . "</a>";
                        //echo $file;
                        echo "</p>";
                        //runDir($dir."/".$file);
                    } else {
                        echo "<p>[-]&nbsp;";
                        echo "<a href='" . $dir . '/' . $file . "' target='_blank'>" . $file . "</a>";
                        echo "</p>";
                    }
                }
                echo "</div>";
            }
            closedir($dh);
        }
    }
}

function isPathMatched($path, $standardPath)
{
    $components = explode("/", $path);
    $tmp = "";
    for ($i = 0; $i < count($components); $i++) {
        $tmp .= ($i > 0 ? "/" : "") . $components[$i];
        if (fnmatch($standardPath, $tmp)) {
            //echo "DEBUG: now Path is Matched($path,$standardPath)".PHP_EOL;
            return true;
        }
    }
    return false;
}

?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
</head>
<body>
<h1><?php echo $title; ?></h1>
<hr>
<?php
// Open a known directory, and proceed to read its contents
$dir = $base_dir;
if (
    !empty($_REQUEST['path'])
    && stristr($_REQUEST['path'], '..') === FALSE
    && strpos($_REQUEST['path'], $base_dir) === 0
) {
    $dir = $_REQUEST['path'];
}
echo "<p>You are here: $dir ";
if ($dir === '.' || $dir === $base_dir) {
    echo "(Root Store Directory)";
} else {
    echo "| " . "<a href='index.php?token=" . urlencode($token) . "&path=" . dirname($dir) . "'>Parent Directory</a>";
}
echo "</p>";

runDir($dir, $permittedPaths, $forbiddenPaths);
?>
<hr>
<p>Powered by <a href="https://github.com/sinri/Vanity" target="_blank">Project Vanity</a>, published under <a
            href="https://github.com/sinri/Vanity/blob/master/LICENSE" target="_blank">AGPL-3.0</a>.</p>
<p>All Hail Sinri Edogawa.</p>
</body>
</html>
