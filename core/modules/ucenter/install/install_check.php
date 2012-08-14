<?php
$uc_client = MUDDER_ROOT . 'uc_client/client.php';
if(!@is_file($uc_client)) {
    redirect('ucentercp_client_not_exists');
    exit;
}
?>