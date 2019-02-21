<?php
/**
	[PHP-CLI] AdminFinder
	--
	Author = ./Z3X_ID
	Team   = BOJONEGORO CYBER SECURITY 
	Github = https://www.github.com/Z3X-Gans 
	Blog   = https://www.bojonegoroprogrammer.zone.id 
	WhatsApp = 085816406954
*/

// Hide the Error
error_reporting(0);

// Word List
$wl = file_get_contents("wordlist.txt");

class AdFinder {
  private $WL;
  
  public function __construct(string $wl) {
    $this->WL = $wl;
    $this->Start();
  }
  
  //Start Finder
  public function Start() {
    $site = getopt("u:")["u"];
    if (isset($site) && !empty($site) && preg_match("/\./", $site)) {
        // Clean Screen & Color
        $os = ucfirst(substr(PHP_OS, 0, 3));
        if ($os === "Win"):
          system("cls && color 0a");
          $P = null;
          $M = null;
          $H = null;
          $B = null;
        else:
          system("clear");
          $P = "\e[1;37m";
          $M = "\e[1;31m";
          $H = "\e[1;32m";
          $B = "\e[1;34m";
        endif;
      
      if (!preg_match("/^https?:\/\//", $site)){
        $site = "http://{$site}";
      }
      if (!preg_match("/\/$/", $site)) {
        $site .= "/";
      }
      $WL = explode(PHP_EOL, $this->WL);
      $WC = count($WL);
      echo "{$B}Total Word List: {$P}{$WC}", PHP_EOL;
      for ($i = 0; $i < $WC; $i++) {
        $rest = "{$site}{$WL[$i]}";
        $opt = [
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => 1
        ];
        $ch = curl_init($rest);		
        curl_setopt_array($ch, $opt);	
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode == "200") {
          $result .= "{$rest}" . PHP_EOL;
          echo "{$B}[{$H}Got Found{$B}] >{$H}-{$B}> {$P}{$rest}", PHP_EOL;
          $next = strtolower(trim(readline("{$B}Ketemu Tu. " . PHP_EOL . "Lanjut? (Enter untuk Lanjut) atau (q untuk Keluar): {$P}")));
          ($next == "q") ? die("Bye~" . PHP_EOL) : null;
        } else {
          echo "{$B}[{$M}Not Found{$B}] >{$M}-{$B}> {$P}{$rest}", PHP_EOL;
        }
      }
      echo PHP_EOL, "{$H}Total:", PHP_EOL, "{$P}{$result}";
    } else {
       die("Usage: " . basename(__FILE__) . " -u site.com" . PHP_EOL);
    }
  }
}
new AdFinder($wl);