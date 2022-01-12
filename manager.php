<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Entity Manager</title>
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
</script>
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
<input class='actionButton' type="button" style="width:64px;" value="INIT" onclick="manage('init', entityID.value, entities.value);">
<input class='actionButton' type="button" style="width:64px;" value="KILL" onclick="manage('kill', entityID.value, entities.value);">
<input class='actionButton' type="button" style="width:86px;" value="MERGE" onclick="manage('merge', entityID.value, entities.value);">
<input class='actionButton' type="button" style="width:86px;" value="DIVIDE" onclick="manage('divide', entityID.value, entities.value);">
</p>
<p align="center">
<input class='actionButton' type="button" style="width:64px;" value="JOIN" onclick="manage('join', entityID.value, entities.value);">
<input class='actionButton' type="button" style="width:80px;" value="LEAVE" onclick="manage('leave', entityID.value, entities.value);">
</p>
<p align="center">
<input class='actionButton' type="button" style="width:96px;" value="MODIFY" onclick="manage('modify', entityID.value, entities.value);">
<input class='actionButton' type="button" style="width:96px;" value="UPDATE" onclick="seq('i,from,manager,flossely;i,from,entity,flossely');">
<input class='actionButton' type="button" style="width:64px;" value="EXIT" onclick="window.location.href = 'index.php';">
</p>
</body>
</html>
