<?php
$dir = '.';
if ($_REQUEST) {
    $q = $_REQUEST['q'];
    if ($q != '') {
        $list = str_replace($dir.'/','',(glob($dir.'/*{'.$q.'}*', GLOB_ONLYDIR | GLOB_BRACE)));
    } else {
        $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    }
} else {
    $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
}
foreach ($list as $key=>$value) {
    if (!file_exists($value.'/get.php')) {
        unset($list[array_search($value, $list)]);
    }
}
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Launch Platform</title>
<link rel="shortcut icon" href="sys.launch.png?rev=<?=time();?>" type="image/x-icon">
<link href="system.css?rev=<?=time();?>" rel="stylesheet">
<?php include 'base.incl.php'; ?>
<script>
window.onload = function() {
    document.getElementById('search').focus();
}
</script>
</head>
<body>
<div class='top'>
<p align="center">
<input style="width:60%;" type="text" id="search" placeholder="Enter the search query" value="" onkeydown="if (event.keyCode == 13) {
    simpleSearch();
}">
<input class='actionButton' type="button" value=">" onclick="simpleSearch();">
<input class='actionButton' type="button" value="X" onclick="window.location.href = 'index.php';">
</p>
</div>
<div class='panel'>
<p align='center'>
<?php
foreach ($list as $key=>$value) {
    $icon = (file_exists($value.'/favicon.png')) ? $value.'/favicon.png' : 'sys.launch.png';
    $link = (file_exists($value.'/index.php')) ? $value : $value.'/get.php?key=i&pkg=from&repo=assemble&user=flossely';
?>
<img class='actionIconButton' style="width:16%;" src="<?=$icon;?>?rev=<?=time();?>" name="<?=$link;?>" onclick="window.location.href = this.name;" title="<?=$value;?>">
<?php } ?>
<img class='actionIconButton' style="width:16%;" src="sys.exit.png?rev=<?=time();?>" onclick="window.location.href = '../../';" title="Exit">
</p>
</div>
</body>
</html>
