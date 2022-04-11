<?php

$limitsArr =
[
    '' => 'A',
    'g' => 'G',
    'm' => 'M',
    'r' => 'R',
    'x' => 'X',
];
$alphasArr =
[
    'l' => '&larr;',
    'u' => '&uarr;',
    'r' => '&rarr;',
    'd' => '&darr;',
];

$dir = '.';
if ($_REQUEST) {
    $q = ($_REQUEST['q']) ? $_REQUEST['q'] : '';
    if ($q != '') {
        $list = str_replace($dir.'/','',(glob($dir.'/*{'.$q.'}*', GLOB_ONLYDIR | GLOB_BRACE)));
    } else {
        $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    }
    $limit = ($_REQUEST['limit']) ? $_REQUEST['limit'] : '';
    $alpha = ($_REQUEST['alpha']) ? $_REQUEST['alpha'] : 'u';
} else {
    $q = '';
    $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    $limit = '';
    $alpha = 'u';
}

if ($alpha == 'l' || $alpha == 'r') {
    $measure = 'width';
} else {
    $measure = 'height';
}

foreach ($list as $key=>$value) {
  if (!file_exists($value.'/mode') && !file_exists($value.'/rating')) {
    unset($list[array_search($value, $list)]);
  } else {
    $profRating = file_get_contents($value.'/rating');
    $profMode = file_get_contents($value.'/mode');
    if ($profRating < 0) {
      unset($list[array_search($value, $list)]);
    } else {
      if ($limit == 'r' || $limit == 'x') {
        if (!file_exists($value.'/foot'.$alpha.'.png')) {
          unset($list[array_search($value, $list)]);
        }
      } elseif ($limit == 'g' || $limit == 'm') {
        if (file_exists($value.'/foot'.$alpha.'.png')) {
          unset($list[array_search($value, $list)]);
        }
      }
    }
  }
}

?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Stats</title>
<link rel="shortcut icon" href="sys.stats.png?rev=<?=time();?>" type="image/x-icon">
<link href="system.css?rev=<?=time();?>" rel="stylesheet">
<script src="base.js?rev=<?=time();?>"></script>
<script src="jquery.js?rev=<?=time();?>"></script>
<script src="sort.js?rev=<?=time();?>"></script>
<script src="manage.js?rev=<?=time();?>"></script>
<script>
window.onload = function() {
    document.getElementById('search').focus();
}
function entityStatsSearch(q) {
    window.location.href = '?q=' + q + '&alpha=' + alphaField.name + '&limit=' + limitField.name;
}
function limit(x) {
    window.location.href = '?q=' + qField.name + '&alpha=' + alphaField.name + '&limit=' + x;
}
function rotate(a) {
    window.location.href = '?q=' + qField.name + '&alpha=' + a + '&limit=' + limitField.name;;
}
</script>
</head>
<body>
<div class='top'>
<p align="center">
<input style="width:47%;" type="text" id="search" placeholder="Enter the search query" value="" onkeydown="if (event.keyCode == 13) {
    entityStatsSearch(this.value);
}">
<input class='actionButton' type="button" value=">" onclick="entityStatsSearch(search.value);">
<select id="chooseLimit" onchange="limit(chooseLimit.options[chooseLimit.selectedIndex].id);">
<option><?=$limitsArr[$limit];?></option>
<?php foreach ($limitsArr as $key=>$value) { ?>
<option id="<?=$key;?>"><?=$value;?></option>
<?php } ?>
</select>
<select id="chooseAlpha" onchange="rotate(chooseAlpha.options[chooseAlpha.selectedIndex].id);">
<option><?=$alphasArr[$alpha];?></option>
<?php foreach ($alphasArr as $key=>$value) { ?>
<option id="<?=$key;?>"><?=$value;?></option>
<?php } ?>
</select>
<input class='actionButton' type="button" value="X" onclick="window.location.href = 'index.php';">
<input type='hidden' id='qField' name="<?=$q;?>">
<input type='hidden' id='alphaField' name="<?=$alpha;?>">
<input type='hidden' id='limitField' name="<?=$limit;?>">
</p>
</div>
<div class='panel'>
<?php if ($limit == 'x') { ?>
<p align='center'>
<?php
    foreach ($list as $key=>$value) {
        $thymode = file_get_contents($value.'/mode');
        $thyrating = file_get_contents($value.'/rating');
        $footlist = str_replace($value.'/','',(glob($value.'/foot'.$alpha.'*.png')));
        foreach ($footlist as $iter=>$item) {
            $icon = $value.'/'.$item;
            $link = $icon;
?>
<img class='actionIconButton' style="<?=$measure;?>:98%;" name="<?=$link;?>" title="<?=$value;?>" src="<?=$value.'/'.$item;?>?rev=<?=time();?>" onclick="window.location.href=this.name;">
<?php }} ?>
</p>
<?php } else { ?>
<table id="table" width="100%">
<thead>
<tr>
<th width="10%">Icon</th>
<th width="25%">
<a href="javascript:SortTable(1,'T');">Name</a></th>
<th width="12%">
<a href="javascript:SortTable(2,'N');">Rating</a></th>
<th width="28%">Actions</th>
</tr>
</thead>
<tbody>
<?php
foreach ($list as $key=>$value) {
    $thymode = file_get_contents($value.'/mode');
    $thyrating = file_get_contents($value.'/rating');
    $icon = (file_exists($value.'/favicon.png')) ? $value.'/favicon.png' : "sys.usr.png";
    $link = $value;
    if (file_exists($value.'/foot'.$alpha.'.png')) {
        $entityType = 'NSFW Account';
        $entTypeIMG = $value.'/foot'.$alpha.'.png';
        $entTypeLink = "stats.php?q=".$value."&alpha=".$alpha."&limit=x";
    } else {
        $entityType = 'Normal Account';
        $entTypeIMG = 'sys.launch.png';
        $entTypeLink = $value;
    }
?>
<tr>
<td>
<a href="<?=$icon;?>">
<img width="80%" class='actionIcon' src="<?=$icon;?>?rev=<?=time();?>">
</a>
</td>
<td>
<a href="<?=$link;?>"><?=$value;?></a>
</td>
<td>
<?=$thyrating;?>
</td>
<td>
<img class='actionIconButton' width="25%" src="sys.downvote.png?rev=<?=time();?>" title="Downvote" name="<?=$value;?>" onclick="vote(this.name, 'down');">
<img class='actionIconButton' width="30%;" src="<?=$entTypeIMG;?>?rev=<?=time();?>" title="<?=$entityType;?>" name="<?=$value;?>" onclick="window.location.href = '<?=$entTypeLink;?>';">
<img class='actionIconButton' width="25%" src="sys.upvote.png?rev=<?=time();?>" title="Upvote" name="<?=$value;?>" onclick="vote(this.name, 'up');">
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
</div>
</body>
</html>
