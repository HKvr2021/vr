<?php

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veebirakendused ja nende loomine</title>
</head>
<body>
    <h1>Uudite lisamine</h1>
    <p>See leht on valminud õppetöö raames!</p>
    <hr>
    <form>
        <label for="news_title_input">Uudise pealkiri</label>
        <input type= "text" id="news_title_input" name="news_title_input" placeholder="Pealkiri">
        <br>
        <label for="news_content_input">Uudise sisu</label>
        <textarea id="news_content_input" name="news_content_input" placeholder="Uudise text" rows="6" cols="40"></textarea>
        <br>
        <label for="news_author_input">Uudise lisaja nimi</label>
        <input type="text" id="news_author_input" name="news_author_input" placeholder="Nimi">
        <br>
        <input type="submit" name="news_submit" value="Salvesta uudis!">
        
    </form>
</body>
</html>