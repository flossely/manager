<?php
$dir = '.';
define('LEFT_COLOR', 'd83d48');
define('CENTER_COLOR', '009f8c');
define('RIGHT_COLOR', '5f677a');
$profList = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
foreach ($profList as $key=>$value) {
    if (!file_exists($value.'/rating') && !file_exists($value.'/mode')) {
        unset($profList[array_search($value, $profList)]);
    } else {
        $profOpenRating = file_get_contents($value.'/rating');
        if ($profOpenRating < 0) {
            unset($profList[array_search($value, $profList)]);
        }
    }
}
$actionFile = file_get_contents('actions');
$actionList = explode(';', $actionFile);
$interFile = file_get_contents('interact');
$interList = explode(';', $interFile);
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Entity Interactor</title>
<link rel="shortcut icon" href="sys.manage.png?rev=<?=time();?>" type="image/x-icon">
<link href="system.css?rev=<?=time();?>" rel="stylesheet">
<script src="base.js"></script>
<script src="jquery.js"></script>
<script src="sort.js"></script>
<script>
function manage(mode, id, data) {
    var dataString = 'mode=' + mode + '&id=' + id + '&data=' + data;
    $.ajax({
        type: "POST",
        url: "manage.php",
        data: dataString,
        cache: false,
        success: function(html) {
            document.location.reload();
        }
    });
    return false;
}
function interact(sub, act, obj) {
    var dataString = 'sub=' + sub + '&act=' + act + '&obj=' + obj;
    $.ajax({
        type: "POST",
        url: "interact.php",
        data: dataString,
        cache: false,
        success: function(html) {
            document.location.reload();
        }
    });
    return false;
}
</script>
</head>
<body>
<div class='top'>
<p align="center">
<select id="chooseProfile">
<?php foreach ($profList as $key=>$value) { ?>
<option id="<?=$value;?>"><?=$value;?></option>
<?php } ?>
</select>
<select id="chooseInteract">
<?php foreach ($interList as $key=>$value) { ?>
<option id="<?=$value;?>"><?=$value.'()';?></option>
<?php } ?>
</select>
<input type="button" value="Next Turn"><br>
<input type="button" value="Reset" onclick="manage('reset', '', '');">
<input type="button" value="U" onclick="seq('i,from,entity,flossely', 'i,from,manager,flossely');">
<input type="button" value="X" onclick="window.location.href = 'index.php';">
</p>
</div>
<div class='panel'>
<p align="center">
<?php
foreach ($profList as $key=>$value) {
    $profNowRating = file_get_contents($value.'/rating');
    $profOpenMode = file_get_contents($value.'/mode');
    if ($profOpenMode > 0) {
        $profColor = RIGHT_COLOR;
    } elseif ($profOpenMode < 0) {
        $profColor = LEFT_COLOR;
    } else {
        $profColor = CENTER_COLOR;
    }
    $profDefineColor = '#'.$profColor;
?>
<input type="button" class='hover' style="background-color:<?=$profDefineColor;?>;color:#fff;" name="<?=$value;?>" value="<?=$value.'('.$profNowRating.')';?>" onclick="interact(chooseProfile.options[chooseProfile.selectedIndex].id, chooseInteract.options[chooseInteract.selectedIndex].id, this.name);">
<?php } ?>
</p>
</div>
</body>
</html>
