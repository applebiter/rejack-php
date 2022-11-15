<?php
namespace Rejack;

use Rejack\Network;
use Rejack\Network\NetworkConfig;
use Rejack\Patchbay;
use Rejack\Patchbay\PatchbayConfig;
use Rejack\Server;
use Rejack\Server\ServerConfig;
use Rejack\Server\JackServer\JackServerConfig;
use Rejack\Server\JackServer\Backend\JackBackendConfig;

class Rejack 
{
    
    /**
     * Contains an initialized instance of Rejack\Rejack
     * @var Rejack\Rejack
     * @access private
     * @static
     */
    private static $instance;

    /**
     * Contains an initialized instance of Rejack\Server
     * @var Rejack\Server
     */
    private $server;

    /**
     * Contains an instance of Rejack\Patchbay
     * @var Patchbay
     * @access private
     */
    private $patchbay;

    /**
     * Contains an instance of the Rejack\Network
     * @var Network
     * @access private
     */
    private $network;


    private function __construct()  {} 
    final public function __clone() {}

    /**
     * Initializes the Server object from a nested config array. 
     * 
     * @param array $server_config_arr 
     * @return void 
     * @access private
     */
    private function initialize(array $server_config_arr) 
    {
        $backend_config_arr = $server_config_arr["jack_config"]["backend"];
        $backend_config = new JackBackendConfig();
        $jack_config_arr = $server_config_arr["jack_config"];
        $jack_config = new JackServerConfig();
        $jack_config->backend = $backend_config;
        $server_config = new ServerConfig();

        foreach ($backend_config_arr as $key => $val) 
        {
            $backend_config->$key = $val;
        }

        foreach ($jack_config_arr as $key => $val) 
        {
            if ($key == "backend") 
            {
                continue;
            } 
            else 
            {
                $jack_config->$key = $val;
            }
        }

        foreach ($server_config_arr as $key => $val) 
        {
            if ($key == "jack_config") 
            {
                $server_config->$key = $jack_config;
            } 
            else 
            {
                $server_config->$key = $val;
            }
        }

        $this->server = new Server($server_config);
    }

    /**
     * Takes a configuration array as input and returns the initialized Rejack.
     * 
     * @param array $server_config_arr 
     * @return Rejack\Rejack
     * @access public
     * @static
     */
    public static function getRejack(array $server_config_arr) : Rejack
    {
        if (null === self::$instance) 
        {            
            self::$instance = new self;
            
            self::$instance->initialize($server_config_arr);
        }
        
        return self::$instance;
    }

    /**
     * Returns the Server object.
     * 
     * @return Server 
     * @access public
     */
    public function getServer() 
    {
        return $this->server;
    }

    /**
     * Returns the Patchbay object. 
     * @param string $presets_path
     * @return Patchbay 
     * @access public
     */
    public function getPatchbay(array $patchbay_config_arr = []) : Patchbay 
    {
        if (!$this->patchbay && !empty($patchbay_config_arr))
        {
            $config = new PatchbayConfig();
            
            foreach ($this->server->getClients() as $client)
            {
                foreach ($client->getPorts() as $port)
                {
                    $config->ports[$port->toString()] = $port;
                }
            }

            $config->presets_path = $patchbay_config_arr["presets_dir"];
            $config->jack_connect_path = $patchbay_config_arr["jack_connect_path"];
            $config->jack_disconnect_path = $patchbay_config_arr["jack_disconnect_path"];
            $this->patchbay = new Patchbay($config);
        }
        
        return $this->patchbay;
    }

    /**
     * Network getNetwork ( array )
     * 
     * Returns the Network object instance.
     * @param array $network_config_arr 
     * @return Network 
     */
    public function getNetwork(array $network_config_arr = []) : Network 
    {
        if (!$this->network && !empty($network_config_arr)) 
        {
            $config = new NetworkConfig();
            $config->local_ip_address = $network_config_arr["local_ip_address"];
            $config->ports_to_use = $network_config_arr["ports_to_use"];
            $config->statefile = $network_config_arr["statefile"];
        }
        
        return $this->network;
    }
}