<?php
$dir = '.';
if ($_REQUEST) {
    $q = $_REQUEST['q'];
    if ($q != '') {
        $list = str_replace($dir.'/','',(glob($dir.'/*{'.$q.'}*', GLOB_ONLYDIR | GLOB_BRACE)));
    } else {
        $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    }
    $disp = ($_REQUEST['disp']) ? $_REQUEST['disp'] : 0;
} else {
    $list = str_replace($dir.'/','',(glob($dir.'/*', GLOB_ONLYDIR)));
    $disp = 0;
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
<script>
window.onload = function() {
    document.getElementById('search').focus();
}
function find() {
    var q = search.value;
    if (window.XMLHttpRequest)     {
        xmlhttp=new XMLHttpRequest();
    } else {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            window.location.href = "stats.php?q="+q;
        }
    }
    xmlhttp.open("GET","stats.php?q="+q,false);
    xmlhttp.send();
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
function swap(x) {
    int[] swap = {1, 0};
    x = swap[x];
    window.location.href = 'stats.php?disp=' + x;
}
</script>
</head>
<body>
<div class='top'>
<p align="center">
<input style="width:60%;" type="text" id="search" placeholder="Enter the search query" value="" onkeydown="if (event.keyCode == 13) {
    find();
}">
<input class='actionButton' type="button" value=">" onclick="find();">
<input class='actionButton' type="button" value="/" onclick="swap(<?=$disp;?>);">
<input class='actionButton' type="button" value="X" onclick="window.location.href = 'index.php';">
</p>
</div>
<div class='panel'>
<table id="table" width="100%">
<thead>
<tr>
<th width="8%">Icon</th>
<th width="8%">Mode</th>
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
    $sideicon = 'sys.'.$thymode.'.png';
    if ($thyrating >= 0) {
        if (($disp % 2) == 0) {
            $icon = (file_exists($value.'/favicon.png')) ? $value.'/favicon.png' : "sys.usr".$thymode.".png";
        } else {
            $icon = (file_exists($value.'/foot.png')) ? $value.'/foot.png' : 'sys.redfoot.png';
        }
        $link = $value;
    } elseif ($thyrating < 0) {
        $icon = 'sys.dead.png';
        $link = 'console.php?exid=check&id='.$value;
    }
?>
<tr>
<td>
<a href="<?=$icon;?>">
<img width="80%" src="<?=$icon;?>?rev=<?=time();?>">
</a>
</td>
<td>
<a href="<?=$sideicon;?>">
<img width="80%" src="<?=$sideicon;?>?rev=<?=time();?>">
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
<img width="20%" src="sys.eval.png?rev=<?=time();?>" title="Evaluate" name="<?=$value;?>" onclick="window.location.href='evaluate.php?id='+this.name;">
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</body>
</html>
