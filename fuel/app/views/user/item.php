<div class="col-12 col-sm-4 col-lg-3 dash-product-square">
        <div class="added-by">&nbsp;</div>
        <a href="<?= Uri::create($quest->url()) ?>" class="dash-product-image-div" style="background-image:url(<?= $quest->default_thumb_url(250, 220) ?>)">
                <div class="product-name"><?= $quest->name() ?></div>
                <span class="close dash-close"><span class="badge"><?= $quest->total_unseen_notifications() ?></span>
        </a>
</div>

