<?php
namespace Rejack;

use Rejack\Network\Firewall\FirewallConfig;
use Rejack\Network\Firewall;
use Rejack\Network\NetworkConfig;
use Rejack\Network\NetworkState;
use Rejack\Network\NetworkPortState;
use Rejack\Network\NetworkSocket;
use Rejack\Network\NetworkSocket\NetworkSocketConfig;

/**
 * class Network
 * This class manages the whole process of acquiring an available port, 
 * initializing an instance of JackTrip to receive a remote user, opening the 
 * firewall to the user for the port, and managing the state of ports and 
 * connections to clean up and close the firewall back up as needed.
 */
class Network
{
    /**
     * An array of error messages, if any.
     * @var string[]
     * @access private
     */
    private $errors;

    /**
     * This object encapsulates the ufw program used by Ubuntu and its variants 
     * very specifically, so I foresee a future refactoring here.
     * @var Firewall
     */
    private $firewall;

    /**
     * The outward-facing IP address of the ReJACK server.
     * @var string
     * @access private
     */
    private $local_ip_address;

    /**
     * An array of port numbers defined in the configuration that are the only 
     * ports available to the app.
     * @var int[] 
     * @access private
     */
    private $ports_to_use;

    /**
     * Contains the state information that can be stored between page loads.
     * @var NetworkState
     * @access private
     */
    private $state;

    /**
     * The path to a file in which a JSON string representing the Network 
     * component state may be stored.
     * @var string
     * @access private
     */
    private $statefile;

    /**
     * The name of the user under whom the apache2 server and any instances of 
     * JackTrip might run.
     * @var string 
     * @access public
     */
    public $username;


    /**
     * Class constructor
     * @param NetworkConfig config 
     * @return Network
     * @access public
     */
    public function __construct(NetworkConfig $config) 
    {
        $this->ports_to_use = $config->ports_to_use;
        $this->local_ip_address = utf8_encode($config->local_ip_address);
        $this->statefile = $config->statefile ? utf8_encode($config->statefile) : null;
        $fw_config = new FirewallConfig();
        $fw_config->local_ip_address = $this->local_ip_address;
        $this->firewall = new Firewall($fw_config);        
        $this->initialize();

        if ($this->statefile && !$this->verifyState()) 
        {
            $this->reset();
        }
    } 

    /**
     * Class destructor
     * @return void
     * @access public
     */
    public function __destruct() 
    {
        if ($this->statefile) 
        {
            $this->saveState();
        }
    } 

    /**
     * void addError ( string )
     *
     * Adds an error message to the list.
     * @param string message 
     * @return void
     * @access public
     */
    public function addError(string $message) : void
    {
        $this->errors = is_array($this->errors) ? $this->errors : [];
        $this->errors[] = $message;
    } 

    /**
     * void clearErrors ( )
     *
     * Resets the error attribute to null.
     * @return void
     * @access public
     */
    public function clearErrors() : void
    {
        $this->errors = null;
    } 

    /**
     * int|null getAvailablePortNumber ( )
     * 
     * Returns an available port number.
     * @return int|null 
     * @access private
     */
    private function getAvailablePortNumber() : int|null 
    {
        $diff = array_diff($this->ports_to_use, array_keys($this->state->ports));

        if (is_array($diff) && count($diff))
        {
            return intval(array_pop($diff));
        }

        return null;
    }

    /**
     * array|null getErrors ( )
     * 
     * Returns the array of error messages, if any.
     * @return string[]|null
     * @access public
     */
    public function getErrors() : array|null
    {
        return is_array($this->errors) ? $this->errors : null;
    }

    /**
     * bool hasAvailablePort ( )
     * 
     * If there is at least one used port, true is returned, otherwise false.
     * @return bool 
     * @access public 
     */
    public function hasAvailablePort() : bool 
    {
        $diff = array_diff($this->ports_to_use, array_keys($this->state->ports));
        return (is_array($diff) && count($diff));
    }

    /**
     * bool hasErrors ( )
     *
     * Returns true if the errors attribute is an array having at least one 
     * element in it.
     * @return bool
     * @access public
     */
    public function hasErrors() : bool
    {
        return is_array($this->errors) && count($this->errors) ? true : false;
    } 

    /**
     * void initialize ( )
     *
     * If a statefile is present then state is loaded.
     * @return void
     * @access private
     */
    private function initialize() : void
    {
        if ($this->statefile) 
        {
            $this->loadState();
        }
    }

    /**
     * int initSocket ( NetworkSocketConfig )
     *
     * Takes the JackTrip configuration and converts it into an executable 
     * string through the NetworkSocket object's toString() method, then runs 
     * the command on the system. If it was successful, then the process ID of 
     * the new JackTrip instance will be returned, otherwise 0 is returned.
     * @param NetworkSocketConfig config
     * @return int
     * @access private
     */
    private function initSocket(NetworkSocketConfig $config) : int
    {
        $cmd = (new NetworkSocket($config))->toString();
        return intval(shell_exec($cmd));
    }

    /**
     * void loadState ( )
     *
     * Reads the stored state value from the statefile into a new NetworkState 
     * object.
     * @return void
     * @access private
     */
    private function loadState() 
    {
        if ($this->statefile) 
        {
            $state = json_decode(file_get_contents($this->statefile));
            $this->state = new NetworkState();

            if ($state->ports)
            {
                foreach ($state->ports as $port)
                {
                    $this->state->ports[$port->port_number] = new NetworkPortState();
                    $this->state->ports[$port->port_number]->port_number = $port->port_number;
                    $this->state->ports[$port->port_number]->process_id = $port->process_id;
                    $this->state->ports[$port->port_number]->protocol = $port->protocol;
                    $this->state->ports[$port->port_number]->remote_ip_address = $port->remote_ip_address;
                }
            }
        }
    }

    /**
     * void occupyPort ( NetworkPortState )
     * 
     * Adds an instance of NetworkPortState to the state for storage.
     * @param NetworkPortState port
     * @return void
     * @access private
     */
    private function occupyPort(NetworkPortState $port) : void
    {
        $this->state->ports[$port->port_number] = $port;
    } 

    /**
     * void releasePort ( int )
     *
     * Removes the data associated with the specified port number from object 
     * state.
     * @param int port_number
     * @return void
     * @access private
     */
    private function releasePort(int $port_number) : void
    {
        if (isset($this->state->ports[$port_number]))
        {
            unset($this->state->ports[$port_number]);
        }
    }

    /**
     * 
     * @param NetworkSocketConfig $config 
     * @return bool 
     */
    public function reservePort(NetworkSocketConfig $config) : bool
    {
        if (!$this->hasAvailablePort())
        {
            $this->addError("There are no available ports at this time.");
            return false;
        }

        $port = new NetworkPortState();
        $port->port_number = $this->getAvailablePortNumber();
        $port->process_id = $this->initSocket($config);
        $port->remote_ip_address = $this->remote_ip_address;
        $this->occupyPort($port);
        return true;
    }

    /**
     * void reset ( )
     * 
     * Closes the firewall and sets the state's ports to null.
     * @return void 
     */
    private function reset() : void 
    {
        foreach ($this->state->ports as $key => $port) 
        {
            $this->firewall->closePort($port);
            $this->releasePort($key);
        }
    }

    /**
     * void saveState ( )
     *
     * Writes the JSON-encoded state into the statefile.
     * @param void
     * @return void
     * @access private
     */
    private function saveState() : void 
    {
        file_put_contents($this->statefile, json_encode($this->state));
    } 

    /**
     * 
     *
     * @return bool
     * @access private
     */
    private function verifyState() 
    {
        // use ps to get a list of jacktrip instances and matching pids
        // pgrep -u sysadmin jacktrip gets list of process ids  
        // lsof -i :{port number} gets details about the process on the port number
        // ps -p {port number} -o comm= returns the name of the process using the port
        // compare the state to the recovered data
        // return true if state is valid, false otherwise
    } 
}
