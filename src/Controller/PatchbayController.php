<?php
namespace Rejack\Controller;

use Cake\Core\Configure;
use Cake\View\JsonView;
use InvalidArgumentException;
use Rejack\Controller\AppController;
use Rejack\Form\PatchbayConnectForm;
use Rejack\Form\PatchbayDisconnectForm;
use Rejack\Form\PatchbayDisconnectallForm;
use Rejack\Form\PatchbayExportForm;
use Rejack\Form\PatchbayImportForm;
use Rejack\Rejack;
use Rejack\Server;

/**
 * class ServerController
 * Implements the Rejack Server for HTTP requests.
 */
class PatchbayController extends AppController
{ 
    /**
     * Holds the initialized Rejack object, through which the Patchbay and other 
     * Components may be accessed.
     * @var Rejack\Rejack
     * @access private
     */
    private $rejack;

    /**
     * This method allows for return array data to be automagically be converted 
     * into JSON format if the request appends ".json" to the URI. That means 
     * the same controller methods are able to do double duty both serving up a 
     * front end and a back end, simultaneously.
     * @return array 
     * @access public
     */
    public function viewClasses(): array
    {
        return [JsonView::class];
    } 

    /**
     * Fetching the Rejack instance requires initialization of the Server object 
     * and subordinate objects
     * @return void 
     * @access public 
     */
    public function initialize() : void 
    {
        $this->rejack = Rejack::getRejack(Configure::read("Rejack.Server"));
        $this->rejack->getPatchbay(Configure::read("Rejack.Patchbay"));
    }

    /**
     * Provides the entire list of audio ports available on the JACK server.
     * @return void 
     * @access public
     */
    public function index()
    {
        if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
        {
            $this->set('status', SERVER::STATUS_ONLINE);
            $this->set('sinks', $this->rejack->getPatchbay()->getSinksAsArrays());
            $this->set('sources', $this->rejack->getPatchbay()->getSourcesAsArrays());
            $this->viewBuilder()->setOption('serialize', ['sinks', 'sources', 'status']);
        } 
        else 
        {
            $this->set('status', SERVER::STATUS_OFFLINE);
            $this->viewBuilder()->setOption('serialize', ['status']);
        }
    }

    /**
     * Connect the specified ports.
     * @return void 
     * @access public
     */
    public function connect()
    {
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
            {
                $form = new PatchbayConnectForm();
                $form->setPatchbay($this->rejack->getPatchbay());
                
                if (!$form->execute($this->request->getData()))
                {
                    $this->Flash->error(__('An error occurred. Please try again.'));
                    $errors = $this->rejack->getPatchbay()->getErrors();

                    if (is_array($errors) && count($errors)) 
                    {
                        foreach ($errors as $error) 
                        {
                            $this->Flash->error(__($error));
                        }
                    }
                }
            }
        }

        $this->redirect($this->referer());
    }

    /**
     * Disconnect the specified audio ports.
     * @return void 
     * @access public
     */
    public function disconnect()
    {
        if ($this->request->is(["patch", "post", "put"]))
        {
            if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
            {
                $form = new PatchbayDisconnectForm();
                $form->setPatchbay($this->rejack->getPatchbay());
                
                if (!$form->execute($this->request->getData()))
                {
                    $this->Flash->error(__("An error occurred. Please try again."));
                    $errors = $this->rejack->getPatchbay()->getErrors();

                    if (is_array($errors) && count($errors)) 
                    {
                        foreach ($errors as $error) 
                        {
                            $this->Flash->error(__($error));
                        }
                    }
                }
            }
        }

        $this->redirect($this->referer());
    }

    /**
     * Disconnect all audio ports.
     * @return void 
     * @access public
     */
    public function disconnectall()
    {
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
            {
                $form = new PatchbayDisconnectallForm();
                $form->setPatchbay($this->rejack->getPatchbay());
                
                if (!$form->execute($this->request->getData()))
                {
                    $this->Flash->error(__("An error occurred. Please try again."));
                    $errors = $this->rejack->getPatchbay()->getErrors();

                    if (is_array($errors) && count($errors)) 
                    {
                        foreach ($errors as $error) 
                        {
                            $this->Flash->error(__($error));
                        }
                    }
                }
            }
        }

        $this->redirect($this->referer());
    }

    /**
     * Export the current state of connections between audio ports to a named 
     * preset configuration.
     * @return void 
     * @access public
     */
    public function export() 
    {
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
            {
                $form = new PatchbayExportForm();
                $form->setPatchbay($this->rejack->getPatchbay());
                
                if (!$form->execute($this->request->getData()))
                {
                    $this->Flash->error(__("An error occurred. Please try again."));
                    $errors = $this->rejack->getPatchbay()->getErrors();

                    if (is_array($errors) && count($errors)) 
                    {
                        foreach ($errors as $error) 
                        {
                            $this->Flash->error(__($error));
                        }
                    }
                }
            }
        }

        $this->redirect($this->referer());
    }

    /**
     * Try to load a saved connection state and impose it onto the current set 
     * of audio ports.
     * @return void 
     * @access public
     */
    public function import() 
    {
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
            {
                $form = new PatchbayImportForm();
                $form->setPatchbay($this->rejack->getPatchbay());
                
                if (!$form->execute($this->request->getData()))
                {
                    $this->Flash->error(__("An error occurred. Please try again."));
                    $errors = $this->rejack->getPatchbay()->getErrors();

                    if (is_array($errors) && count($errors)) 
                    {
                        foreach ($errors as $error) 
                        {
                            $this->Flash->error(__($error));
                        }
                    }
                }
            }
        }

        $this->redirect($this->referer());
    }

    /**
     * Takes in a full <client:port> name and renders a view of all available 
     * port details. Also displays a field of checkboxes next to the names of 
     * all ports of the opposite type on the JACK bus. The checkboxes are ticked 
     * according to whether the respective ports are in fact already connected. 
     * Checking a box without AJAX support does nothing until the form is 
     * submitted. With AJAX support, there is no page reload, but rather the 
     * connect or disconnect call(s) are made in the background in real time.
     * @param mixed $portname 
     * @return void 
     * @access public
     */
    public function port($portname = null)
    {
        if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
        {
            $this->set('port', $this->rejack->getPatchbay()->getAudioPortAsArray(urldecode($portname)));
            $this->set('status', SERVER::STATUS_ONLINE);
            $this->set('sinks', $this->rejack->getPatchbay()->getSinksAsArrays());
            $this->set('sources', $this->rejack->getPatchbay()->getSourcesAsArrays());
        } 
        else 
        {
            $this->set("port", []);
            $this->set('status', SERVER::STATUS_OFFLINE);
            $this->set('sinks', []);
            $this->set('sources', []);
        }

        $this->set("errors", $this->rejack->getPatchbay()->getErrors() ? $this->rejack->getPatchbay()->getErrors() : []);
        $this->set("request_data", $this->request->getData());
        $this->viewBuilder()->setOption("serialize", [
            "errors", "request_data", "port", "sinks", "sources", "status"
        ]);
    }
}