<?php
$id = $_REQUEST['id'];
$name = '@'.$id;
$leftIcon = 'sys.-1.png';
$centerIcon = 'sys.0.png';
$rightIcon = 'sys.1.png';
$leftTitle = 'Left';
$centerTitle = 'Moderate';
$rightTitle = 'Right';
// EVALUATION DATA
if (!file_exists($id.'/eval.ini')) {
    file_put_contents($id.'/eval.ini', "0=||=0=||=0");
    chmod($id.'/eval.ini', 0777);
}
$allSidesRating = file_get_contents($id.'/eval.ini');
$expAllSideRating = explode('=||=', $allSidesRating);
$leftRating = $expAllSideRating[0];
$centerRating = $expAllSideRating[1];
$rightRating = $expAllSideRating[2];
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Evaluate <?=$name;?></title>
<link rel="shortcut icon" href="sys.eval.png?rev=<?=time();?>" type="image/x-icon">
<link href="system.css?rev=<?=time();?>" rel="stylesheet">
<script src="jquery.js"></script>
<script src="sort.js"></script>
<script>
function evalID(id,key) {
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
    xmlhttp.open("GET","eval.php?id="+id+"&key="+key,false);
    xmlhttp.send();
}
</script>
</head>
<body>
<div class='top'>
<p align="center">
<?='Evaluating '.$name.' '.$leftRating.'/'.$centerRating.'/'.$rightRating.' ';?>
<input class='actionButton' type="button" value="<" onclick="window.location.href = 'stats.php';">
<input class='actionButton' type="button" value="X" onclick="window.location.href = 'index.php';">
</p>
</div>
<div class='panel'>
<table id="table" width="100%">
<thead>
<tr>
<th width="8%">Mode</th>
<th width="20%">
<a href="javascript:SortTable(1,'T');">Name</a></th>
<th width="30%">
<a href="javascript:SortTable(2,'N');">Rating</a></th>
<th width="30%">Actions</th>
</tr>
</thead>
<tbody>
<tr>
<td>
<a href="<?=$leftIcon;?>">
<img width="80%" src="<?=$leftIcon;?>?rev=<?=time();?>">
</a>
</td>
<td>
<a href="<?=$id;?>"><?=$leftTitle;?></a>
</td>
<td>
<?=$leftRating;?>
</td>
<td>
<img width="30%" src="sys.upvote.png?rev=<?=time();?>" title="Vote" name="<?=$id;?>" onclick="evalID(this.name,'left');">
</td>
</tr>
<tr>
<td>
<a href="<?=$centerIcon;?>">
<img width="80%" src="<?=$centerIcon;?>?rev=<?=time();?>">
</a>
</td>
<td>
<a href="<?=$id;?>"><?=$centerTitle;?></a>
</td>
<td>
<?=$centerRating;?>
</td>
<td>
<img width="30%" src="sys.upvote.png?rev=<?=time();?>" title="Vote" name="<?=$id;?>" onclick="evalID(this.name,'center');">
</td>
</tr>
<tr>
<td>
<a href="<?=$rightIcon;?>">
<img width="80%" src="<?=$rightIcon;?>?rev=<?=time();?>">
</a>
</td>
<td>
<a href="<?=$id;?>"><?=$rightTitle;?></a>
</td>
<td>
<?=$rightRating;?>
</td>
<td>
<img width="30%" src="sys.upvote.png?rev=<?=time();?>" title="Vote" name="<?=$id;?>" onclick="evalID(this.name,'right');">
</td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
