<?php
namespace Rejack\Controller;

use Cake\Core\Configure;
use Cake\View\JsonView;
use Rejack\Controller\AppController;
use Rejack\Rejack;
use Rejack\Server;

/**
 * class ServerController
 * Implements the Rejack Server for HTTP requests.
 */
class ServerController extends AppController
{
    /**
     * Holds the initialized Rejack object, through which the Server and other 
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
    }
    
    /**
     * Renders the buffer size, aka bufsize aka period.
     * @return void 
     * @access public
     */
    public function buffersize()
    {
        if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
        {
            $this->set('buffersize', $this->rejack->getServer()->getBufferSize());
            $this->set('status', SERVER::STATUS_ONLINE);
            $this->viewBuilder()->setOption('serialize', ['buffersize', 'status']);
        }
        else 
        {
            $this->set('status', SERVER::STATUS_OFFLINE);
            $this->viewBuilder()->setOption('serialize', ['status']);
        }
    }

    /**
     * Takes as input the name of a client on the JACK server and renders a view 
     * of the specified client. 
     * @param string $name 
     * @return void 
     * @access public
     */
    public function client($name = null)
    {
        $url_decoded_name = urldecode($name);
        
        if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE && $name) 
        {
            $value = $this->rejack->getServer()->getClientByName($url_decoded_name) ? $this->rejack->getServer()->getClientByName($url_decoded_name)->toArray() : [];
            $this->set('client', $value);            
        }
        else 
        {
            $this->set('client', []);
        }
        
        $this->set('status', $this->rejack->getServer()->getStatus());
        $this->viewBuilder()->setOption('serialize', ['client', 'status']);
    }

    /**
     * Renders a view of the clients currently on the JACK server. 
     * @return void 
     * @access public
     */
    public function clients()
    {
        if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
        {
            $this->set('clients', $this->rejack->getServer()->getClientsAsArray());
            $this->set('status', $this->rejack->getServer()->getStatus());
            $this->viewBuilder()->setOption('serialize', ['clients', 'status']);
        }
    }

    /**
     * Renders an overview of the running JACK server and its clients. 
     * @return void 
     * @access public
     */
    public function index() 
    {
        if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
        {
            $this->set('status', SERVER::STATUS_ONLINE);
            $this->set('uptime', $this->rejack->getServer()->getUptime());
            $this->set('buffersize', $this->rejack->getServer()->getBufferSize());
            $this->set('samplerate', $this->rejack->getServer()->getRate());
            $this->set('numclients', $this->rejack->getServer()->getNumClients());
            $this->set('numports', $this->rejack->getServer()->getNumPorts());
            $this->set('clients', $this->rejack->getServer()->getClientsAsArray());
            $this->viewBuilder()->setOption('serialize', [
                'status', 'uptime', 'buffersize', 'samplerate', 'numclients', 
                'numports', 'clients'
            ]);
        } 
        else 
        {
            $this->set('status', SERVER::STATUS_OFFLINE);
            $this->viewBuilder()->setOption('serialize', ['status']);
        }
    }
    
    /**
     * Renders the JACK server sample rate. 
     * @return void 
     * @access public
     */
    public function samplerate()
    {
        if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
        {
            $this->set('status', SERVER::STATUS_ONLINE);
            $this->set('samplerate', $this->rejack->getServer()->getRate());
            $this->viewBuilder()->setOption('serialize', ['samplerate', 'status']);
        }
        else 
        {
            $this->set('status', SERVER::STATUS_OFFLINE);
            $this->viewBuilder()->setOption('serialize', ['status']);
        }
    }

    /**
     * Start the JACK server.
     * @return void 
     * @access public
     */
    public function start() 
    {
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($this->rejack->getServer()->getStatus() !== SERVER::STATUS_ONLINE) 
            {
                $this->rejack->getServer()->startJackServer();
                $this->redirect($this->referer());
            } 
        }

        $this->set('status', $this->rejack->getServer()->getStatus());
        $this->set('uptime', $this->rejack->getServer()->getUptime());
        $this->viewBuilder()->setOption('serialize', ['status', 'uptime']);
    }

    /**
     * Renders an integer representing the current status:
     *     0 = Offline
     *     1 = Online
     *     2 = Error (Only if using statefile)
     * @return void 
     * @access public
     */
    public function status() 
    {
        $this->set('status', $this->rejack->getServer()->getStatus());
        $this->set('uptime', $this->rejack->getServer()->getUptime());
        $this->viewBuilder()->setOption('serialize', ['status', 'uptime']);
    }

    /**
     * Stop the JACK server. 
     * @return void 
     * @access public 
     */
    public function stop()
    {
        if ($this->request->is(['patch', 'post', 'put']))
        {
            if ($this->rejack->getServer()->getStatus() === SERVER::STATUS_ONLINE) 
            {
                $this->rejack->getServer()->shutdown();
                usleep(500);
                $this->redirect($this->referer());
            }
        }

        $this->set('status', $this->rejack->getServer()->getStatus());
        $this->set('uptime', $this->rejack->getServer()->getUptime());
        $this->viewBuilder()->setOption('serialize', ['status', 'uptime']);
    }

    /**
     * This method uses "server events", a one-way, persistent HTTP(S) 
     * connection that sends a stream of events. The event stream is throttled 
     * to one event loop every thirty seconds by default, but an optional 
     * integer between 1 and 59 inclusive can be passed to the method to specify 
     * how many seconds to wait between refreshes.
     * @param int $interval_seconds
     * @return void 
     * @access public
     */
    public function uptime(int $interval_seconds = 30) 
    {
        if ($this->rejack->getServer()->getStatus() === Server::STATUS_ONLINE) 
        {
            error_reporting(E_ERROR);        
            header("Cache-Control: no-store");
            header("Content-Type: text/event-stream");

            $interval = ($interval_seconds > 0 && $interval_seconds < 60) ? $interval_seconds : 30;

            while (true) 
            {
                echo "event: uptime\n";
                $uptime = $this->rejack->getServer()->getUptime();
                echo "data: {\"uptime\": \"{$uptime['uptime']}\", \"months\": \"{$uptime['months']}\", \"days\": \"{$uptime['days']}\", \"hours\": \"{$uptime['hours']}\", \"minutes\": \"{$uptime['minutes']}\", \"seconds\": \"{$uptime['seconds']}\"}";
                echo "\n\n";
                ob_end_flush();
                flush();

                if (connection_aborted()) 
                {
                    break;
                }

                sleep($interval);
            }
        }
    }
}
