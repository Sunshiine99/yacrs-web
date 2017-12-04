<?php
if($alert):

$type = $alert->getType() ? $alert->getType() : "";
$message = $alert->getMessage() ? $alert->getMessage() : "";;
$title = $alert->getTitle() ? "<strong>".$alert->getTitle()."</strong> " : "";;
?>
<div class="alert alert-<?=$type?>" role="alert">
    <?=$title?><?=$message?>
</div>
<?php endif; ?>