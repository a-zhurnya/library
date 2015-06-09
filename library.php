<?
/**
 * Вариант функции strpos(), где искомое значение может быть массивом любой вложенности
 *
 * @param $haystack
 * @param $needles
 * @return int
 */
function strpos_array($haystack, $needles) {
    if ( is_array($needles) ) {
        foreach ($needles as $str) {
            if ( is_array($str) ) {
                $pos = strpos_array($haystack, $str);
            } else {
                $pos = strpos($haystack, $str);
            }
            if ($pos !== FALSE) {
                return $pos;
            }
        }
    } else {
        return strpos($haystack, $needles);
    }

    return false;
}

/**
 * Фильтр убирает подрят идущие символы в переданной строке
 *
 * @param string $string фильтруемая строка
 * @param string $symbol убираемый повторяющийся символ
 * @param integer $count сколько повторений подрят убирать
 * @return string
 **/
function removeRepeatingSymbols($string, $symbol = " ", $count = 2) {
    $v = $string;

    $ss = "";
    for($i=0;$i<$count;$i++) {
        $ss .= $symbol;
    }

    $string = str_replace($ss, $symbol, $string);
    if($v != $string) {
        return removeRepeatingSymbols($string, $symbol, $count);
    } else {
        return $string;
    }
}

/**
 * Функция подготавливает строку к адресной. Транслитерирует и убирает всё лишнее
 *
 * @relation function removeRepeatingSymbols()
 * @param $string
 * @return mixed|string
 */
function strToAliasUrl($string) {

    // преобразуем специальные HTML-сущности в соответствующие символы
    $string = htmlentities($string, ENT_QUOTES, "UTF-8");
    $a = array('&Agrave;'=>'A','&#192;'=>'A','&Aacute;'=>'A','&#193;'=>'A','&Acirc;'=>'A','&#194;'=>'A','&Atilde;'=>'A','&#195;'=>'A','&Auml;'=>'A','&#196;'=>'A','&Aring;'=>'A','&#197;'=>'A','&AElig;'=>'AE','&#198;'=>'AE','&Ccedil;'=>'C','&#199;'=>'C','&Yacute;'=>'Y','&#221;'=>'Y','&ETH;'=>'D','&#208;'=>'D','&Ntilde;'=>'N','&#209;'=>'N','&Egrave;'=>'E','&#200;'=>'E','&Eacute;'=>'E','&#201;'=>'E','&Ecirc;'=>'E','&#202;'=>'E','&Euml;'=>'E','&#203;'=>'E','&Igrave;'=>'I','&#204;'=>'I','&Iacute;'=>'I','&#205;'=>'I','&Icirc;'=>'I','&#206;'=>'I','&Iuml;'=>'I','&#207;'=>'I','&Ograve;'=>'O','&#210;'=>'O','&Oacute;'=>'O','&#211;'=>'O','&Ocirc;'=>'O','&#212;'=>'O','&Otilde;'=>'O','&#213;'=>'O','&Ouml;'=>'O','&#214;'=>'O','&Ugrave;'=>'U','&#217;'=>'U','&Uacute;'=>'U','&#218;'=>'U','&Ucirc;'=>'U','&#219;'=>'U','&Uuml;'=>'U','&#220;'=>'U','&agrave;'=>'a','&#224;'=>'a','&aacute;'=>'a','&##225;'=>'a','&acirc;'=>'a','&##226;'=>'a','&atilde;'=>'a','&#227;'=>'a','&auml;'=>'a','&#228;'=>'a','&aring;'=>'a','&#229;'=>'a','&aelig;'=>'ae','&#230;'=>'ae','&egrave;'=>'e','&#232;'=>'e','&eacute;'=>'e','&#233;'=>'e','&ecirc;'=>'e','&#234;'=>'e','&euml;'=>'e','&#235;'=>'e','ё'=>'e','&igrave;'=>'i','&#236;'=>'i','&iacute;'=>'i','&#237;'=>'i','&icirc;'=>'i','&#238;'=>'i','&iuml;'=>'i','&#239;'=>'i','&ograve;'=>'o','&#242;'=>'o','&oacute;'=>'o','&#243;'=>'o','&ocirc;'=>'o','&#244;'=>'o','&otilde;'=>'o','&#245;'=>'o','&ouml;'=>'o','&#246;'=>'o','&ugrave;'=>'u','&#249;'=>'u','&uacute;'=>'u','&#250;'=>'u','&ucirc;'=>'u','&#251;'=>'u','&uuml;'=>'u','&#252;'=>'u','&yacute;'=>'y','&#253;'=>'y','&yuml;'=>'y','&#255;'=>'y','&ntilde;'=>'n','&#241;'=>'n','&ccedil;'=>'c','&#231;'=>'c','&ndash;'=>'-','&#8211;'=>'-','&mdash;'=>'-','&#8212;'=>'-','&oline;'=>'-','&#8254;'=>'-');
    $string = strtr($string, $a);

    // переводим в строчные, убираем теги и пробелы/дефисы по краям
    $string = trim(mb_strtolower(strip_tags($string), 'utf8'));
    $string = trim($string, '-');

    // удаляем прочие символы-сущности, которые по какии-либо причинам не отфильтровались выше
    $string = preg_replace('/&[a-zA-Z0-9#]+\;/', '', $string);

    // транслируем
    $tr = array("а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"j","з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y","ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya");
    $string = strtr($string,$tr);

    // разрешенные символы, убираем те, что отсутствуют в этом списке
    $new_name = '';
    $mix = array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m','0','1','2','3','4','5','6','7','8','9','-',' ');
    for($i=0; $i<=strlen($string)-1; $i++) {
        $j = substr($string,$i,1);
        if (in_array($j, $mix)) {
            $new_name .= $j;
        }
    }
    $string = $new_name;

    // убираем подряд идущие пробелы и дефисы
    $string = removeRepeatingSymbols($string, ' ');
    $string = removeRepeatingSymbols($string, '-');

    // заменяем пробелы на дефисы
    $string = str_replace(array(' - ', '- ', ' -', ' ', '_', '—'), '-', $string);
    $string = trim($string, ' ');
    $string = trim($string, '-');

    return $string;
}

/**
 * Функция подготавливает строку к вставке для значения в мета-тег
 * Заменяет любые кавычки на одинарные, убирает html-теги, заменяет html-сущности на норм. символы
 *
 * @relation function removeRepeatingSymbols()
 * @param $string
 * @return mixed|string
 */
function stringToMeta($string) {
    // убираем html-теги
    $string = strip_tags($string);

    // преобразуем специальные HTML-сущности в соответствующие символы
    $string = html_entity_decode($string, ENT_QUOTES, "UTF-8");
    $a = array('&Agrave;'=>'A','&#192;'=>'A','&Aacute;'=>'A','&#193;'=>'A','&Acirc;'=>'A','&#194;'=>'A','&Atilde;'=>'A','&#195;'=>'A','&Auml;'=>'A','&#196;'=>'A','&Aring;'=>'A','&#197;'=>'A','&AElig;'=>'AE','&#198;'=>'AE','&Ccedil;'=>'C','&#199;'=>'C','&Yacute;'=>'Y','&#221;'=>'Y','&ETH;'=>'D','&#208;'=>'D','&Ntilde;'=>'N','&#209;'=>'N','&Egrave;'=>'E','&#200;'=>'E','&Eacute;'=>'E','&#201;'=>'E','&Ecirc;'=>'E','&#202;'=>'E','&Euml;'=>'E','&#203;'=>'E','&Igrave;'=>'I','&#204;'=>'I','&Iacute;'=>'I','&#205;'=>'I','&Icirc;'=>'I','&#206;'=>'I','&Iuml;'=>'I','&#207;'=>'I','&Ograve;'=>'O','&#210;'=>'O','&Oacute;'=>'O','&#211;'=>'O','&Ocirc;'=>'O','&#212;'=>'O','&Otilde;'=>'O','&#213;'=>'O','&Ouml;'=>'O','&#214;'=>'O','&Ugrave;'=>'U','&#217;'=>'U','&Uacute;'=>'U','&#218;'=>'U','&Ucirc;'=>'U','&#219;'=>'U','&Uuml;'=>'U','&#220;'=>'U','&agrave;'=>'a','&#224;'=>'a','&aacute;'=>'a','&##225;'=>'a','&acirc;'=>'a','&##226;'=>'a','&atilde;'=>'a','&#227;'=>'a','&auml;'=>'a','&#228;'=>'a','&aring;'=>'a','&#229;'=>'a','&aelig;'=>'ae','&#230;'=>'ae','&egrave;'=>'e','&#232;'=>'e','&eacute;'=>'e','&#233;'=>'e','&ecirc;'=>'e','&#234;'=>'e','&euml;'=>'e','&#235;'=>'e','ё'=>'e','&igrave;'=>'i','&#236;'=>'i','&iacute;'=>'i','&#237;'=>'i','&icirc;'=>'i','&#238;'=>'i','&iuml;'=>'i','&#239;'=>'i','&ograve;'=>'o','&#242;'=>'o','&oacute;'=>'o','&#243;'=>'o','&ocirc;'=>'o','&#244;'=>'o','&otilde;'=>'o','&#245;'=>'o','&ouml;'=>'o','&#246;'=>'o','&ugrave;'=>'u','&#249;'=>'u','&uacute;'=>'u','&#250;'=>'u','&ucirc;'=>'u','&#251;'=>'u','&uuml;'=>'u','&#252;'=>'u','&yacute;'=>'y','&#253;'=>'y','&yuml;'=>'y','&#255;'=>'y','&ntilde;'=>'n','&#241;'=>'n','&ccedil;'=>'c','&#231;'=>'c','&ndash;'=>'-','&#8211;'=>'-','&mdash;'=>'-','&#8212;'=>'-','&oline;'=>'-','&#8254;'=>'-');
    $string = strtr($string, $a);

    // заменяем любые кавычки на одинарные
    $arQuote = array(
        '"', '&quot;', '&#34;', // двойная кавычка
        "«", '»', '&laquo;', '&raquo;', '&#171;', '&#187;', // типографская кавычка
        '&prime;', '&#8242;', '&Prime;', '&#8243;',  // штрих (двойной и одинарный)
        '&lsquo;', '&#8216;', '&rsquo;', '&#8217;', '&sbquo;', '&#8218;', // одиночная кавычка
        '&ldquo;', '&#8220;', '&rdquo;', '&#8221;', '&bdquo;', '&#8222;', // кавычка-лапка
        '&#10075;', '&#10076;', '&#10077;', '&#10078;' // английские кавычи
    );
    $string = str_replace($arQuote, "'", $string);

    // убираем подряд идущие пробелы и дефисы
    $string = removeRepeatingSymbols($string, ' ');
    $string = removeRepeatingSymbols($string, '-');
    $string = trim($string, ' ');
    $string = trim($string, '-');

    // удаляем прочие символы-сущности, которые по какии-либо причинам не отфильтровались выше
    $string = preg_replace('/&[a-zA-Z0-9#]+\;/', '', $string);

    return $string;
}

/**
 * Функция преобразует первый символ строки в верхний регистр
 *
 * @param $string
 * @param string $encoding
 * @return string
 */
function stringToUpperFirst($string, $encoding='UTF-8') {
    $string = mb_ereg_replace('^[\ ]+', '', $string);
    $string = mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding).
        mb_substr($string, 1, mb_strlen($string), $encoding);
    return $string;
}

/**
 * Обрамляем абзацы текста в тег <p>
 *
 * @param $string
 * @return string
 */
function paragraphToTag($string) {
    $res = '';
    $a = explode("\n", $string);
    foreach($a as $val) {
        $val = str_replace("\r", "", $val);
        if (strlen($val) > 1) {
            $res .= '<p>'.$val.'</p>'."\n";
        }
    }
    return $res;
}

/**
 * Генерация кода из цифр и букв в нижнем и верхнем регистрах
 *
 * @param integer $len
 * @return string
 */
function randHardCode($len) {
    $arSymbols = array(
        "a","b","c","d","e","f","g","h","j","k","l","m","n","p","q","r","s","t","u","v","w","x","y","z",
        "A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z",
        0,1,2,3,4,5,6,7,8,9
    );
    $code = '';
    for($i = 0; $i < $len; $i++) {
        $a = rand(0, 57);
        $code = $code.$arSymbols[$a];
    }
    return $code;
}

/**
 * Генерация кода из цифр и букв нижнего регистра
 *
 * @param integer $len
 * @return string
 */
function randEasyCode($len) {
    $arSymbols = array("a","b","c","d","e","f","g","h","j","k","l","m","n","p","q","r","s","t","u","v","w","x","y","z",0,1,2,3,4,5,6,7,8,9);
    $code = '';
    for($i = 0; $i < $len; $i++) {
        $a = rand(0, 33);
        $code = $code.$arSymbols[$a];
    }
    return $code;
}

/**
 * Class Filter_BBCode
 */
class Filter_BBCode {
    protected $codes = array(
        "\n"    => '<br />',
        '[b]'   => '<strong>',
        '[/b]'  => '</strong>',
        '[i]'   => '<i>',
        '[/i]'  => '</i>',
        '[u]'   => '<u>',
        '[/u]'  => '</u>'
    );

    public function filter($string) {
        $v = str_ireplace(array_flip($this->codes), $this->codes, $string);

        // links
        $p = '/(http:\/\/|https:\/\/)?([a-zA-Z0-9]+[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}([\/a-zA-Z0-9\-_\?%&=]+)?)/';
        $r = '<a href="http://$2" target="_blank">$1$2</a>';
        $v = preg_replace($p, $r, $v);

        return $v;
    }

    public function Decode($string) {
        $v = str_ireplace($this->codes, array_flip($this->codes), $string);
        $v = preg_replace('/<a href="(.*)" target="_blank">(.*)<\/a>/', '$2', $v);
        return $v;
    }
}


/**
 * Функция заменяет ключ массива на другой
 *
 * @param $key
 * @param $new_key
 * @param $arr
 * @param bool $rewrite
 * @return bool
 */
function arrayChangeKey($key,$new_key,&$arr,$rewrite=true){
    if(!array_key_exists($new_key,$arr) || $rewrite){
        $arr[$new_key]=$arr[$key];
        unset($arr[$key]);
        return true;
    }
    return false;
}


/**
 * Конвертирует переносы строк в windows-понятные
 *
 * @param string $string
 * @return string
 */
function normalizeLine($string) {
    // Replace all the CRLF ending-lines by something uncommon
    $DontReplaceThisString = "\r\n";
    $specialString = "!£#!Dont_wanna_replace_that!#£!";
    $string = str_replace($DontReplaceThisString, $specialString, $string);

    // Convert the CR ending-lines into CRLF ones
    $string = str_replace("\r", "\r\n", $string);

    // Replace all the CRLF ending-lines by something uncommon
    $string = str_replace($DontReplaceThisString, $specialString, $string);

    // Convert the LF ending-lines into CRLF ones
    $string = str_replace("\n", "\r\n", $string);

    // Restore the CRLF ending-lines
    $string = str_replace($specialString, $DontReplaceThisString, $string);

    // Update the file contents
    return $string;
}


/**
 * Рекурсвная смена прав для файлов и папок. Функция взята из курса Битрикс "Администратор. Базовый"
 *
 * @param $path
 */
function chmod_R($path) {
    $BX_FILE_PERMISSIONS = 0644;
    $BX_DIR_PERMISSIONS = 0755;

    $handle = opendir($path);
    while ( false !== ($file = readdir($handle)) ) {
        if ( ($file !== ".") && ($file !== "..") ) {
            if ( is_file($path."/".$file) ) {
                chmod($path . "/" . $file, $BX_FILE_PERMISSIONS);
            }
            else {
                chmod($path . "/" . $file, $BX_DIR_PERMISSIONS);
                chmod_R($path . "/" . $file);
            }
        }
    }
    closedir($handle);
}