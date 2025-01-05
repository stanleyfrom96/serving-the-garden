<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $site->title() ?> | <?= $page->title() ?></title>
        <?= css(['/assets/css/main.css']) ?>
        <link rel="shortcut icon" href="/assets/favicon.png" type="image/x-icon">
    </head>
    <body>
        <header>

            <?php
            // main menu items
            $items = $pages->listed();

            // only show the menu if items are available
            if($items->isNotEmpty()):

            ?>
            <nav>
            <ul>
                <?php foreach($items as $item): ?>
                <li><a<?php e($item->isOpen(), ' class="active"') ?> href="<?= $item->url() ?>"><?= $item->title()->html() ?></a></li>
                <?php endforeach ?>
            </ul>
          
            </nav>
            <?php endif ?>
        </header>