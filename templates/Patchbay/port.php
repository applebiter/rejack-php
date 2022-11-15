<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">Applebiter.com</a></li>
  <li class="breadcrumb-item"><a href="/rejack">ReJACK</a></li>
  <li class="breadcrumb-item"><a href="/rejack/patchbay"><?= __("Patchbay") ?></a></li>
  <li class="breadcrumb-item active">
    <?= h($port["client_name"]) ?>:<?= h($port["name"]) ?>
  </li>
</ol>
<div class="page-header" id="banner">
    <div class="row">
        <div class="col-12">
            <h1>
                <?php if ($status == 1 && !empty($port)) : ?>
                <?php $icon = $port["port_type"] ? "box-arrow-right" : "box-arrow-in-right" ?>
                <i class="bi bi-<?= $icon ?> me-1"></i> 
                <?= h($port["client_name"]) ?>:<?= h($port["name"]) ?>
                <?php else : ?>
                <i class="bi bi-dash-lg me-1"></i> 
                <?= __("Patchbay Offline") ?>
                <?php endif ?>
            </h1>
        </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
    </div>
</div>
<?php if ($status) : ?>
<div class="row gx-2">
    <div class="col-6">
        <div class="card my-2">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <span class="text-muted"><?= __("Port Type") ?>:</span> 
                    <?= $port["port_type"] ? __("Source") : __("Sink") ?>
                </li>
                <li class="list-group-item">
                    <span class="text-muted"><?= __("Properties") ?>:</span> 
                    <?= str_replace(",", ", ", h($port["properties"])) ?>
                </li>
                <?php if ($port["aliases"]) : ?>
                <li class="list-group-item">
                    <span class="text-muted"><?= __("Aliases") ?>:</span>
                    <?= h($port["aliases"]) ?>
                </li>
                <?php endif ?>
                <?php if ($port["content_type"]) : ?>
                <li class="list-group-item">
                    <span class="text-muted"><?= __("Content Type") ?>:</span> 
                    <?= h($port["content_type"]) ?>
                </li>
                <?php endif ?>
                <?php if ($port["port_playback_latency"]) : ?>
                <li class="list-group-item">
                    <span class="text-muted"><?= __("Playback Latency") ?>:</span> 
                    <?= h($port["port_playback_latency"]) ?> 
                    <?= __("frames") ?>
                </li>
                <?php endif ?>
                <?php if ($port["port_capture_latency"]) : ?>
                <li class="list-group-item">
                    <span class="text-muted"><?= __("Capture Latency") ?>:</span> 
                    <?= h($port["port_capture_latency"]) ?> 
                    <?= __("frames") ?>
                </li>
                <?php endif ?>
                <?php if ($port["total_latency"]) : ?>
                <li class="list-group-item">
                    <span class="text-muted"><?= __("Total Latency") ?>:</span> 
                    <?= h($port["total_latency"]) ?> 
                    <?= __("frames") ?>
                </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
    <div class="col-6">
        <div class="p-3">
            <h5>
                <?= __("Connected") ?> 
                <?= $port["port_type"] ? __("Sinks") : __("Sources") ?>:
            </h5>
            <?php if (is_array($port["connections"]) && count($port["connections"])) : ?>
            <ul style="list-style:none">
                <?php foreach ($port["connections"] as $connection) : ?>
                <li >
                    <a class="<?= $port["port_type"] ? "text-danger" : "text-warning" ?>" href="/rejack/patchbay/port/<?= urlencode("{$connection}") ?>">
                        <i class="bi bi-<?= $port["port_type"] ? "box-arrow-in-right" : "box-arrow-right" ?> me-2"></i> 
                        <?= h($connection) ?>
                    </a>
                </li>
                <?php endforeach ?>
            </ul>
            <?php else : ?>
            <?= __("None") ?> 
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <?= __("Connect/Disconnect") ?>
                    <?= $port["port_type"] ? __("Sinks") : __("Sources") ?>:
                </h5>
                <div class="row gx-1">
                    <?php $ports = $port["port_type"] ? $sinks : $sources ?>
                    <?php $colsize = (count($ports) % 2) == 0 ? count($ports) / 2 : (count($ports) / 2) + 1 ?>
                    <div class="col-6">
                    <?php foreach (array_values($ports) as $idx => $conn) : ?>
                        <?php if ($colsize == $idx) : ?>
                    </div>
                    <div class="col-6">
                        <?php endif ?>
                        <div class="form-check form-switch">
                            <?php $connection = "{$conn["client_name"]}:{$conn["name"]}" ?>
                            <?php $checked = is_array($port["connections"]) && in_array($connection, $port["connections"]) ? " checked" : null ?>
                            <input class="form-check-input" type="checkbox" id="<?= h($connection) ?>" value="1"<?= $checked ?>>
                            <label class="form-check-label" for="flexSwitchCheckDefault">
                                <?= h($connection) ?>
                            </label>
                        </div>
                    <?php endforeach ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>