<?php
/*         __________________________________________
  ________|                                          |_______
  \       |              PHP PREVOD                  |      /
   \      |  Copyright © 2015 Extreme Design Studio  |     /
   /      |__________________________________________|     \
  /__________)                                    (_________\

* =======================================================================
* Copyright:            Extreme Design Studio 2014                  
* Author:               Dragan Jankovic (dragan88_ar@hotmail.com)       
* =======================================================================
* original filename:    prevod.php
* author:               Dragan Jankovic
* email:                dragan88_ar@hotmail.com
* =======================================================================
*/
class CirLat {

    var $html_aware = false;
    var $case_sensitive = false;
    var $cirilica = array("љ", "њ", "е", "р", "т", "з", "у", "и", "о", "п", "ш", "ђ", "ж", "а", "с", "д", "ф", "г", "х", "ј", "к", "л", "ч", "ћ", "џ", "ц", "в", "б", "н", "м", "Љ", "Њ", "Е", "Р", "Т", "З", "У", "И", "О", "П", "Ш", "Ђ", "Ж", "А", "С", "Д", "Ф", "Г", "Х", "Ј", "К", "Л", "Ч", "Ћ", "Џ", "Ц", "В", "Б", "Н", "М");
    var $latinica = array("lj", "nj", "e", "r", "t", "z", "u", "i", "o", "p", "š", "đ", "ž", "a", "s", "d", "f", "g", "h", "j", "k", "l", "č", "ć", "dž", "c", "v", "b", "n", "m", "Lj", "Nj", "E", "R", "T", "Z", "U", "I", "O", "P", "Š", "Đ", "Ž", "A", "S", "D", "F", "G", "H", "J", "K", "L", "Č", "Đ", "DŽ", "C", "V", "B", "N", "M");

  function tagsafe_replace($search, $replace, $subject, $casesensitive = false)  {
    $subject = '>' . $subject . '<';
    $search = preg_quote($search);
    
    $cs = !$casesensitive ? 'i' : '';
    preg_match_all('/>[^<]*(' . $search . ')[^<]*</i', $subject, $matches, PREG_PATTERN_ORDER);
    foreach($matches[0] as $match)
    {
        $tmp     = preg_replace("/($search)/", $replace, $match);
        $subject = str_replace($match, $tmp, $subject);
    }

    return substr($subject, 1, -1);
  } 
  function Transliterate($cyrilic) {
    if ($this->html_aware) {
      for ($i=0;$i<count($this->cirilica);$i++) {
        $cyrilic = $this->tagsafe_replace($this->cirilica[$i],$this->latinica[$i],$cyrilic,$this->case_sensitive);
      }
      return $cyrilic;
    } else {
      return str_replace($this->cirilica, $this->latinica, $cyrilic);  
    }
  }

}

class GoogleTranslate {
    

    public $lastResult = "";
    

    private $langFrom;
    

    private $langTo;
    

    private static $urlFormat = "http://translate.google.com/translate_a/t?client=t&text=%s&hl=en&sl=%s&tl=%s&ie=UTF-8&oe=UTF-8&multires=1&otf=1&pc=1&trs=1&ssel=3&tsel=6&sc=1";


    public function __construct($from = "en", $to = "ka") {
        $this->setLangFrom($from)->setLangTo($to);
    }


    public function setLangFrom($lang) {
        $this->langFrom = $lang;
        return $this;
    }
    

    public function setLangTo($lang) {
        $this->langTo = $lang;
        return $this;
    }
    
    

    public static final function makeCurl($url, array $params = array(), $cookieSet = false) {
        if (!$cookieSet) {
            $cookie = tempnam(sys_get_temp_dir(), "CURLCOOKIE");
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            unset($ch);
            unlink($cookie);

            return $output;
        }
        
        $queryString = http_build_query($params);

        $curl = curl_init($url . "?" . $queryString);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        
        return $output;
    }

    public function translate($string) {
        return $this->lastResult = self::staticTranslate($string, $this->langFrom, $this->langTo);
    }


    public static function staticTranslate($string, $from, $to) {
        $url = sprintf(self::$urlFormat, rawurlencode($string), $from, $to);
        $result = preg_replace('!,+!', ',', self::makeCurl($url)); 
        $result = str_replace ("[,", "[", $result);
        $resultArray = json_decode($result, true);
        $finalResult = "";
        if (!empty($resultArray[0])) {
            foreach ($resultArray[0] as $results) {
                $finalResult .= $results[0];
            }
            return $finalResult;
        }
        return false;
    }

}
$sta=$_GET['sta'];
$tr = new GoogleTranslate("en", "sr");
$prevod=new CirLat;
echo $prevod->Transliterate($tr->translate($sta));
?>
