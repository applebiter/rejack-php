<?php
namespace Rejack\Controller;

use Cake\Core\Configure;
use Cake\View\JsonView;
use Rejack\Controller\AppController;
use Rejack\Rejack;
use Rejack\Server;

/**
 * class ThemeController
 * Facilitates a theme change for the ReJACK server
 */
class ThemeController extends AppController
{
    public function index($theme = "default")
    {
        $themes = [
            "cerulean", "cosmo", "cyborg", "darkly", "default", "flatly", 
            "journal", "litera", "lumen", "lux", "materia", "minty", "morph", 
            "pulse", "quartz", "sandstone", "simplex", "sketchy", "slate",
            "solar", "spacelab", "superhero", "united", "vapor", "yeti", 
            "zephyr"
        ];

        if (in_array($theme, $themes))
        {
            file_put_contents(Configure::read("Rejack.Theme.themefile"), $theme);
        }

        $this->redirect($this->referer());
    }
}