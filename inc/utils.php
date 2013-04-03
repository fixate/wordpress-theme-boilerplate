<?php
/**
  * A suite of helper classes
  *
  * @author  Stan Bondi <stan@fixate.it>
  */

class HTMLUtil
{
  public static function DecodeHtmlEntities($string, $exceptions = array())
  {
    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);

    // replace literal entities
    $translation_table = array_flip(get_html_translation_table(HTML_ENTITIES));

    $translation_table['&rsquo;'] = '\'';
    $translation_table['&lsquo;'] = '\'';
    $translation_table['&rdquo;'] = '"';
    $translation_table['&ldquo;'] = '"';

    foreach ($exceptions as $e) {
      if ($translation_table[$e])
        unset($e);
    }
    
    return strtr($string, $translation_table);
  }

  public static function DecodeHtmlSpecialChars($string)
  {
    return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
  }

  public static function HtmlToNL($string)
  {
    $string = preg_replace('~<[ ]*br[ ]*/>~ei', "\n", $string);
    $string = preg_replace('~<[ ]*p[ ]*>~ei', "", $string);
    $string = preg_replace('~</[ ]*p[ ]*>~ei', "\n", $string);

    return $string;
  }

  function MakeURLSafeString($string)
  {  
    return trim(preg_replace('/[-]{2,}/', '-', 
                             preg_replace('/[^a-z0-9]+/', '-', strtolower($string))), '-');  
  }


  /* From: http://php.net/manual/en/function.stripslashes.php
   * Recursively strip slashes
   */
  function StripSlashesDeep($value)
  {
    return (is_array($value)) ?
      array_map(array(__CLASS__, __FUNCTION__), $value) :
      stripslashes($value);
  }
  
}

class PHP
{
  public static function GetIncludeContent($filename, $args = null) 
  {
    if (!is_file($filename)) {
      die($filename . ' is not a valid file!');
      return false;
    }

    // Create context
    if ($args && is_array($args) && count($args) > 0) 
      extract($args);
    
    ob_start();
    include($filename);
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }

  public static function StreamCopy($sourcePath, $destPath) 
  {
    $input = fopen($sourcePath, "r");
    $temp = tmpfile();
    $realSize = stream_copy_to_stream($input, $temp);
    fclose($input);

    if (!isset($_SERVER["CONTENT_LENGTH"]))
      throw new Exception('Getting content length is not supported.');
    
    $reportedSize = (int)$_SERVER["CONTENT_LENGTH"];
    if ($realSize != $reportedSize)
      throw new Exception("Size of file ($realSize) is not equal to reported size ($reportedSize)");
    
    if (DIRECTORY_SEPARATOR == '/' &&
        !String::StartsWith($destPath, '/'))
      $destPath = Path::Join(getcwd(), $destPath);

    if (!$target = fopen($destPath, "w"))
      throw new Exception("Unable to open ($destPath) for writing");
    fseek($temp, 0, SEEK_SET);
    stream_copy_to_stream($temp, $target);
    fclose($target);
    fclose($temp);

    return true;
  }

  static function GetToQuery($get = null)
  {
    if (!$get)
      $get = $_GET;
    $result = array();
    foreach ($get as $key => $value) {
      $result[] = "$key=$value";
    }
    
    return implode("&", $result);
  }
}

class ObjUtil
{
  // Extends associative array or object
  function Extend(&$target, $source, $exclusive = false) 
  {
    if (!isset($target)) {
      $target = ($exclusive) ? null : $source;
      return $target;
    }
    if (!isset($source))
      return $target;

    if (is_object($target)) {
      if (is_object($source)) 
        $member_list = get_object_vars($source);
      else // is array already
        $member_list = $source;

      foreach($member_list as $member => $value) {
        if (!$exclusive || isset($target->{$member})) 
          $target->{$member} = $value;
      }
      return $target;
    }

    if (is_array($target)) {
      // Merge source with target
      foreach($source as $name => $default) {
        // If exclusive, we only want to merge keys 
        // that target already has
        if (!$exclusive || array_key_exists($name, $target)) 
          $target[$name] = $source[$name];
      }
      return $target;
    }
  }
}

class Browser
{
  function IsIE($version=null)
  {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    if (!$version)
      return (strpos($user_agent, 'MSIE') !== false);

    return (strpos($user_agent, "MSIE $version") !== false);
  }
  
  function GetBrowser($user_agent = '') 
  {
    if (empty($user_agent))
      $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/windows|win32/i', $user_agent)) {
      $platform = 'windows';
    }
    elseif (preg_match('/linux/i', $user_agent)) {
      $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
      $platform = 'mac';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    $ub = '';
    if(preg_match('/MSIE/i',$user_agent) && !preg_match('/Opera/i',$user_agent)) { 
      $bname = 'Internet Explorer'; 
      $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$user_agent)) { 
      $bname = 'Mozilla Firefox'; 
      $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$user_agent)) { 
      $bname = 'Google Chrome'; 
      $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$user_agent)) { 
      $bname = 'Apple Safari'; 
      $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$user_agent)) { 
      $bname = 'Opera'; 
      $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$user_agent)) { 
      $bname = 'Netscape'; 
      $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
      ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

    if (preg_match_all($pattern, $user_agent, $matches)) {
      if (count($matches['browser']) > 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($user_agent, "Version") < strripos($user_agent,$ub))
          $version= floatval($matches['version'][0]);
        else 
          $version= floatval($matches['version'][1]);
      } 
      else { $version= floatval($matches['version'][0]); }
    }
    
    return array(
      'userAgent' => $user_agent,
      'name'      => $bname,
      'shortname' => $ub,
      'version'   => $version,
      'platform'  => $platform,
      'pattern'   => $pattern
    );    
  }
}

class String
{
  static function PadLeft($str, $pad_num, $chr = ' ')
  {
    $str_diff = $pad_num - strlen($str);
    
    if ($str_diff < 0)
      return $str;

    for ($i = 0; $i < $str_diff; $i++) {
      $str = $chr . $str;
    }

    return $str;
  }

  static function PadRight($str, $pad_num, $chr = ' ')
  {
    $str_diff = $padnum - strlen($str);
    if ($str_diff < 0)
      return $str;

    for ($i = 0; $i < $str_diff; $i++) {
      $str = $str . $chr;
    }

    return $str;
  }

  static function Endswith($haystack, $needle)
  {
    return strrpos($haystack, $needle) === strlen($haystack)-strlen($needle);
  }

  static function StartsWith($haystack, $needle)
  {
    if (is_array($needle)) {
      foreach ($needle as $i) {
        if (strpos($haystack, $i) === 0)
          return true;
      }
      return false;
    }

    return strpos($haystack, $needle) === 0;
  }


  function GetSnippet($content, $word_count, $continue_str = '...', $max_length = null)
  {
    if (strlen($content) == 0)
      return '';

    $result = implode(' ', array_slice(explode(' ', $content), 0, $word_count));
    
    if ($max_length && strlen($result) > $max_length - strlen($continue_str))
      $result = substring($result, 0, $max_length - strlen($continue_str));
    
    return "$result$continue_str";
  }

  function GenRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $string = '';    

    for ($p = 0; $p < $length; $p++) {
      $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
  }
}

class Image
{
  public static function TrToWidth($image_path, $target_width)
  {
    $thumb_size = getimagesize($image_path);
    $w1 = (float)$thumb_size[0];
    $h1 = (float)$thumb_size[1];
    $w2 = $target_width;

    return ($h1 / $w1) * $w2;
  }

  public static function TrToHeight($image_path, $target_height)
  {
    $thumb_size = getimagesize($image_path);
    $w1 = (float)$thumb_size[0];
    $h1 = (float)$thumb_size[1];
    $h2 = $target_height;

    return ($w1 / $h1) * $h2;
  }
}


class Path
{
  static function GetFilename($path)
  {    
    $arrStr = explode('/', str_replace('\\', '/', $path)); 
    return end($arrStr);
  }

  static function GetFilenameWithoutExtension($path)
  {
    if (($pos = strrpos($path, '.')) === false)
      return $path;
    return substr($path, 0, $pos);
  }

  static function ChangeExtension($path, $ext) {
    $ext = trim($ext, '.');

    return self::GetFilenameWithoutExtension($path).".{$ext}";
  }

  static function GetExtension($path)
  {
    return end(explode(".", $path));
  }

  static function GetFiles($path, $extension = null)
  {
    $result = array();
    if ($handle = opendir($path)) {
      while (($file = readdir($handle)) !== false) {
        if (is_dir("$path/$file"))
          continue;

        if ($extension && Path::GetExtension($file) != $extension)
          continue;

        $result[] = $file;
      }
    }

    if (isset($handle))
      closedir($handle);
    
    return $result;
  }
  
  static function GetDirectories($path) {
    if (strrpos($path, '/') !== strlen($path) - 1)
      $path .= '/';

    return array_map(
      create_function('$dir',
                      'return end(explode("/", str_replace("\\\\", "/", $dir)));'),
      glob($path.'*', GLOB_ONLYDIR)); 
  }

  static function Join()
  {
    $sep = '/'; //DIRECTORY_SEPARATOR
    $_args = func_get_args();
    $path = rtrim($_args[0], $sep);
    for ($i = 1; $i < count($_args);$i++)
      $path = "$path$sep".trim($_args[$i], $sep);

    return $path;
  }

  static function DeleteDir($dir) 
  { 
    if (!is_dir($dir)) 
      return;

    $objects = scandir($dir); 
    foreach ($objects as $object) {
      if ($object == "." || $object == "..") 
        continue;

      if (filetype($dir."/".$object) == "dir") 
        Path::DeleteDir($dir."/".$object); 
      else 
        unlink($dir."/".$object); 
    } 
    reset($objects); 
    rmdir($dir); 
  } 
}


if (!class_exists('Url')):
  class Url
  {
    public $host;
    public $path;
    public $query;
    public $hash;
    public $protocol;
    public $url;

    public $error = false;

    function __construct($url = '') {
      $this->url = htmlspecialchars_decode($url); 
      if (empty($url)) {
        $this->error = true;
        return;
      }

      $this->protocol = $this->__getProtocol();
      list($this->host, $this->path) = $this->__getHostAndPath();
      $this->query    = $this->__getQuery();
      $this->hash     = $this->__getHash();

      $this->url = $this->__tostring();
    }

    function __getProtocol() {
      if (($pos = strpos($this->url, ':')) === false)
        return '';
      return substr($this->url, 0, $pos);
    }

    function __getHostAndPath() {
      $host = $this->url;
      if (($pos = strpos($host, '://')) == false)
        return array($host, '');

      $host = substr($host, $pos + 3);
      if ((@preg_match('/[\/|\?]+/', $host, $pos, PREG_OFFSET_CAPTURE)) === 0) 
        return array($host, '');
      $pos = $pos[0][1]; 

      $path = substr($host, $pos + 1, strlen($host));
      if ((@preg_match('/[^\/a-zA-Z0-9_\.-]/', $path, $pos2, PREG_OFFSET_CAPTURE)) > 0)
        $path = substr($path, 0, $pos2[0][1]);

      return array(substr($host, 0, $pos), $path);
    }

    function __getQuery() {
      if (($pos = strpos($this->url, '?')) === false)
        return array();

      $querystr = substr($this->url, $pos + 1);

      if (@preg_match_all('/([^&=]+)=?([^&#]*)/', $querystr, $matches) === false)
        return array();

      $query = array_combine($matches[1], $matches[2]);   
      $i = 0;
      foreach ($query as $k => $v) {
        if (String::StartsWith(trim($k), '#'))
          break;
        $i++;
      }

      return array_slice($query, 0, $i);
    }

    function __getHash() {
      if (($pos = strpos($this->url, '#')) === false)
        return '';
      
      return substr($this->url, $pos + 1);
    }

    function __tostring() {
      $url = sprintf('%s://%s', $this->protocol, $this->host);
      if (!empty($this->path))
        $url = Path::Join($url, $this->path);
      if (!empty($this->query)) {
        $parts = array();
        foreach ($this->query as $k => $v)
          $parts[] = sprintf('%s=%s', urlencode($k), urlencode($v));

        $url .= '?'.implode('&', $parts);
      }
      if (!empty($this->hash))
        $url .= '#'.htmlspecialchars($this->hash);

      return $url;
    }

    public static function &ParseUrl($url) {  
      return new Url($url);
    }
  }
endif;