<?php
namespace Rejack\Controller;

use App\Controller\AppController as BaseController;
use Cake\Core\Configure;

class AppController extends BaseController 
{ 
    public function initialize(): void
    {
        parent::initialize();
        Configure::load("rejack-php.rejack", "default");
    }
}
