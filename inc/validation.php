<?php 

class Validation {
  public function email($value)
  {
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $value)) {
      // Email invalid because wrong number of characters
      // in one section or wrong number of @ symbols.
      return false;
    }

    // Split it into sections to make life easier
    $value_array = explode("@", $value);
    $local_array = explode(".", $value_array[0]);
    for ($i = 0; $i < count($local_array); $i++) {
      if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",//"
                $local_array[$i])) {
        return false;
      }
    }
    // Check if domain is IP. If not,
    // it should be valid domain name
    if (!ereg("^\[?[0-9\.]+\]?$", $value_array[1])) {
      $domain_array = explode(".", $value_array[1]);
      if (count($domain_array) < 2) {
        return false; // Not enough parts to domain
      }

      for ($i = 0; $i < count($domain_array); $i++) {
        if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",
                  $domain_array[$i])) {
          return false;
        }
      }
    }
    return true;
  }

  public function presence($value)
  {
    $value = trim($value);
    return !empty($value);
  }
}
