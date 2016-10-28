<?

$langs = [
    ['lang' => 'php', 'cmd' => '%path %s'],
    ['lang' => 'python', 'cmd' => '%path %s'],
    ['lang' => 'fake', 'cmd' => 'blah'],
    ['lang' => 'blah', 'cmd' => 'adf'],
    ['lang' => 'perl', 'cmd' => '%path %s'],
    ['lang' => 'c', 'which' => 'gcc', 'compile' => 'gcc -x c %s -o %output', 'cmd' => '%s'],
    ['lang' => 'java', 'which' => 'javac', 'compile' => 'javac %s', 'cmd' => 'java compiled/HelloWorldJava', 'tpl' => 'HelloWorldJava.java', 'compiledPath' => 'HelloWorldJava.java'],
];

for ($i=0; $i<count($langs); $i++) {

    $lang = $langs[$i]['lang'];
    $which = isset($langs[$i]['which']) ? $langs[$i]['which'] : $lang;
    $cmd=trim(`which $which`);

    if (strlen($cmd)==0) {
        continue;
    }
    
    //echo "Setting up $lang\n";


    if ( isset($langs[$i]['compiledPath']) ) {
        $compiledPath = 'compiled/' . $langs[$i]['compiledPath'];
    } else {
        $compiledPath = 'compiled/' . $lang ;
    }

    if ( isset($langs[$i]['tpl']) ) {
        $tpl = $langs[$i]['tpl'];
    } else {
        $tpl = $lang . '.tpl';
    }
    copy('templates/'. $tpl, $compiledPath);
    $script = file_get_contents($compiledPath);

    $foundLang = false;
    for ($j=$i+1; $j<count($langs); $j++) {

        $otherLang = $langs[$j]['lang'];
        $otherWhich = isset($langs[$j]['which']) ? $langs[$j]['which'] : $otherLang;

        $otherCmd = trim(`which $otherWhich`);
        
        if (strlen($otherCmd)==0) {
            continue;
        }

        $cwd = getcwd() . '/compiled/';
        $runCmd = str_replace('%s', $cwd . $otherLang, $langs[$j]['cmd']);
        $runCmd = str_replace('%path', $otherCmd, $runCmd);
        $compiled = str_replace('%s', $runCmd, $script);
        file_put_contents($compiledPath, $compiled);

        $foundLang = true;
        $i=$j-1;
        break;
    }

    if (!$foundLang) {
        $compiled = str_replace('%s', "", $script);
        file_put_contents($compiledPath, $compiled);
    }

    if (isset($langs[$i]['compile'])) {
        $compileCmd = $langs[$i]['compile'];
        $compileCmd = str_replace(['%path', '%s', '%output'], [$cmd, $compiledPath, $compiledPath], $compileCmd);
        passthru($compileCmd);

    }

}

passthru("php compiled/php");
