<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Entity Manager</title>
<link rel="shortcut icon" href="sys.manage.png?rev=<?=time();?>" type="image/x-icon">
<link href="system.css?rev=<?=time();?>" rel="stylesheet">
<script src="base.js?rev=<?=time();?>"></script>
<script src="jquery.js?rev=<?=time();?>"></script>
<script src="sort.js?rev=<?=time();?>"></script>
<script src="manage.js?rev=<?=time();?>"></script>
</head>
<body>
<p align="center">
<label>Entity ID: </label>
<input style="width:60%;" type="text" id="entityID" value="">
</p>
<p align="center">
<label>Entities: </label>
<textarea style="width:62%;height:30%;" id="entities" value="">
</textarea>
</p>
<p align="center">
<select id='actionSel' style="width:100px;">
<?php
$actionFile = file_get_contents('actions');
$actionList = explode(';', $actionFile);
foreach ($actionList as $key=>$value) {
?>
<option id="<?=$value;?>"><?=$value.'()';?></option>
<?php } ?>
</select>
<input class='actionButton' type="button" style="width:86px;" value="ENTER" onclick="manage(actionSel.options[actionSel.selectedIndex].id, entityID.value, entities.value);">
<input class='actionButton' type="button" style="width:96px;" value="UPDATE" onclick="seq('i,from,manager,flossely;i,from,entity,flossely');">
<input class='actionButton' type="button" style="width:64px;" value="EXIT" onclick="window.location.href = 'index.php';">
</p>
</body>
</html>
