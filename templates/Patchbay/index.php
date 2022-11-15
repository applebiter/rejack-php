<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">Applebiter.com</a></li>
  <li class="breadcrumb-item"><a href="/rejack">ReJACK</a></li>
  <li class="breadcrumb-item"><a href="#"><?= __("Patchbay") ?></a></li>
  <li class="breadcrumb-item active"><?= __("Dashboard") ?></li>
</ol>
<div class="page-header" id="banner">
    <div class="row">
        <div class="col-lg-8 col-md-7 col-sm-6">
            <h1>
                <i class="bi bi-diagram-3-fill me-1"></i> 
                <?= __("Patchbay") ?>
            </h1>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-6"> </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
    </div>
</div>
<?php if ($status) : ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-muted mb-2">
                    <i class="bi bi-puzzle me-1"></i>
                    <?= __("Load a Connection Scheme") ?>
                </h3>
                <form class="row gy-2 gx-2 align-items-center">
                    <div class="col-6">
                        <select class="form-select" id="autoSizingSelect">
                            <option><?= __("There are no presets") ?></option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-puzzle-fill me-1"></i> 
                            <?= __("Load Preset") ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row gx-2">
    <div class="col-6">
        <div class="card my-2">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-box-arrow-right me-1"></i> 
                    <?= __("Source Ports") ?>
                </h5>
                <h6 class="card-subtitle text-muted">
                    <?= __("Source ports bring signal input into the server from the client") ?>
                </h6>
                <?php if (is_array($sources) && count($sources)) : ?>
                <div class="row">
                    <?php foreach ($sources as $idx => $port) : ?>
                    <?php if ($port["port_type"] == 1) : ?>
                    <div class="col-12">
                        <div class="card bg-primary my-2">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="text-warning bi bi-caret-right-fill me-1"></i> 
                                    <a class="text-warning" href="/rejack/patchbay/port/<?= urlencode("{$port["client_name"]}:{$port["name"]}") ?>">
                                        <?= h($port["client_name"]) ?>:<?= h($port["name"]) ?>
                                    </a>
                                </h5>
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
        <div class="card my-2">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-box-arrow-in-right me-1"></i> 
                    <?= __("Sink Ports") ?>
                </h5>
                <h6 class="card-subtitle text-muted">
                    <?= __("Sink ports listen for signal output from the source ports of other clients") ?>
                </h6>
                <?php if (is_array($sinks) && count($sinks)) : ?>
                <div class="row">
                    <?php foreach ($sinks as $port) : ?>
                    <?php if ($port["port_type"] == 0) : ?>
                    <div class="col-12">
                        <div class="card border-secondary my-2">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="text-danger bi bi-caret-right me-1"></i> 
                                    <a class="text-danger" href="/rejack/patchbay/port/<?= urlencode("{$port["client_name"]}:{$port["name"]}") ?>">
                                        <?= h($port["client_name"]) ?>:<?= h($port["name"]) ?>
                                    </a>
                                </h5>
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-muted mb-2">
                    <i class="bi bi-puzzle me-1"></i>
                    <?= __("Save Current Connection Scheme") ?>
                </h3>
                <form class="row gy-2 gx-2 align-items-center">
                    <div class="col-6">
                        <label class="visually-hidden" for="preset_name">
                            <?= __("Preset Name") ?>
                        </label>
                        <input type="text" class="form-control" id="preset_name" 
                                placeholder="<?= __("Save preset as...") ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-puzzle-fill me-1"></i> 
                            <?= __("Save Preset") ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>