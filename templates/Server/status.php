<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">Applebiter.com</a></li>
  <li class="breadcrumb-item"><a href="#">ReJACK</a></li>
  <li class="breadcrumb-item"><a href="/rejack/server"><?= __("Server") ?></a></li>
  <li class="breadcrumb-item active"><?= __("Status") ?></li>
</ol>
<div class="page-header" id="banner">
    <div class="row">
        <div class="col-lg-8 col-md-7 col-sm-6">
            <h1>
                <?php if ($status == 1) : ?>
                <i class="bi bi-activity me-1 text-success"></i><?= __("ONLINE") ?>
                <?php else : ?>
                <i class="bi bi-dash-lg me-1 text-danger"></i><?= __("OFFLINE") ?>
                <?php endif ?>
            </h1>
            <p class="lead">
                <?php if (isset($uptime) && !empty($uptime)) : ?>
                <?php if ($uptime["days"]) : ?>
                <?= strval($uptime["days"]) ?> <?= $uptime["days"] == 1 ? __("day") : __("days") ?>,
                <?php endif ?>
                <?php if ($uptime["hours"]) : ?>
                <?= strval($uptime["hours"]) ?> <?= $uptime["hours"] == 1 ? __("hour") : __("hours") ?>, 
                <?php endif ?>
                <?= strval($uptime["minutes"]) ?> <?= $uptime["minutes"] == 1 ? __("minute") : __("minutes") ?>
                <i class="bi bi-clock-history ms-1"></i> 
                <?php else : ?>
                <?= __("The ReJACK server is not running") ?>
                <?php endif ?>
            </p>
        </div>
        <div class="col-lg-4 col-md-5 col-sm-6"> </div>
        <div class="col-12">
            <?= $this->Flash->render() ?>
        </div>
    </div>
</div>