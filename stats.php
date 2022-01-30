<?php
function whatSide($a) {
    define('UP_ARROW', '&#9757;');
    define('DOWN_ARROW', '&#9759;');
    define('LEFT_ARROW', '&#9754;');
    define('RIGHT_ARROW', '&#9755;');
    if ($a == 'u') {
        return UP_ARROW;
    } elseif ($a == 'd') {
        return DOWN_ARROW;
    } elseif ($a == 'l') {
        return LEFT_ARROW;
    } elseif ($a == 'r') {
        return RIGHT_ARROW;
    }
}

$dir = '.';
if ($_REQUEST) {
    $q = ($_REQUEST['q']) ? $_REQUEST['q'] : '';
    if ($q != '') {
        $list = str_replace($dir.'/','',(glob($dir.'/*{'.$q.'}*', GLOB_ONLYDIR | GLOB_BRACE)));
    } else {
        $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    }
    $view = ($_REQUEST['view']) ? $_REQUEST['view'] : 0;
    $alpha = ($_REQUEST['alpha']) ? $_REQUEST['alpha'] : 'u';
    if (($view % 2) != 0) {
        foreach ($list as $key=>$value) {
            if (!file_exists($value.'/foot'.$alpha.'.png')) {
                unset($list[array_search($value, $list)]);
            }
        }
    }
} else {
    $q = '';
    $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    $view = 0;
    $alpha = 'u';
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
    window.location.href = 'stats.php?q=' + q + '&alpha=' + aField.name + '&view=' + viewField.name;
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
function swap(a, q, x) {
    x = 1 - x;
    window.location.href = 'stats.php?q=' + q + '&alpha=' + a + '&view=' + x;
}
function rotate(a, q, x) {
    if (a == 'u') {
        a = 'r';
    } else if (a == 'r') {
        a = 'd';
    } else if (a == 'd') {
        a = 'l';
    } else if (a == 'l') {
        a = 'u';
    }
    window.location.href = 'stats.php?q=' + q + '&alpha=' + a + '&view=' + x;
}
</script>
</head>
<body>
<div class='top'>
<p align="center">
<input style="width:60%;" type="text" id="search" placeholder="Enter the search query" value="" onkeydown="if (event.keyCode == 13) {
    find(this.value);
}">
<input class='actionButton' type="button" value=">" onclick="find(search.value);">
<?php if ($view == 1) { ?>
<input class='actionButton'  type="button" name="<?=$view;?>" value="<" onclick="swap(aField.name, qField.name, viewField.name);">
<?php } else { ?>
<input class='actionButton'  type="button" value="R" onclick="manage('reset', '', '');">
<?php } ?>
<input class='actionButton'  type="button" name="<?=$alpha;?>" value="<?=whatSide($alpha);?>" onclick="rotate(aField.name, qField.name, viewField.name);">
<input class='actionButton' type="button" value="X" onclick="window.location.href = 'index.php';">
<input type='hidden' id='qField' name="<?=$q;?>">
<input type='hidden' id='aField' name="<?=$alpha;?>">
<input type='hidden' id='viewField' name="<?=$view;?>">
</p>
</div>
<div class='panel'>
<?php if (($view % 2) == 0) { ?>
<table id="table" width="100%">
<thead>
<tr>
<th width="8%">Icon</th>
<th width="20%">
<a href="javascript:SortTable(2,'T');">Name</a></th>
<th width="12%">
<a href="javascript:SortTable(3,'N');">Rating</a></th>
<th width="30%">Actions</th>
</tr>
</thead>
<tbody>
<?php
foreach ($list as $key=>$value) {
    $thymode = file_get_contents($value.'/mode');
    $thyrating = file_get_contents($value.'/rating');
    if ($thyrating >= 0) {
        $icon = (file_exists($value.'/favicon.png')) ? $value.'/favicon.png' : "sys.usr.png";
        $link = $value;
    } elseif ($thyrating < 0) {
        $icon = 'sys.error.png';
        $link = "javascript:manage('kill', '', '".$value."');";
    }
    if (file_exists($value.'/foot'.$alpha.'.png')) {
        $entityType = 'FEET PICS';
        $entTypeIMG = 'sys.foot.png';
        $entTypeOnClick = "window.location.href = 'stats.php?q=".$value."&alpha=".$alpha."&view=1';";
    } elseif (file_exists($value.'/get.php')) {
        $entityType = 'SYSTEM';
        $entTypeIMG = 'sys.launch.png';
        $entTypeOnClick = "window.location.href = '".$value."';";
    } elseif (file_exists($value.'/name') && $value.'/description')) {
        $entityType = 'BUSINESS';
        $entTypeIMG = 'sys.help.png';
        $entTypeOnClick = "window.location.href = '".$value."';";
    } else {
        $entityType = 'PROFILE';
        $entTypeIMG = 'sys.img.png';
        $entTypeOnClick = "window.location.href = '".$value."';";
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
<?php if ($entityType == 'FEET PICS') { ?>
<img width="20%" src="<?=$entTypeIMG;?>?rev=<?=time();?>" title="<?=$entityType;?>" name="<?=$value;?>" onclick="<?=$entTypeOnClick;?>">
<?php } else { ?>
<img width="20%" src="sys.img.png?rev=<?=time();?>" title="<?=$entityType;?>" name="<?=$value;?>" onclick="window.location.href = this.name + '/favicon.png';">
<?php } ?>
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
            if ($thyrating >= 0) {
                $icon = $value.'/'.$item;
                $link = $icon;
            } elseif ($thyrating < 0) {
                $icon = 'sys.error.png';
                $link = "javascript:manage('kill', '', '".$value."');";
            }
?>
<img style="width:96%;" name="<?=$link;?>" title="<?=$value;?>" src="<?=$value.'/'.$item;?>?rev=<?=time();?>" onclick="window.location.href=this.name;">
<?php }}} ?>
</div>
</body>
</html>
