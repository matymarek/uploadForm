<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload souboru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
          crossorigin="anonymous">
</head>
<body>
<?php
$ok = true;
$targetFile = null;

if ($_FILES) {
    $targetDir = "saves/";
    $targetFile = $targetDir . basename($_FILES['uploadedName']['name']);
    $fileType = strtolower( pathinfo( $targetFile, PATHINFO_EXTENSION ) );

    if ($_FILES['uploadedName']['error'] != 0) {
        console_log("Chyba serveru při uploadu");
        $ok = false;
    }

    //kontrola existence
    elseif (file_exists($targetFile)) {
        console_log("Soubor již existuje.");
        $ok = false;
    }

    //kontrola velikosti
    elseif ($_FILES['uploadedName']['size'] > 8000000) {
        console_log("Soubor je příliš velký.");
        $ok = false;
    }


    //kontrola typu
    elseif (explode("/", $_FILES['uploadedName']['type'])[0] !== "image" &&
            explode("/", $_FILES['uploadedName']['type'])[0] !== "video" &&
            explode("/", $_FILES['uploadedName']['type'])[0] !== "audio") {
        console_log("Soubor má špatný typ.");
        $ok = false;
    }


    if (!$ok) {
        console_log("Došlo k chybě uploadu.");
    } else {
        //vše je OK
        //přesun souboru
        if (move_uploaded_file($_FILES['uploadedName']['tmp_name'], $targetFile)) {
            console_log("Soubor '" . basename($_FILES['uploadedName']['name']) . "' byl uložen.");
        } else {
            console_log("Došlo k chybě uploadu.");
        }
    }
}

?>
<form method='post' action='' enctype='multipart/form-data'><div>
        <div class="mb-3">
            <label for="uploadedName" class="form-label">Here select or drop file to upload:</label>
            <input type="file" name="uploadedName" class="form-control" accept="image/*, video/*, audio/*"/>
        </div>
        <div class="col-auto">
            <button type="submit" name="submit" class="btn btn-primary mb-3">Submit</button>
        </div>
        <?php if($ok == true && $_FILES && explode("/", $_FILES['uploadedName']['type'])[0] == "image")
                echo "<img src=$targetFile alt='Tady bych měl být'>";
              elseif($ok == true && $_FILES && explode("/", $_FILES['uploadedName']['type'])[0] == "audio")
                echo "<audio src=$targetFile title='Tady bych měl být'>";
              elseif($ok == true && $_FILES && explode("/", $_FILES['uploadedName']['type'])[0] == "video")
                echo "<video src=$targetFile title='Tady bych měl být'>";
              else echo "<p>Soubor nebyl uložen, podrobnosti naleznete v konzoli.</p>";?>
    </div>
</form>
</body>
</html>
<?php
function console_log($output, $with_script_tags = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
        ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
?>