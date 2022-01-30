<?php
$dir = '.';
define('LEFT_COLOR', 'd83d48');
define('LEFT_FORE_COLOR', 'a2252e');
define('CENTER_COLOR', '009f8c');
define('CENTER_FORE_COLOR', '19635b');
define('RIGHT_COLOR', '5f677a');
define('RIGHT_FORE_COLOR', '383c4a');
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
$launchFile = file_get_contents('launch');
$launchList = explode(';', $launchFile);
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
<script src="manage.js"></script>
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
<select id="chooseLaunch">
<?php foreach ($launchList as $key=>$value) { ?>
<option id="<?=$value;?>"><?=$value.'()';?></option>
<?php } ?>
</select>
</p>
<p align="center">
<input type="button" value="Next Turn">
<input type="button" value="Reset" onclick="manage('reset', '', '');">
<input type="button" value="U" onclick="seq('i,from,entity,flossely;i,from,manager,flossely');">
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
        $profForeColor = RIGHT_FORE_COLOR;
    } elseif ($profOpenMode < 0) {
        $profColor = LEFT_COLOR;
        $profForeColor = LEFT_FORE_COLOR;
    } else {
        $profColor = CENTER_COLOR;
        $profForeColor = CENTER_FORE_COLOR;
    }
    $profDefineColor = '#'.$profColor;
    $profDefineForeColor = '#'.$profForeColor;
?>
<input type="button" class='hover' style="background-color:<?=$profDefineColor;?>;color:#fff;" name="<?=$value;?>" value="<?=$value;?>" onclick="interact(chooseProfile.options[chooseProfile.selectedIndex].id, chooseInteract.options[chooseInteract.selectedIndex].id, this.name);">
<input type="button" class='hover' style="background-color:<?=$profDefineForeColor;?>;color:#fff;" name="<?=$value;?>" value="<?=$profNowRating;?>" onclick="launch(chooseLaunch.options[chooseLaunch.selectedIndex].id, this.name);">
<?php } ?>
</p>
</div>
</body>
</html>
