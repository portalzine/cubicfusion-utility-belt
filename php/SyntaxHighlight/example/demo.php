<!DOCTYPE html>
<html dir="ltr">
<head>
<meta charset="utf-8">
<title>Generic Syntax Highlighter with PHP</title>
<link src="css/colorize.css" type="text/css">
</head>
<body>
<?php

require_once("../SyntaxHighlight.php");

?>
<!-- Test Results -->
<?php
echo SyntaxHighlight::process('SELECTpostId, created, keyword, title, teaser FROM pn_blog_posts WHERE  keyword = \'hello-world\' AND status >= 2 ORDER BY created DESC');

echo SyntaxHighlight::process('pre {
  display:block;
  background-color:#3F3F3F;
  margin:1em 0;
  padding:1em;
  font:normal normal 13px/1.4 Consolas,"Andale Mono WT","Andale Mono","Lucida Console","Lucida Sans Typewriter","DejaVu Sans Mono","Bitstream Vera Sans Mono","Liberation Mono","Nimbus Mono L",Monaco,"Courier New",Courier,Monospace;
  color:#E3CEAB;
  overflow:auto;
  white-space:pre;
  word-wrap:normal;
}
pre code {
  font:inherit;
  color:inherit;
}
pre span.N {color:#8CD0D3} /* Numbers */
pre span.S {color:#CC9385} /* Strings */
pre span.C {color:#7F9F7F} /* Comments */
pre span.K {color:#DFC47D} /* Keywords */
pre span.V {color:#CEDF99} /* Vars */
pre span.D {color:#FFFFFF} /* Defines */
pre span.P {color:#9F9D65} /* Punctuations */');

echo SyntaxHighlight::process('// Example inline comment

function theme_include($file) {

    $theme_folder = Config::meta(\'theme\');
    $base = PATH . \'themes\' . DS . $theme_folder . DS;

    /**
     * Example multiline comment
     * Another comment
     */
    if(is_readable($path = $base . ltrim($file, DS) . EXT)) {
        return require $path;
    }

}');

echo SyntaxHighlight::process('Def * locate(string index = "") {
    int start = 0, stop = 0;
    index = trim(index, "\t\n\r /");
    if(index.empty()) return this;

    // Descent into the tree
    Def * d = this;
    do {
        stop = index.find_first_of("/", start);
        string name = index.substr(start, stop - start);
        start = stop + 1;
        d = d->children[name];
    } while(stop != string::npos && d);

    return d;
}');

echo SyntaxHighlight::process('<!DOCTYPE html>
<html dir="ltr" class="page-dashboard">
  <head>
    <meta charset="utf-8">
    <title>Create New Post</title>
    <link href="/plugins/dashboard/lib/css/shell.css" rel="stylesheet">
  </head>
  <body>
    <!-- Test comment -->
  </body>
</html>');

?>
</body>
</html>