<?php
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
    if (!file_exists($value.'/mode') && !file_exists($value.'/rating')) {
        unset($list[array_search($value, $list)]);
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
<input class='actionButton'  type="button" name="<?=$view;?>" value="<?=$view;?>" onclick="swap(aField.name, qField.name, viewField.name);">
<input class='actionButton'  type="button" name="<?=$alpha;?>" value="<?=$alpha;?>" onclick="rotate(aField.name, qField.name, viewField.name);">
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
