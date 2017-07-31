
<section class="modern-card vertical <?=$styling?>">
    <?php if (!empty($img)): ?>
        <div class="thumbnail">
            <img src="<?= ipFileUrl('file/repository/' . $img[0]) ?>" alt="<?= !empty($title) ? $title : 'image' ?>">
        </div>
    <?php endif; ?>

    <div class="data">
        <?php if (!empty($title)): ?> <h3><?=$title?></h3> <?php endif; ?>
        <?php if (!empty($text)): ?>
            <div><?=$text?></div>
        <?php endif; ?>

        <?php if (!empty($link)): ?>
            <a class="button" href="<?=$link?>" <?=$openInTab == true ? 'target="blank"' : ''?>>
                <?=!empty($linkLabel) ? $linkLabel : $link?>
            </a>
        <?php endif; ?>
    </div>
</section>
