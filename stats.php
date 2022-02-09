<?php
$dir = '.';
if ($_REQUEST) {
    $q = ($_REQUEST['q']) ? $_REQUEST['q'] : '';
    if ($q != '') {
        $list = str_replace($dir.'/','',(glob($dir.'/*{'.$q.'}*', GLOB_ONLYDIR | GLOB_BRACE)));
    } else {
        $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    }
    $mode = ($_REQUEST['mode']) ? $_REQUEST['mode'] : 0;
    $alpha = ($_REQUEST['alpha']) ? $_REQUEST['alpha'] : 'u';
    if (($mode % 2) != 0) {
        foreach ($list as $key=>$value) {
            if (!file_exists($value.'/foot'.$alpha.'.png')) {
                unset($list[array_search($value, $list)]);
            }
        }
    }
    if ($alpha == 'u' || $alpha == 'd') {
        $measure = 'height';
    } else {
        $measure = 'height';
    }
} else {
    $q = '';
    $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    $mode = 0;
    $alpha = 'u';
    $measure = 'height';
}
foreach ($list as $key=>$value) {
    if (!file_exists($value.'/rating') && !file_exists($value.'/mode')) {
        unset($list[array_search($value, $list)]);
    } else {
        $profRating = file_get_contents($value.'/rating');
        if ($profRating < 0) {
            unset($list[array_search($value, $list)]);
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
<script src="base.js"></script>
<script src="jquery.js"></script>
<script src="sort.js"></script>
<script src="manage.js"></script>
<script>
window.onload = function() {
    document.getElementById('search').focus();
}
function find(q) {
    window.location.href = 'stats.php?q=' + q + '&alpha=' + alphaField.name + '&mode=' + modeField.name;
}
function vote(id,key) {
    if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest();
    } else {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            window.location.reload();
        }
    };
    xmlhttp.open("GET","vote.php?id="+id+"&key="+key,false);
    xmlhttp.send();
}
function changeMode(x) {
    x = 1 - x;
    window.location.href = 'stats.php?q=' + qField.name + '&alpha=' + alphaField.name + '&mode=' + x;
}
function rotate(a) {
    if (a == 'u') {
        a = 'r';
    } else if (a == 'r') {
        a = 'd';
    } else if (a == 'd') {
        a = 'l';
    } else if (a == 'l') {
        a = 'u';
    }
    window.location.href = 'stats.php?q=' + qField.name + '&alpha=' + a + '&mode=' + modeField.name;
}
</script>
</head>
<body>
<div class='top'>
<p align="center">
<input style="width:45%;" type="text" id="search" placeholder="Enter the search query" value="" onkeydown="if (event.keyCode == 13) {
    find(this.value);
}">
<input class='actionButton' type="button" value=">" onclick="find(search.value);">
<input class='actionButton'  type="button" value="<" onclick="manage('reset', '', '');">
<input class='actionButton'  type="button" name="<?=$mode;?>" value="<?=$mode;?>" onclick="changeMode(modeField.name);">
<input class='actionButton'  type="button" name="<?=$alpha;?>" value="<?=strtoupper($alpha);?>" onclick="rotate(alphaField.name);">
<input class='actionButton'  type="button" value="P" onclick="seq('i,from,entity,flossely;i,from,manager,flossely');">
<input class='actionButton' type="button" value="X" onclick="window.location.href = 'index.php';">
<input type='hidden' id='qField' name="<?=$q;?>">
<input type='hidden' id='alphaField' name="<?=$alpha;?>">
<input type='hidden' id='modeField' name="<?=$mode;?>">
</p>
</div>
<div class='panel'>
<?php if (($mode % 2) == 0) { ?>
<table id="table" width="100%">
<thead>
<tr>
<th width="10%">Icon</th>
<th width="25%">
<a href="javascript:SortTable(2,'T');">Name</a></th>
<th width="12%">
<a href="javascript:SortTable(3,'N');">Rating</a></th>
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
        $entityType = 'FEET PICS';
        $entTypeIMG = $value.'/foot'.$alpha.'.png';
        $entTypeLink = "stats.php?q=".$value."&alpha=".$alpha."&mode=1";
    } elseif (file_exists($value.'/get.php')) {
        $entityType = 'SYSTEM';
        $entTypeIMG = 'sys.app.png';
        $entTypeLink = $value;
    } elseif (file_exists($value.'/name') && file_exists($value.'/description')) {
        $entityType = 'BUSINESS';
        $entTypeIMG = 'sys.help.png';
        $entTypeLink = $value;
    } else {
        $entityType = 'PROFILE';
        $entTypeIMG = 'sys.dir.png';
        $entTypeLink = $value;
    }
?>
<tr>
<td>
<a href="<?=$icon;?>">
<img width="80%" src="<?=$icon;?>?rev=<?=time();?>">
</a>
</td>
<td>
<a href="<?=$link;?>"><?=$value;?></a>
</td>
<td>
<?=$thyrating;?>
</td>
<td>
<img width="20%" src="sys.downvote.png?rev=<?=time();?>" title="Downvote" name="<?=$value;?>" onclick="vote(this.name, 'down');">
<img style="<?=$measure;?>:20%;position:relative;" src="<?=$entTypeIMG;?>?rev=<?=time();?>" title="<?=$entityType;?>" name="<?=$value;?>" onclick="window.location.href = '<?=$entTypeLink;?>';">
<img width="20%" src="sys.upvote.png?rev=<?=time();?>" title="Upvote" name="<?=$value;?>" onclick="vote(this.name, 'up');">
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php
} else {
    foreach ($list as $key=>$value) {
        $thymode = file_get_contents($value.'/mode');
        $thyrating = file_get_contents($value.'/rating');
        $footlist = str_replace($value.'/','',(glob($value.'/foot'.$alpha.'*.png')));
        foreach ($footlist as $iter=>$item) {
            $icon = $value.'/'.$item;
            $link = $icon;
?>
<img style="<?=$measure;?>:100%;" name="<?=$link;?>" title="<?=$value;?>" src="<?=$value.'/'.$item;?>?rev=<?=time();?>" onclick="window.location.href=this.name;">
<?php }}} ?>
</div>
</body>
</html>
