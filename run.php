<?php

$packagesDir = __DIR__.'/packages';

foreach (glob(__DIR__.'/data/*', GLOB_ONLYDIR) as $package) {
    $directory = rtrim($package, '/\\');
    $package = explode('-', basename($directory))[0];

    if (!is_dir($packagesDir.'/'.$package)) {
        mkdir($packagesDir.'/'.$package);
    }

    echo $package."\n";
    echo "---------------------\n";

    foreach (glob($directory.'/data/*', GLOB_ONLYDIR) as $childDirectory) {
        $fileName = $package == 'locale' ? 'locales' : $package;
        $data = require $childDirectory.'/'.$fileName.'.php';
        $code = basename($childDirectory);
        $mainCode = explode('_', $code)[0];

        if (!is_dir($packagesDir.'/'.$package.'/'.$mainCode)) {
            mkdir($packagesDir.'/'.$package.'/'.$mainCode);
        }

        file_put_contents($packagesDir.'/'.$package.'/'.$mainCode.'/'.$code.'.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $content = "<?php\n\nreturn [\n";
        foreach ($data as $key => $value) {
            $content.= "    '$key' => '$value',\n";
        }
        $content.= "];\n";
        file_put_contents($packagesDir.'/'.$package.'/'.$mainCode.'/'.$code.'.php', $content);
    }

    echo "\n\n";
}
