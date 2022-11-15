<?php
use Cake\Core\Configure;

$theme = file_get_contents(Configure::read("Rejack.Theme.themefile"));
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?= __("ReJACK") ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->fetch('meta') ?>
    <link rel="stylesheet" href="/assets/css/<?= h($theme) ?>/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/vendor/prismjs/themes/prism-okaidia.css">
    <link rel="stylesheet" href="/assets/css/custom.min.css">
    <?= $this->fetch('css') ?>
  </head>
  <body>
    <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
      <div class="container">
        <a href="/rejack" class="navbar-brand"><?= __("ReJACK") ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" id="themes">Themes</a>
              <div class="dropdown-menu" aria-labelledby="themes">
                <a class="dropdown-item" href="/rejack/theme/index/default/">Default</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/rejack/theme/index/cerulean">Cerulean</a>
                <a class="dropdown-item" href="/rejack/theme/index/cosmo">Cosmo</a>
                <a class="dropdown-item" href="/rejack/theme/index/cyborg">Cyborg</a>
                <a class="dropdown-item" href="/rejack/theme/index/darkly">Darkly</a>
                <a class="dropdown-item" href="/rejack/theme/index/flatly">Flatly</a>
                <a class="dropdown-item" href="/rejack/theme/index/journal">Journal</a>
                <a class="dropdown-item" href="/rejack/theme/index/litera">Litera</a>
                <a class="dropdown-item" href="/rejack/theme/index/lumen">Lumen</a>
                <a class="dropdown-item" href="/rejack/theme/index/lux">Lux</a>
                <a class="dropdown-item" href="/rejack/theme/index/materia">Materia</a>
                <a class="dropdown-item" href="/rejack/theme/index/minty">Minty</a>
                <a class="dropdown-item" href="/rejack/theme/index/morph">Morph</a>
                <a class="dropdown-item" href="/rejack/theme/index/pulse">Pulse</a>
                <a class="dropdown-item" href="/rejack/theme/index/quartz">Quartz</a>
                <a class="dropdown-item" href="/rejack/theme/index/sandstone">Sandstone</a>
                <a class="dropdown-item" href="/rejack/theme/index/simplex">Simplex</a>
                <a class="dropdown-item" href="/rejack/theme/index/sketchy">Sketchy</a>
                <a class="dropdown-item" href="/rejack/theme/index/slate">Slate</a>
                <a class="dropdown-item" href="/rejack/theme/index/solar">Solar</a>
                <a class="dropdown-item" href="/rejack/theme/index/spacelab">Spacelab</a>
                <a class="dropdown-item" href="/rejack/theme/index/superhero">Superhero</a>
                <a class="dropdown-item" href="/rejack/theme/index/united">United</a>
                <a class="dropdown-item" href="/rejack/theme/index/vapor">Vapor</a>
                <a class="dropdown-item" href="/rejack/theme/index/yeti">Yeti</a>
                <a class="dropdown-item" href="/rejack/theme/index/zephyr">Zephyr</a>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav ms-md-auto">
            <li class="nav-item">
              <a target="_blank" rel="noopener" class="nav-link" href="https://github.com/applebiter/rejack-php/"><i class="bi bi-github"></i> GitHub</a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container">

      <?= $this->fetch('content') ?>

      <footer id="footer">
        <div class="row">
          <div class="col-lg-12">
            <ul class="list-unstyled">
              <li class="float-end"><a href="#top">Back to top</a></li>
              <li><a href="https://github.com/applebiter/rejack-php">GitHub</a></li>
            </ul>
            <p class="text-muted small">
                Made by <a href="https://applebiter.com/">Richard Lucas</a>  
                and released under the 
                <a href="https://opensource.org/licenses/MIT">MIT License</a>.
            </p>
          </div>
        </div>
      </footer>
    </div>

    <script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/prismjs/prism.js" data-manual></script>
    <script src="/assets/js/custom.js"></script>
    <?= $this->fetch('script') ?>
  </body>
</html>
