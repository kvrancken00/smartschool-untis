<?php
   if(isset($_FILES['untis'])){
      $errors= array();
      $file_name = strtolower($_FILES['untis']['name']);
      $file_size =$_FILES['untis']['size'];
      $file_tmp =$_FILES['untis']['tmp_name'];
      $file_type=$_FILES['untis']['type'];
      $temp=explode('.',$file_name);
      $file_ext=end($temp);

      $expensions= array("txt");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="extension not allowed, please choose a txt file.";
      }

      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }

      if(empty($errors)==true){
         //move_uploaded_file($file_tmp,"images/".$file_name);
         move_uploaded_file($file_tmp,"images/gpu001.txt");
         echo "Succesvol geupload";
         echo "<br>";
         verwerkUntis();

      }else{
         print_r($errors);
      }
   }
   function verwerkUntis(){
     $locatie = "images\gpu001.txt";
     if (file_exists($locatie)){
       $lines = file($locatie);
     }
     else {
       echo "Bestand $locatie is niet gevonden, controleer of dit bestaat.";
       exit;
     }
     //de filters die overbodig zijn
     $filters = array("GOK","MAG","VB");
     $i = 0;
     $locatie_zuiver = "images\GPUGezuiverd.txt";

     $wegschrijven = fopen("images\GPUGezuiverd.txt", "w") or die("Unable to open file!");

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
     }
     echo "Weggeschreven";
     echo "<br>";
     fclose($wegschrijven);
     //maak connectie met smartschool
     $client = new SoapClient('https://school.smartschool.be/Webservices/V3?wsdl');
     $bijlage = file_get_contents($locatie_zuiver);

     $bijlage_encoded = base64_encode($bijlage);

     $arr_bijlage[0]['filename'] = $locatie_zuiver;
     $arr_bijlage[0]['filedata'] = $bijlage_encoded;



     if($wsresult = $client->sendMsg("webserviceswachtwoord","ontvanger","Untis importeren",
     "body-tekst","Afzender", $arr_bijlage,"0")==0){
       echo "Bericht verstuurd.";
     }
     else {
       {
         echo "Fout: $wsresult";
       }
     }
   }//end verwerkUntis()
?>
<html>
   <body>
     <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="untis" /> <br>
        <input type="submit" value="Verwerk Bestand"/>
     </form>


   </body>
</html>
