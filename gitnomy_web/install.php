<?php

/**
 * This file is part of Gitonomy.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 * (c) Julien DIDIER <genzo.wm@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */
require_once '/var/www/gitonomy/app/bootstrap.php.cache';

use Symfony\Component\HttpFoundation\Request;
use Gitonomy\Component\Requirements\WebGitonomyRequirements;

$requirements = new WebGitonomyRequirements();
?>
<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="bundles/gitonomydistribution/css/main.css" />
    </head>
    <body>
        <div class="gitonomy-install">
            <header>
                <h1>Gitonomy <small>requirements</small></h1>
            </header>
            <section>
                <?php
                        echo '<p>Everything is OK</p>';
                        echo '<p class="welcome-buttons"><a class="btn" href="app_dev.php/install">Continue &raquo;</a>.</p>';
                ?>
            </section>
            <footer>
                <p>Gitonomy is beautiful and you are beautiful, too.</p>
            </footer>
        </div>
    </body>
</html>
