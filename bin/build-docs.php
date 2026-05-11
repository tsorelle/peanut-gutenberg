<?php
$source = $argv[1] ?? 'all';
/*if ($argc < 2) {
    print "Usage: php bin/build-docs.php all | [file name]\n";
    exit;
}*/
// update this to match your project structure
$projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/..'));
$bootstrapDir = '/web.root/tq-peanut/bootstrap';
$indexFileUrl = '/peanut/docs';
// $projectFileRoot =   str_replace('\\','/', realpath(__DIR__.'/..')).'/web.root/';
print "Project root: $projectFileRoot\n";
include_once $projectFileRoot.$bootstrapDir.'/definitions.php';

$docPath = DIR_APPLICATION."/docs";
$docUrl = URL_APPLICATION."/docs";
$srcPath = "$projectFileRoot/doc/public";

include_once DIR_PEANUT_ROOT."/src/tops/sys/TStrings.php";
include_once DIR_PEANUT_ROOT."/src/tops/sys/TParseDown.php";

/*print "Source path: $srcPath\n";
print "Doc path: $docPath\n";*/

if($source === 'all') {
    $sections = [];
    $dirs = scandir($srcPath);
    $files = [];

    foreach ($dirs as $section) {
        if ($section == '.'
            || $section == '..'
            || (!is_dir("$srcPath/$section"))
        ) {
            continue;
        }

        $filenames = scandir("$srcPath/$section");
        foreach ($filenames as $filename) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext === 'md') {
                $files[] = "$section/$filename";
            }
        }
        if (count($files) > 0) {
            $sections[] = $section;
        }

    }


}
else {
    $files = $argv;
    array_shift($files);
}

$template = file_get_contents("$srcPath/doc-template.html");
if (empty($template)) {
    print "Template not found.\n";
    exit;
}
foreach($files as $filename) {
    if (str_starts_with($filename,'/')) {
        $filename = substr($filename, 1);
    }
    $srcFile = "$srcPath/".$filename;
    print  "Processing: $srcFile...";
    // $filename = pathinfo($srcFile, PATHINFO_FILENAME);
    $outFile = str_ireplace('.md','.html',"$docPath/$filename");
    $content = \Tops\sys\TParseDown::ParseMdFile($srcFile);
    $content = str_ireplace('../../index.md', $indexFileUrl, $content);
    $content = str_ireplace('../index.md', $indexFileUrl, $content);
    $content = str_ireplace('.md', '.html', $content);
    print "\n";
    $title = 'Peanut Documentation';
    $content = sprintf($template,$title,$content);
    print "\nWriting: $outFile...";
    $outDir = dirname($outFile);
    if (!is_dir($outDir) && !mkdir($outDir, 0777, true) && !is_dir($outDir)) {
        throw new RuntimeException("Failed to create directory: $outDir");
    }
    file_put_contents($outFile,$content);
    print "\n";
}

if ($source === 'all') {
    $srcFile = "$srcPath/index.md";
    $outFile = DIR_APPLICATION."/content/pages/doc-index.php";
    print  "Processing: $srcFile...";
    $content = \Tops\sys\TParseDown::ParseMdFile($srcFile);
    $content = str_ireplace('.md', '.html', $content);
    foreach ($sections as $section) {
        $search = 'href="' . $section . '/';
        $fix = 'href="'. $docUrl . '/' . $section. '/'  ;
        $content = str_ireplace($search, $fix, $content);

        $imageDirSrc = "$srcPath/$section/img";
        if (!is_dir($imageDirSrc)) {
            continue;
        }
        $imageDirDest = "$docPath/$section/img";
        if (!is_dir($imageDirDest) && !mkdir($imageDirDest, 0777, true) && !is_dir($imageDirDest)) {
            throw new RuntimeException("Failed to create directory: $imageDirDest");
        }
        $imageFiles = scandir($imageDirSrc);
        foreach ($imageFiles as $imageFile) {
            if (str_starts_with($imageFile,'.')) {
                continue;
            }
            $srcFile = "$imageDirSrc/$imageFile";
            $destFile = "$imageDirDest/$imageFile";
            print "Copying: $imageFile...\n";
            copy($srcFile, $destFile);
        }
    }
    print "\nWriting index file: $outFile...\n";
    file_put_contents($outFile,$content);
}

print "Done.\n";

