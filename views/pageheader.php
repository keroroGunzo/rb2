<!-- br-pageheader -->
<?php require_once('../config/phconfig.php'); ?>
<div class="br-pageheader">
    <nav class="breadcrumb pd-0 mg-0 tx-12">
        <?php foreach ($page['breadcrumb'] as $i => $crumb): ?>
            <?php if ($i === array_key_last($page['breadcrumb'])): ?>
                <span class="breadcrumb-item active"><?= $crumb ?></span>
            <?php else: ?>
                <a class="breadcrumb-item" href="javascript:;"><?= $crumb ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
</div><!-- br-pageheader -->

<!-- br-pagetitle -->
<div class="br-pagetitle">
    <i class="<?= $page['icon'] ?>" style="font-size:60px"></i>
    <div>
        <h4><?= $page['title'] ?></h4>
        <p class="mg-b-0"><?= $page['subtitle'] ?></p>
    </div>
</div><!-- br-pagetitle -->