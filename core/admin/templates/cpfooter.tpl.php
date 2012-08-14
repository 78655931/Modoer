<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<?if($info&&!$_GET['nofooter']) {?>
<div id="footer">
<small>Powered by <a href="http://www.modoer.com" target="_blank">Modoer <?=$version?></a> &copy; 2007 - 2010, <a href="http://www.modoer.com" target="_blank">Moufer Studio</a></small><br />

</div>
<? } ?>
<?=$DEBUG?>
</body>
</html>