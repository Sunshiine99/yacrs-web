<?php if($breadcrumbs): ?>

<div id="breadcrumb">
    <ul class="breadcrumb">

        <?php
        // Foreach breadcrumb
        for($i = 0; $i < $breadcrumbs->count(); $i++) {

            // Get the breadcrumb item
            $item = $breadcrumbs->getItems()[$i];

            // If item has a link
            if($item->hasLink()): ?>

                <li>
                    <a href="<?=$item->getLink()?>"><?=$item->getTitle()?></a>
                </li>

            <?php
            // Otherwise no link
            else: ?>

                <li>
                    <?=$item->getTitle()?>
                </li>

            <?php endif;
        }
        ?>
    </ul>
</div>

<?php endif; ?>