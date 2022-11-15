<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">Applebiter.com</a></li>
  <li class="breadcrumb-item"><a href="#">ReJACK</a></li>
  <li class="breadcrumb-item"><a href="/rejack/server"><?= __("Server") ?></a></li>
  <li class="breadcrumb-item"><a href="/rejack/server/clients"><?= __("Clients") ?></a></li>
  <li class="breadcrumb-item active"><?= h($client["name"]) ?></li>
</ol>
<div class="page-header" id="banner">
    <div class="row">
        <div class="col-lg-8 col-md-7 col-sm-6">
            <h1>
                <i class="bi bi-person-fill me-1"></i> 
                <?= h($client["name"]) ?>
            </h1>
            <p class="lead">
                <?= __("A client on the JACK bus") ?> 
                <i class="bi bi-diagram-3-fill ms-1"></i>
            </p>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-6"> </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card border-secondary my-2">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-box-arrow-right me-1"></i> 
                    <?= __("Source Ports") ?>
                </h5>
                <h6 class="card-subtitle text-muted">
                    <?= __("Source ports bring signal input into the server from the client") ?>
                </h6>
                <?php if (is_array($client["ports"])) : ?>
                <div class="row">
                    <?php foreach ($client["ports"] as $idx => $port) : ?>
                    <?php if ($port["port_type"] == 1) : ?>
                    <div class="col-lg-6">
                        <div class="card text-light bg-primary my-2">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-caret-right-fill me-1"></i> 
                                    <?= h($port["name"]) ?>
                                </h5>
                                <ul>
                                    <?php if ($port["properties"]) : ?>
                                    <li><?= h(str_replace(",", ", ", $port["properties"])) ?></li>
                                    <?php endif ?>
                                    <?php if ($port["content_type"]) : ?>
                                    <li><?= h($port["content_type"]) ?></li>
                                    <?php endif ?>
                                </ul>
                                <a class="card-text text-light small" href="/rejack/patchbay/port/<?= urlencode($port["client_name"].":".$port["name"]) ?>">
                                    <?= __("View Connections") ?>  
                                    <i class="bi bi-search ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif ?>
                    <?php endforeach ?>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-secondary my-2">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-box-arrow-in-right me-1"></i> 
                    <?= __("Sink Ports") ?>
                </h5>
                <h6 class="card-subtitle text-muted">
                    <?= __("Sink ports listen for signal output from the source ports of other clients") ?>
                </h6>
                <?php if (is_array($client["ports"])) : ?>
                <div class="row">
                    <?php foreach ($client["ports"] as $port) : ?>
                    <?php if ($port["port_type"] == 0) : ?>
                    <div class="col-lg-6">
                        <div class="card text-dark bg-light my-2">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-caret-right me-1"></i> 
                                    <?= h($port["name"]) ?>
                                </h5>
                                <ul>
                                    <?php if ($port["properties"]) : ?>
                                    <li><?= h(str_replace(",", ", ", $port["properties"])) ?></li>
                                    <?php endif ?>
                                    <?php if ($port["content_type"]) : ?>
                                    <li><?= h($port["content_type"]) ?></li>
                                    <?php endif ?>
                                </ul>
                                <a class="card-text small text-dark" href="/rejack/patchbay/port/<?= urlencode($port["client_name"].":".$port["name"]) ?>">
                                    <?= __("View Connections") ?>  
                                    <i class="bi bi-search ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif ?>
                    <?php endforeach ?>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>