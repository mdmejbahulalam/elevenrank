<?php
// Zips a folder: php deploy/zip.php <source-dir> <target.zip>
[, $source, $target] = $argv + [null, null, null];

if (! $source || ! $target || ! is_dir($source)) {
    fwrite(STDERR, "Usage: php deploy/zip.php <source-dir> <target.zip>\n");
    exit(1);
}

$source = rtrim(str_replace('\\', '/', realpath($source)), '/');
$zip = new ZipArchive;

if (true !== $zip->open($target, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    fwrite(STDERR, "Cannot create $target\n");
    exit(1);
}

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);

foreach ($files as $file) {
    $path = str_replace('\\', '/', $file->getPathname());
    $name = ltrim(substr($path, strlen($source)), '/');
    $file->isDir() ? $zip->addEmptyDir($name) : $zip->addFile($path, $name);
}

$zip->close();
echo "Wrote $target\n";
