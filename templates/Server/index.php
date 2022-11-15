<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/">Applebiter.com</a></li>
  <li class="breadcrumb-item"><a href="#">ReJACK</a></li>
  <li class="breadcrumb-item"><a href="#"><?= __("Server") ?></a></li>
  <li class="breadcrumb-item active"><?= __("Dashboard") ?></li>
</ol>
<div class="page-header" id="banner">
    <div class="row">
        <div class="col-lg-8 col-md-7 col-sm-6">
            <h1>
                <i class="bi bi-<?= $status == 1 ? "activity text-success" : "dash text-danger" ?> me-1"></i> 
                <?= __("ReJACK") ?>                
            </h1>
            <p class="lead">
                <?= $status == 1 ? __("ONLINE : ") : __("OFFLINE") ?> 
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
<div class="row">
    <div class="col-12 col-lg-8 col-xl-6">
        <?php if ($status == 1) : ?>
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-subtitle text-muted">
                    <b><span class="text-warning"><?= __("PLEASE NOTE") ?></span></b> 
                    <?= __("that your local JACK sample rate and buffer size " . 
                           "(aka \"period\") settings MUST match the values " . 
                           "shown below in order to join the remote session.") ?>
                </h5>
                </h6>                
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <?= __("Sample Rate") ?>: 
                    <?= h($samplerate) ?>Hz
                </li>
                <li class="list-group-item">
                    <?= __("Buffer Size") ?>: 
                    <?= h($buffersize) ?>
                </li>
            </ul>
            <div class="card-footer text-muted d-flex justify-content-between">
                <span>
                    <?= __("Number of clients") ?>: 
                    <?= h($numclients) ?> 
                    <a class="small ms-2" href="/rejack/server/clients">
                        <?= __("Clients") ?> 
                        <i class="bi bi-people-fill ms-1"></i>
                    </a> 
                </span>
                <span>
                    <?= __("Total number of audio ports") ?>: 
                    <?= h($numports) ?>
                    <a class="small ms-2" href="/rejack/patchbay">
                        <?= __("Patchbay") ?> 
                        <i class="bi bi-usb-symbol ms-1"></i>
                    </a>
                </span>
            </div>
        </div>
        <h4 class="card-title text-muted mb-2">
            <i class="bi bi-diagram-3-fill me-1"></i>
            <?= __("Connect to the ReJACK Server") ?>
        </h3>
        <form class="row mb-3 gx-2 gy-2 align-items-center">
            <div class="col-4 mb-2">
                <label class="visually-hidden" for="client_name">
                    <?= __("Client Name") ?>
                </label>
                <input type="text" class="form-control" id="client_name" 
                        placeholder="<?= __("Your name") ?>">
            </div>
            <div class="col-4 mb-2">
                <label class="visually-hidden" for="num_outputs">
                    <?= __("Channels to Server") ?>
                </label>
                <select class="form-select" id="num_outputs">
                    <option value="2"><?= __("# channels to send") ?>
                    <option value="1"> 1 </option>
                    <option value="2"> 2 (<?= __("default") ?>)</option>
                    <option value="3"> 3 </option>
                    <option value="4"> 4 </option>
                    <option value="5"> 5 </option>
                </select>
            </div>
            <div class="col-4 mb-2">
                <label class="visually-hidden" for="num_inputs">
                    <?= __("Channels from Server") ?>
                </label>
                <select class="form-select" id="num_inputs">
                    <option value="2"><?= __("# channels to receive") ?></option>
                    <option value="1"> 1 </option>
                    <option value="2"> 2 (<?= __("default") ?>)</option>
                    <option value="3"> 3 </option>
                    <option value="4"> 4 </option>
                    <option value="5"> 5 </option>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="button">
                    <i class="bi bi-usb-plug-fill me-1"></i>     
                    <?= __("Click Here to Plug In") ?>
                </button>
            </div>
        </form>
        <?php else : ?>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-muted mb-2">
                    <i class="bi bi-diagram-3-fill me-1"></i>
                    <?= __("Start the ReJACK Server") ?>
                </h3>
                <form class="row gy-2 gx-2 align-items-center">
                    <div class="col-6">
                        <select class="form-select" id="autoSizingSelect">
                            <option value="48000"><?= __("Choose sample rate (default 48000Hz)") ?></option>
                            <option value="44100">44100Hz</option>
                            <option value="48000">48000Hz</option>
                            <option value="88200">88200Hz</option>
                            <option value="96000">96000Hz</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <select class="form-select" id="autoSizingSelect">
                            <option value="256"><?= __("Choose buffer size (default 256)") ?></option>
                            <option value="128">128</option>
                            <option value="256">256</option>
                            <option value="512">512</option>
                            <option value="1024">1024</option>
                        </select>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-power me-1"></i> 
                            <?= __("Start the ReJACK Server") ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>