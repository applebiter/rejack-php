<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">Applebiter.com</a></li>
  <li class="breadcrumb-item"><a href="#">ReJACK</a></li>
  <li class="breadcrumb-item"><a href="/rejack/server"><?= __("Server") ?></a></li>
  <li class="breadcrumb-item active"><?= __("Clients") ?></li>
</ol>
<div class="page-header" id="banner">
    <div class="row">
        <div class="col-lg-8 col-md-7 col-sm-6">
            <h1>
                <i class="bi bi-people-fill me-1"></i> 
                <?= __("ReJACK Clients") ?>
            </h1>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-6"> </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($clients as $client) : ?>
    <div class="col-auto m-2 g-1">
        <div class="card border-secondary">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="bi bi-person-fill me-1"></i>    
                    <?= h($client["name"]) ?>
                </h4>
                <p class="card-text small">
                    <a href="/rejack/server/client/<?= urlencode($client["name"]) ?>">
                        <?= __("View Details") ?> 
                        <i class="bi bi-search ms-1"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>