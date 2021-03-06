<?php
/**
 * 壓縮檔讀取 FIXME: 合併到raw.php
 *
 * @version 0.1.3
 * @author TCC <john987john987@gmail.com>
 * @date 2019-09-01
 * @since 0.1.1 2017-10-01 TCC: 加註解
 * @since 0.1.1 2017-10-01 TCC: 功能單一化，類似raw.php
 * @since 0.1.2 2017-10-04 TCC: 多層ZIP草稿
 * @since 0.1.3 2019-09-01 TCC: 簡單調整 PHP 排版
 *
 * @param {Number} $_POST['id'] || $_GET['id'] ZIP檔案ID
 * @param {String} $_POST['file'] || $_GET['file'] ZIP檔案子路徑
 */
include_once 'functions.inc';

header("Content-Type: application/json;charset=utf-8");

$id = isset($_POST['id']) ? $_POST['id'] : isset($_GET['id']) ? $_GET['id'] : null;

$file_location = dirname(dirname(__FILE__)) . "\\files\\" . $id;

if (!file_exists($file_location)) { // 不存在
    http_response_code(404);
    exit();
}

$zip_archive = new ZipArchive();

$zip_archive->open($file_location);

$file_count = $zip_archive->numFiles;

if ($file_count == 0) { // 可能不是ZIP
    http_response_code(404);
    exit();
}

$file_name = isset($_POST['file']) ? $_POST['file'] : (isset($_GET['file']) ? $_GET['file'] : "/");

if (substr($file_name, -1) != "/") { // 假如路徑不是資料夾
    $fp = $zip_archive->getStream($file_name);

    if ($fp) {
        $content = stream_get_contents($fp);
        fclose($fp);

        header("Content-Type: " . getFileMimeType("", $file_name));

        echo $content;

        exit();
    } else {
        http_response_code(404);
        exit();
    }
} else {
    http_response_code(403);
    exit();
}
