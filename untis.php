<?php

//locatie van je Untis-export
$locatie = "...\GPU001.txt";
if (file_exists($locatie)){
  $lines = file($locatie);
}
else {
  echo "Bestand $locatie is niet gevonden, controleer of dit bestaat.";
  exit;
}
echo $lines;
//de filters die overbodig zijn
$filters = array("GOK","MAG","VB");
$i = 0;

$wegschrijven = fopen("...\GPUGezuiverd.txt", "w") or die("Unable to open file!");

foreach($lines as $line)
{
  foreach ($filters as $filter) {
    if(strpos($line, $filter) == true) {
        unset($lines[$i]);

    }//endif strpos == true
  }//end foreach filters
  $i++;
}//end foreach(lines)
foreach($lines as $gezuiverd){
  fwrite($wegschrijven, $gezuiverd);
  echo $gezuiverd;
  echo "<br>";
}
fclose($wegschrijven);
?>
