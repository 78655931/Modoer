<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>" />
<title>Modoer</title>
<style>
#order {
    margin-left: auto;
    margin-right: auto;
    margin-top: 100px;
    border: 1px solid #C8C8C8;
    width: 600px;
}
#msg {
    padding-top: 50px;
    text-align: center;
}
#site {
    padding-top: 50px;
    margin-bottom: 5px;
    margin-right: 5px;
    text-align: right;
}
</style>
</head>

<body>
<div id="order">
    <div id="msg"><?=$msg?></div>
    <div id="site"><?=$_CFG['sitename']?></div>
</div>
</body>
</html>