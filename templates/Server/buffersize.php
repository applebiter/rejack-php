<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">Applebiter.com</a></li>
  <li class="breadcrumb-item"><a href="#">ReJACK</a></li>
  <li class="breadcrumb-item"><a href="/rejack/server"><?= __("Server") ?></a></li>
  <li class="breadcrumb-item active"><?= __("Buffer Size") ?></li>
</ol>
<div class="page-header" id="banner">
    <div class="row">
        <div class="col-lg-8 col-md-7 col-sm-6">
            <h1>
                <?= !isset($buffersize) ? __("OFFLINE") : h($buffersize) ?>
            </h1>
            <p class="lead">
                <?php if (isset($buffersize)) : ?>
                <?= __("The current buffer size on the running JACK server is") ?> 
                <?= h($buffersize) ?>
                <?php else : ?>
                <?= __("The JACK server is not running") ?>
                <?php endif ?>
            </p>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-6"> </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
    </div>
</div>