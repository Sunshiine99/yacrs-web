<?php if($breadcrumbs): ?>
<div class="container">
    <nav id="breadcrumb" aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            <?php foreach($breadcrumbs->getItems() as $breadcrumb): ?>
                <?php if($breadcrumb->hasLink()): ?>
                    <li class="breadcrumb-item">
                        <a href="<?=$breadcrumb->getLink()?>">
                            <?=$breadcrumb->getTitle()?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?=$breadcrumb->getTitle()?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>
<?php endif; ?>