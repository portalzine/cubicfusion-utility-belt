<?php
// SYNTAX HIGHLIGHTING WITH REGULAR EXPRESSIONS
// Version 1.0.1

// Alexander Graef 
// portalzine.projects@gmail.com
// portalzine.de

// Tweaked to work with PHP 5.4x+
// Original code by Dominic Szablewski 
// http://phoboslab.org/log/2007/08/generic-syntax-highlighting-with-regular-expressions

/* USAGE */

// echo SyntaxHighlight::process( $your_code );

/* CSS */

// pre { 
//	font-family: Courier New, Bitstream Vera Sans Mono, monospace; 
//	font-size: 9pt;
//	border-top: 1px solid #333;
//	border-bottom: 1px solid #333;
//	padding: 0.4em;
//	color: #fff;
// }
// pre span.N{ color:#f2c47f; } /* Numbers */
// pre span.S{ color:#42ff00; } /* Strings */
// pre span.C{ color:#838383; } /* Comments */
// pre span.K{ color:#ff0078; } /* Keywords */
// pre span.V{ color:#70d6ff; } /* Vars */
// pre span.D{ color:#ff9a5d; } /* Defines */


class SyntaxHighlight {
	
	static $tokens = array(); // This array will be filled from the regexp-callback
    
	public static function process($s) {
        $s = htmlspecialchars($s);

        // Workaround for escaped backslashes
        $s = str_replace('\\\\','\\\\<e>', $s); 

        $regexp = array(           

            // Punctuations
            '/([\-\!\%\^\*\(\)\+\|\~\=\`\{\}\[\]\:\"\'<>\?\,\.\/]+)/'
            => '<span class="P">$1</span>',

            // Numbers (also look for Hex)
            '/(?<!\w)(
                (0x|\#)[\da-f]+|
                \d+|
                \d+(px|em|cm|mm|rem|s|\%)
            )(?!\w)/ix'
            => '<span class="N">$1</span>',

            // Make the bold assumption that an
            // all uppercase word has a special meaning
            '/(?<!\w|>|\#)(
                [A-Z_0-9]{2,}
            )(?!\w)/x'
            => '<span class="D">$1</span>',

            // Keywords
            '/(?<!\w|\$|\%|\@|>)('
                
				.'abstract|and|array|array|array_cast|array_splice|as|
				bool|boolean|break|
				case|catch|char|class|clone|close|const|continue|
				declare|default|define|delete|die|do|double|
				echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|
				endwhile|eval|exit|exit|explode|extends|eventsource|
				false|file|file_exists|final|finally|float|flush|for|foreach|function|
				global|goto|
				header|
				if|implements|implode|include|include_once|ini_set|instanceof|int|integer|interface|isset|
				json_encode|json_decode
				list|long|
				namespace|new|new|null|
				ob_flush|object|on|or|
				parse|print|private|protected|public|published|
				real|require|require_once|resource|return|
				self|short|signed|sleep|static|string|struct|switch|
				then|this|throw|true|try|
				unset|unsigned|use|usleep|
				var|var|void|
				while|
				xor|'
				
				.'RewriteEngine|RewriteRule|ErrorDocument
            )(?!\w|=")/ix'
            => '<span class="K">$1</span>',

            // PHP/Perl-Style Vars: $var, %var, @var
            '/(?<!\w)(
                (\$|\%|\@)(\-&gt;|\w)+
            )(?!\w)/ix'
            => '<span class="V">$1</span>'

        );
      
		 $s = preg_replace_callback( '/(
                \/\*.*?\*\/|
                \/\/.*?\n|
                \#.[^a-fA-F0-9]+?\n|
                \&lt;\!\-\-[\s\S]+\-\-\&gt;|
                (?<!\\\)&quot;.*?(?<!\\\)&quot;|
                (?<!\\\)\'(.*?)(?<!\\\)\'
            )/isx' , array('SyntaxHighlight', 'replaceId'),$s);
			
        $s = preg_replace(array_keys($regexp), array_values($regexp), $s);

        // Paste the comments and strings back in again
        $s = str_replace(array_keys(SyntaxHighlight::$tokens), array_values(SyntaxHighlight::$tokens), $s);

        // Delete the "Escaped Backslash Workaround Token" (TM)
        // and replace tabs with four spaces.
        $s = str_replace(array('<e>', "\t"), array('', '    '), $s);

        return '<pre>'.$s.'</pre>';
    }

    // Regexp-Callback to replace every comment or string with a uniqid and save
    // the matched text in an array
    // This way, strings and comments will be stripped out and wont be processed
    // by the other expressions searching for keywords etc.
     static function replaceId($match) {
        $id = "##r" . uniqid() . "##";
	
        // String or Comment?
        if(substr($match[1], 0, 2) == '//' || substr($match[1], 0, 2) == '/*' || substr($match[1], 0, 2) == '##' || substr($match[1], 0, 7) == '&lt;!--') {
            SyntaxHighlight::$tokens[$id] = '<span class="C">' . $match[1] . '</span>';
        } else {
           SyntaxHighlight::$tokens[$id] = '<span class="S">' . $match[1] . '</span>';
        }
		
        return $id;
    }
}