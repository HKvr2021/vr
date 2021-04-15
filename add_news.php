<?php //peale php algusmärgenit ja enne php lõumärgenit peab olema üks tühi rida
//var_dump($_POST)--> selle meetodiga saab kätte kogu POST massiivi, näitab elementide kaupa.
// On olemas ka $_GET // näitab kõiki postitusi

require_once "../../../../conf.php";// paneme juhise kus on serveri andmed/paroolid ja kus andmed asuvad
//echo $server_host;
$news_input_error = null; //muutuja, mis hoiab endas erinevaid veateateid. Algväärtus tühi ehk "null".
$titleSave = null; // pealkirja meeldejätmiseks - väärtus vaikimisi null
$contentSave = null; // sisu meeldejätmiseks - väärtus vaikimisi null
$authorSave = null; // autori meeldejätmiseks - väärtus vaikimisi null
	
	if(isset($_POST["news_submit"])){ //esmalt kontrollime, et kas üldse nuppu klõpsati, sest enne seda pole sealt midagi otsida mõtet. isset=is set= kas on seatud mingi väärtus?
		if(empty($_POST["news_title_input"])) {
			$news_input_error = "Uudise pealkiri on puudu! "; //annab veateate
			$contentSave = (isset($_POST['news_content_input']) ? $_POST['news_content_input'] : ''); // väärtus, mis salvestatakse postituse sisu meeldejätmiseks vaatamata veateatele
			$authorSave = (isset($_POST['news_author_input']) ? $_POST['news_author_input'] : ''); // väärtus, mis salvestatakse postituse autori meeldejätmiseks
		}
		if(empty($_POST["news_content_input"])){
			$news_input_error .= "Uudise tekst on puudu! ";		//.= võta senine ja pane juurde ehk kui on varasemalt juba üks viga olnud, siis teise ja iga järgneva vea toome ka välja. Lõppu lisame tühiku, et lausete vahel oleks tühikud. 
			$titleSave = (isset($_POST['news_title_input']) ? $_POST['news_title_input'] : ''); // väärtus, mis salvestatakse pealkirja meeldejätmiseks
	        $authorSave = (isset($_POST['news_author_input']) ? $_POST['news_author_input'] : ''); // väärtus, mis salvestatakse autori meeldejätmiseks
			//autori puudumine ei anna viga
		}
		if(empty($news_input_error)){ //siis pole vigu esinenud ja kõik on hästi läinud ja siis teeme järgmisd sammud ehk:
		// valideerime sisendandmed
			$news_title_input = test_input($_POST["news_title_input"]);
			$news_content_input = test_input($_POST["news_content_input"]);
			$news_author_input = test_input($_POST["news_author_input"]);
		// salvestame andmebaasi
			store_news("news_title_input", "news_content_input", "news_author_input"); //store  ehk salvestame  sisu andmebaasi
		}
	}

	function store_news(){ 
		//echo $news_title .$news_content .$news_author; üsna koledas stiitls saab esile kutsuda salvestatud andmed
		//echo $GLOBALS["server_host"];

		// loome andmebaasi serveri ja baasiga ühenduse
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]); //conn= muutuja, mis tähendab connection, klass "mysqli" võetakse kasutusse ning ta ootab parameetreid, mille me conf faili panime ja millist serverit ta hakkab kasutama
		
		$conn -> set_charset("utf8");//määrame suhtluseks kodeeringu
		// valmistan ette SQL käsu- andmebaasiga suhtlemise keel, millega moodustatakse päring
		$stmt = $conn -> prepare("INSERT INTO vr21_news (vr21_news_news_title, vr21_news_news_content, vr21_news_news_author) VALUES (?,?,?)"); //stmt= statment = käsk. Jutumärkides on nö ametlik käsk. Me tahame andmebaasimidagi kirjutada ehk käsk on INSERT INTO suurtähtedega. Edasi täpsustame, kuhu me need asjad kirjutame (tabeli nimi vr21_news ja täpsustame seal sees ka konkreetsed ja täpsete nimedega andmeväljad sulgudes, eraldame komadega). Lõpupoole tuleb ka veel öelda, mis väärtused (VALUES) nendel väljadel on. Kuna me et tea, mis sinna väljadele kirjutatakse, siis meie paneme küsimärgid.
		echo $conn -> error; //meile vajalik, sest kui käsu ettevalmistuse käigus on tehtud viga, siis ta kontrollib käsku ja ütleb, et midagi käsureal ei tööta.
	
		$stmt -> bind_param("sss", $news_title, $news_content, $news_author); // ?-ga andmete sidumine (bind_param) i-integer, s-string, d-decimal, peavad ühtima väljadega- siin ei tohi vigu teha. 
		$stmt -> execute(); //stmt täidetakse, võetakse see pikk käsk ja täidetakse andmetega.
		$stmt -> close(); //panen käsu kinni
		$conn -> close(); //panen ühenduse kinni st login andmebaasist välja
	}
	function test_input($input) { // sisendandmete valideerimise funktsioonid
		$data = trim($input); 
		$data = stripslashes($input);
		$data = htmlspecialchars($input);
		return $input;
	}

?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="UTF-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>Uudiste lisamine !!!</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> <!--POST meetod, mida kirjutatakse suure läbiva tähega, serveris salvestatakse nad massiivi-->
		<label for="news_title_input">Uudise pealkiri: </label> <br> <!--kohustuslik on lisada label, pealkiri, soovituslik kirjutada väiketähtedega, id peab olema arusaadav ja sisukas, et mida see väli üldse teeb ning nubriga ei tohi alustada-->
		<!--kasutame varasemalt defineeritud php koodi, et salvestada teksti, mille eelnevalet ehk enne veateadet sisesatasime-->
		<input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri" value="<?php echo $titleSave; ?>"><br> <!-- sisendvälja INPUT formuleerimine, vaikimisi tekstitüüpi, aga võib ikkagi defineerida, ID on vahjalik defineerida nt Javascripti jaoks. Name ja ID on sama väärtusega, kuna frontend kasutab ID-d ja meie PHP vajab NAME väärtust. Placeholder ehk kohatäitja vihjab kasutajale, mida sinna väljale kirjutada tuleb, kuid süsteem seda infot ei kasuta-->
		<br>
		<label for="news_content_input">Uudise tekst:</label> <br>
		<textarea name="news_content_input" id="news_content_input" placeholder="Uudise tekst" rows="7" cols="45"><?php echo $contentSave; ?></textarea><br> <!-- kuna teksti võib olla rohkem, kui paar rida, siis anname talle väärtuse textarea ning määrame, kui suur/pikk see tekst olla võiks,  textarea'l on ka vaikimisi mingi väärtus, kuid meie anname talle enda jaoks sobiva vääruse. Textarea'l peab olema ka lõpumärgend </textarea>-->
		<br>
		<label for="news_author_input">Uudise lisaja nimi:</label> <br>
		<input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi" value="<?php echo $authorSave; ?>"><br> <!-- võib kopeerida abaloogseid välju veelgi-->
		<input type="submit" name="news_submit" value="Salvesta uudis!"> <!-- submit on nö nupuväli, mis saadab kogu eelneva info serverisse-->
		<br>
	</form>
	<p><?php echo $news_input_error; ?></p> <!-- ka asutajatele toome veebilehel tekkinud veateated välja/nähtavaks.
</body>s
</html>