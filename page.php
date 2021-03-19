<?php
	$myname = "Tiina Elmi";
	$currenttime = date("d.m.Y H:i:s"); //hetkeaeg ja kellaaeg muutujasse nimega currenttime
	$timehtml = "\n <p>Lehe avamise hetkel oli: " .$currenttime .".</p> \n"; //teeme html-i jaoks vormindatud kellaaja
	$semesterbegin = new DateTime("2021-1-25"); //muutuja-semestri algusaeg, mille ise ette annan
	$semesterend = new DateTime("2021-6-30"); //muutuja- semestri lõpaeg, samuti annan ise ette
	$semesterduration = $semesterbegin->diff($semesterend); //semestri kestvus, selleks paneme võrdlusesse varasemalt defineeritud alguse ja lõpuaja ja kasutame diff funtktsiooni(difference)
	$semesterdurationdays = $semesterduration->format("%r%a"); //muudab ajaformaadi päevadeks (a) ja näitan tulemust ka negatiivse väärtuse korral (r)
	$semesterdurhtml = "\n <p>2021 kevadsemestri kestus on " .$semesterdurationdays ." päeva.</p> \n";  //muutuja html-is info väljastamiseks koos teksti ja reavahetusega /n, mis tuleb panna jutumärkidesse
	$today = new DateTime("now"); //küsime tänast kuupäeva, et ei peaks käsitsi sisse trükkima ja saaks hiljem võrdlemisel kasutada
	
	//--------------semestri kulgemise määramine-----
	$fromsemesterbegin = $semesterbegin->diff($today); //siit küsime aega semestri algusest tänaseni
	$fromsemesterbegindays = $fromsemesterbegin->format("%r%a"); //ning eelmises reas saadud tulemuse arvutame päevadeks

	
	if($fromsemesterbegindays <= $semesterdurationdays && $fromsemesterbegindays >=0){ //kontrollime, mis väärtus on semestri kestvuses, võimalik väärtus 0, negatiivne või positiivne
		$semesterprogress = "\n"  .'<p>Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"></meter>.</p>' ."\n";
		//<p>Semester edeneb: <meter min="0" max="156" value="35"></meter>  // kui ajavahemik on positiivne ( ja vahemikus kuni 156 päeva), siis semester käib ja näitame progressiriba
	} else {
		if ($fromsemesterbegindays <0)
		{$semesterprogress = "\n <p>Semester pole veel alanud.</p> \n";} //kui tulemus on negatiivne, siis semester pole veel alanud
		else {
		$semesterprogress = "\n <p>Semester on lõppenud!</p> \n";} //kui tulemus on suurem kui 156 (päeva), siis on semester lõppenud
	}
	
	//-------Tuleb siis leida nädalapäeva nimetus ja see välja kuvada-------
	setlocale(LC_TIME, 'et_EE.utf8');
	$day_name = strftime('%A');
	$todaysweekdayhtml = "<p> Täna on ". $day_name.".</p>";  
	
	//----------- loeme piltide kataloogi sisu----------------
	$picsdir = "../../pics/"; //siin kataloogis asuvad pildid. Kuna mul on millegipärast vr kataloogis veel üks vr, siis tuleb kahed punktid ette panna, et kood õige kausta leiaks
	$allfiles = array_slice(scandir($picsdir), 2); //massiivi lisame kõik kaustas olevad pildid ning kuna 2 esimest on kataloog ja ülemkataliig (ehk . ja ..), siis need lõikame maha
	//echo $allfiles[5]; selleks, et kontrollida, kas kõik pildid loetakse sisse, siis kontrollime seda echo-ga. programmis tuleb see välja kommenteerida
	//var_dump($allfiles); 
	$allowedphototypes = ["image/jpeg", "image/png"]; //lisame massiivi libatud failitüüpide nimed
	$picfiles = []; // uus tühi massiiv pildifailide jaoks
	
	//for($x = 0; $x <10;$++){  //saame tekitada võimalikke tegevusi pildidega, koodinäide lihtsalt vahepeal
		//tegevus
	//}
	foreach($allfiles as $file){   // korratakse tegevust kuni allfiles massiivis ridu on
		$fileinfo = getimagesize($picsdir .$file);  // võetakse massiivist järjekorras failinimed ja tehakse päring faili sisu kohta
		//var_dump($fileinfo);
		if(isset($fileinfo["mime"])){   //kui faili infos on olemas tyyp mime siis tehakse järgnevat 
			if(in_array($fileinfo["mime"], $allowphototypes)){   //kui failiinfo mime sisu esineb lubatud failitüüpide masiivis siis tehakse järgnevat 
				array_push($picfiles, $file); //lisatakse massiivi picfiles faili nimi
			}
		}
	}
	//------------fotode massiivist kolme suvalise foto valimine------------
	//$photocount = count($picfiles);
	//$photonum = mt_rand(0, $photocount-1);  
	//$randomphoto = $picfiles[$photonum]; selle käsuga valitakse üks suvaline foto
	$randomphoto = array_rand($picfiles, 3); //peale seda rida lõpetame php ära, kuna peale seda tuleb html
?> 




<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>
	<?php
		echo $myname;
	?>
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php
		echo $timehtml;
		echo $semesterdurhtml;
		echo $semesterprogress;
		echo $todaysweekdayhtml;
	?>

	<img width= "250px" src ="<?php echo $picsdir .$picfiles[$randomphoto[0]]; ?>" alt = "vaade Haapsalust">
	<img width= "250px" src ="<?php echo $picsdir .$picfiles[$randomphoto[1]]; ?>" alt = "vaade Haapsalust">
	<img width= "250px" src ="<?php echo $picsdir .$picfiles[$randomphoto[2]]; ?>" alt = "vaade Haapsalust">
	<!--https://tigu.hk.tlu.ee/~andrus.rinde/vr2021/pics/IMG_0177.JPG-->
</body>
</html>