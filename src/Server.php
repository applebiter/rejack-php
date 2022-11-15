<?php
namespace Rejack;

use Rejack\Server\ServerConfig;
use Rejack\Server\AudioPort;
use Rejack\Server\AudioPort\AudioPortConfig;
use Rejack\Server\Client;
use Rejack\Server\Client\ClientConfig;
use Rejack\Server\JackServer;
use Rejack\Server\JackServer\JackServerConfig;


/**
 * class Server
 * Class Server is the controller within the Server component. It manages the
 * JackServer and Client models at the core of the Rejack ecosystem.
 */
class Server
{

    const STATUS_OFFLINE = 0;
    const STATUS_ONLINE = 1;
    const STATUS_ERROR = 2;
    
    /**
     * Server loads and formats clients from the running JACK server during the
     * initialization() method. The initialized Clients are then available to 
     * the rest of the Rejack ecosystem for the duration of this object's 
     * lifecycle. However, the clients are not stored with Server state data.
     * @var Client[]
     * @access private
     */
    private $clients;

    /**
     * This parameter is optional. If present, it represents the path to a file 
     * where console output can be redirected when starting the JACK server 
     * process.
     * @var string
     * @access private
     */
    private $console_log;

    /**
     * If an error is encountered, a message is generated and stored in this 
     * array of error messages for later retrieval.
     * @var string[]
     * @access private
     */
    private $errors;

    /**
     * The path to the console program jack_bufsize.
     * @var string
     * @access private
     */
    private $jack_bufsize_path;

    /**
     * The path to the console program jack_lsp. 
     * @access private
     * @var string
     */
    private $jack_lsp_path;

    /**
     * The path to the console program jack_samplerate.
     * @var string
     */
    private $jack_samplerate_path;

    /**
     * Holds the JackServerConfig object passed to constructor.
     * @var JackServerConfig
     */
    private $jack_config;

    /**
     * This is the process ID of the running JACK server.
     * @var int
     * @access private
     */
    private $process_id;

    /**
     * The path to a file where the process ID of the running JACK server can be
     * stored. When the JACK server is stopped, the process ID and client data 
     * is deleted from the file.
     * @var string
     * @access private
     */
    private $statefile;

    /**
     * During class initialization, the state is loaded then verified against 
     * the current system. If the state and current conditions are in harmony, 
     * then the server status is either offline or online, depending on whether 
     * or not the JACK server itself is running. If the stored state and the 
     * current conditions are in conflict, then the server status is error.
     * @var int
     * @access private
     */
    private $status;


    /**
     * 
     *
     * @param ServerConfig config
     * @return Server
     * @access public
     */
    public function __construct(ServerConfig $config) 
    {
        $this->console_log = $config->console_log ? utf8_encode($config->console_log) : null;
        $this->jack_bufsize_path =  $config->jack_bufsize_path ? utf8_encode($config->jack_bufsize_path) : null;
        $this->jack_config = $config->jack_config ? $config->jack_config : null;
        $this->jack_lsp_path = $config->jack_lsp_path ? utf8_encode($config->jack_lsp_path) : null;
        $this->jack_samplerate_path = $config->jack_samplerate_path ? utf8_encode($config->jack_samplerate_path) : null;
        $this->statefile = $config->statefile ? utf8_encode($config->statefile) : null;
        $this->initialize();
    } 

    /**
     * Save state within the class destructor.
     *
     * @return void
     * @access public
     */
    public function __destruct() 
    {
        $this->saveState();
    } 

    /**
     * void addError ( string )
     * 
     * Insert an error message into the JackServerManager errors array.
     *
     * @param string message
     * @return void
     * @access public
     */
    public function addError(string $message) : void
    {
        $this->errors[] = $message;
    } 

    /**
     * void clearErrors ( )
     * 
     * Unsets all elements of the errors array, if any.
     *
     * @return void
     * @access public
     */
    public function clearErrors() : void
    {
        $this->errors = [];
    } 

    /**
     * AudioPort getAudioPortByName ( string )
     * 
     * Searches clients for an audio port having the specified name.
     *
     * @param string name 
     * @return AudioPort
     * @access public
     */
    public function getAudioPortByName(string $name) : AudioPort
    {
        foreach ($this->clients as $client)
        {
            if ($port = $client->getPortByName($name))
            {
                return $port;
            }
        }
    } 

    /**
     * int getBufferSize ( )
     * 
     * Returns the current buffer size (aka period) of the JACK audio bus.
     *
     * @return int
     * @access public
     */
    public function getBufferSize() : int
    {
        return intval(shell_exec($this->jack_bufsize_path));
    } 

    /**
     * Client[] getClients ( )
     * 
     * Returns all currently connected JACK clients in an array.
     *
     * @return Client[]
     * @access public
     */
    public function getClients() : array
    {
        return $this->clients;
    } 

    /**
     * Returns all clients in array form. 
     * 
     * @return string|null
     * @access public
     */
    public function getClientsAsArray() : array
    {
        $arr = [];

        if ($this->clients)
        {
            foreach ($this->clients as $client) 
            {
                $arr[] = $client->toArray();
            }
        }

        return $arr;
    } 

    /**
     * Client getClientByName ( string )
     * 
     * Returns any Client in the clients array having the specified name.
     *
     * @param string name 
     * @return Client|null
     * @access public
     */
    public function getClientByName(string $name) : Client|null
    {
        if (is_array($this->clients) && count($this->clients)) 
        {
            foreach ($this->clients as $client) 
            {
                if ($client->getName() == $name)
                {
                    return $client;
                }
            }
        }

        return null;
    } 

    /**
     * Returns a specified client as an array. 
     * 
     * @param string $name 
     * @return array|null 
     * @access public
     */
    public function getClientByNameAsArray(string $name) : array|null
    {
        if (is_array($this->clients) && count($this->clients)) 
        {
            foreach ($this->clients as $client) 
            {
                if ($client->getName() == $name)
                {
                    return $client->toArray();
                }
            }
        }

        return null;
    } 

    /**
     * string[] getErrors ( )
     * 
     * Returns the error messages, if any.
     *
     * @return string[]
     * @access public
     */
    public function getErrors() : array 
    {
        return $this->errors ? $this->errors : [];
    } 

    /**
     * string getName ( )
     * 
     * Returns the JACK server name, if any.
     *
     * @return string
     * @access public
     */
    public function getName() : ?string 
    {
        return isset($this->jack_config->name) ? $this->jack_config->name : null;
    } 

    /**
     * int getNumClients ( )
     * 
     * Returns the number of JACK clients connected to the server.
     *
     * @return int
     * @access public
     */
    public function getNumClients() : int 
    {
        return count($this->clients);
    } 

    /**
     * int getNumPorts ( )
     * 
     * Returns the total number of audio ports connected to the JACK server.
     *
     * @return int
     * @access public
     */
    public function getNumPorts() : int 
    {
        $cnt = 0;

        foreach ($this->clients as $client)
        {
            $cnt += count($client->getPorts());
        }

        return $cnt;
    } 

    /**
     * int getRate ( )
     * 
     * Returns the sample rate in Hz on the JACK audio bus.
     *
     * @return int
     * @access public
     */
    public function getRate() : int 
    {
        return intval(shell_exec($this->jack_samplerate_path));
    } 

    /**
     * ServerStatus getStatus ( )
     * 
     * Returns the status of the Server.
     *
     * @return int
     * @access public
     */
    public function getStatus() : int 
    {
        return $this->status;
    } 

    /**
     * Returns the uptime of the JACK server in an array with indexes referring 
     * to months, days, hours, minutes, seconds.
     * 
     * @return array 
     * @access public
     */
    public function getUptime() : array
    {
        if ($this->status == self::STATUS_ONLINE && $this->process_id) 
        {
            $raw = trim(shell_exec("ps -o etimes -p {$this->process_id}"));
            $raw_arr = explode("\n", $raw);
            $ss = intval($raw_arr[1]);
            $s = $ss%60;
            $m = floor(($ss%3600)/60);
            $h = floor(($ss%86400)/3600);
            $d = floor(($ss%2592000)/86400);
            $M = floor($ss/2592000);
            return [
                "uptime" => "{$M} months, {$d} days, {$h} hours, {$m} minutes, {$s} seconds",
                "months" => "{$M}",
                "days" => "{$d}",
                "hours" => "{$h}",
                "minutes" => "{$m}",
                "seconds" => "{$s}"
            ];
        }

        return "JACK is offline";
    }

    /**
     * bool hasErrors ( )
     * 
     * Returns true if the errors array contains one or more elements, false 
     * otherwise.
     *
     * @return bool
     * @access public
     */
    public function hasErrors() : bool 
    {
        return count($this->errors) ? true : false;
    } 

    /**
     * void initialize ( )
     * 
     * Initialize is a private method that runs inside of the class constructor 
     * to load state, verify state, determine Server status and generate an 
     * error message, if appropriate. Finally, if the JACK server is running, go
     * ahead and gather up the client and port data.
     *
     * @return void
     * @access private
     */
    private function initialize() : void 
    {
        $pid = intval(shell_exec("pidof jackd"));

        if ($pid < 1)
        {
            $pid = intval(shell_exec("pidof jackdbus"));
        }

        $this->status = $pid ? self::STATUS_ONLINE : self::STATUS_OFFLINE;
        
        if ($this->statefile)
        {
            $this->loadState();

            if (!$this->verifyState($pid))
            {
                $this->addError("Server state: Invalid state detected");
                $this->status = self::STATUS_ERROR;
            }
        }

        if ($pid)
        {
            $this->process_id = $pid;
            $this->refreshClients();
        }
    } 

    /**
     * void loadState ( )
     * 
     * Reads the state file for the presence of a process ID.
     *
     * @return void
     * @access private
     */
    private function loadState() : void 
    {
        if ($this->statefile)
        {
            $this->process_id = intval(file_get_contents($this->statefile));
        }
    } 

    /**
     * Loads the client information from jack_lsp.
     *
     * @return void
     * @access public
     */
    public function refreshClients() : void 
    {
        $lsp = trim(shell_exec($this->jack_lsp_path));
        $raw_ports_arr = explode("\n", $lsp);
        $ports_arr = [];

        if (count($raw_ports_arr)) 
        {
            unset($lsp);
            unset($raw_ports_arr);
            $aliases = trim(shell_exec("{$this->jack_lsp_path} -A "));
            $raw_aliases_arr = explode("\n", $aliases);
            $current = null;

            foreach ($raw_aliases_arr as $line) // extract port names and aliases
            {
                if (empty($line)) 
                {
                    continue;
                }

                if (strpos($line, "   ") !== 0) 
                {
                    $current = trim($line);
                } 
                else 
                {
                    $ports_arr[$current]["aliases"] = trim($line);
                }
            }

            unset($aliases);
            unset($raw_aliases_arr);
            $connections = trim(shell_exec("{$this->jack_lsp_path} -c "));
            $raw_connections_arr = explode("\n", $connections);

            foreach ($raw_connections_arr as $line) // extract connections
            {
                if (empty($line)) 
                {
                    continue;
                }

                if (strpos($line, "   ") !== 0) 
                {
                    $current = trim($line);
                } 
                else 
                {
                    $ports_arr[$current]["connections"][] = trim($line);
                }
            }

            unset($connections);
            unset($raw_connections_arr);
            $content_type = trim(shell_exec("{$this->jack_lsp_path} -t "));
            $raw_content_type_arr = explode("\n", $content_type);

            foreach ($raw_content_type_arr as $line) // extract content_type
            {
                if (empty($line)) 
                {
                    continue;
                }

                if (strpos($line, " bit ") == false) 
                {
                    $current = trim($line);
                } 
                else 
                {
                    $ports_arr[$current]["content_type"] = trim($line);
                }
            }

            unset($content_type);
            unset($raw_content_type_arr);
            $port_latency = trim(shell_exec("{$this->jack_lsp_path} -l "));
            $raw_port_latency_arr = explode("\n", $port_latency);

            foreach ($raw_port_latency_arr as $line) // extract port latency
            {
                if (empty($line)) 
                {
                    continue;
                }

                if (strpos($line, "playback latency =") === false 
                    && strpos($line, "capture latency =") === false) 
                {
                    $current = trim($line);
                } 
                else 
                {
                    if (strpos($line, "capture latency") === false) 
                    {
                        $line_arr = explode(" ", trim($line));
                        $latency = intval($line_arr[6]);
                        $ports_arr[$current]["port_playback_latency"] = $latency;
                    }
                    
                    if (strpos($line, "playback latency") === false) 
                    {
                        $line_arr = explode(" ", trim($line));
                        $latency = intval($line_arr[6]);
                        $ports_arr[$current]["port_capture_latency"] = $latency;
                    }
                }
            }

            unset($port_latency);
            unset($raw_port_latency_arr);
            $total_latency = trim(shell_exec("{$this->jack_lsp_path} -L "));
            $raw_total_latency_arr = explode("\n", $total_latency);

            foreach ($raw_total_latency_arr as $line) // extract total latency
            {
                if (empty($line)) 
                {
                    continue;
                }

                if (strpos($line, "total latency =") === false) 
                {
                    $current = trim($line);
                } 
                else 
                {
                    $line_arr = explode(" ", trim($line));
                    $latency = intval($line_arr[3]);
                    $ports_arr[$current]["total_latency"] = $latency;
                }
            }

            unset($total_latency);
            unset($total_latency_arr);
            $properties = trim(shell_exec("{$this->jack_lsp_path} -p "));
            $raw_properties_arr = explode("\n", $properties);

            foreach ($raw_properties_arr as $line) // extract properties
            {
                if (empty($line)) 
                {
                    continue;
                }

                if (strpos($line, "properties: ") === false) 
                {
                    $current = trim($line);
                } 
                else 
                {
                    $line_arr = explode(" ", trim($line));
                    $ports_arr[$current]["properties"] = rtrim($line_arr[1], ",");

                    if (strpos($ports_arr[$current]["properties"], "output") !== false) 
                    {
                        $ports_arr[$current]["port_type"] = AudioPort::SOURCE;
                    }

                    if (strpos($ports_arr[$current]["properties"], "input") !== false) 
                    {
                        $ports_arr[$current]["port_type"] = AudioPort::SINK;
                    }
                }
            }

            unset($properties);
            unset($raw_properties_arr);
            
            foreach (array_keys($ports_arr) as $compound_name)
            {
                $first_colon_pos = strpos($compound_name, ":");
                $client_name = substr($compound_name, 0, $first_colon_pos);
                $port_name = substr($compound_name, $first_colon_pos + 1);
                $ports_arr[$compound_name]["client_name"] = $client_name;
                $ports_arr[$compound_name]["name"] = $port_name;
            }

            foreach ($ports_arr as $compound_name => $port_arr)
            {
                $port_config = new AudioPortConfig();
                $port_config->aliases = isset($port_arr["aliases"]) ? $port_arr["aliases"] : null;
                $port_config->client_name = isset($port_arr["client_name"]) ? $port_arr["client_name"] : null;
                $port_config->connections = isset($port_arr["connections"]) ? $port_arr["connections"] : null;
                $port_config->content_type = isset($port_arr["content_type"]) ? $port_arr["content_type"] : null;
                $port_config->client_name = $port_arr["client_name"];
                $port_config->name = $port_arr["name"];
                $port_config->port_playback_latency = isset($port_arr["port_playback_latency"]) ? $port_arr["port_playback_latency"] : null;
                $port_config->port_capture_latency = isset($port_arr["port_capture_latency"]) ? $port_arr["port_capture_latency"] : null;
                $port_config->port_type = isset($port_arr["port_type"]) ? $port_arr["port_type"] : null;
                $port_config->properties = isset($port_arr["properties"]) ? $port_arr["properties"] : null;
                $port_config->total_latency = isset($port_arr["total_latency"]) ? $port_arr["total_latency"] : null;
                $audio_port = new AudioPort($port_config);

                if (!$client = $this->getClientByName($audio_port->getClientName())) 
                {
                    $client_config = new ClientConfig();
                    $client_config->name = $audio_port->getClientName();
                    $client = new Client($client_config);
                    $client->addPort($audio_port);
                    $this->clients[] = $client;
                }
                else 
                {
                    $client->addPort($audio_port);
                }
            }
        }
    }  

    /**
     * If the statefile property exists, this method tries to condense the 
     * current program state to a JSON string and store it in that file. State 
     * in this case means the presence or absence of a process ID by which to 
     * identify a running jackd process.
     *
     * @return void
     * @access private
     */
    private function saveState() : void 
    {
        if ($this->statefile)
        {
            file_put_contents($this->statefile, $this->process_id, LOCK_EX);
        }
    } 

    /**
     * Shut down the JACK server.
     *
     * @return void
     * @access public
     */
    public function shutdown() : void 
    {
        shell_exec("kill -9 {$this->process_id}");
    } 

    /**
     * int startJackServer ( JackServerConfig )
     * 
     * Starts a new JackServer instance and returns the PID.
     *
     * @param JackServerConfig config
     * @return int
     * @access public
     */
    public function startJackServer(JackServerConfig $config = null) : int 
    {
        $cmd = $config === null ? (new JackServer($this->jack_config))->__toString() : (new JackServer($config))->__toString();
        $cmd .= $this->console_log ? "> {$this->console_log} " : null;
        $cmd .= "2>&1 & echo $!";
        $ret = shell_exec($cmd);
        usleep(125000);
        $ret_arr = $ret ? explode("\n", $ret) : [];
        $this->process_id = isset($ret_arr[1]) ? intval($ret_arr[1]) : $this->process_id;

        if ($this->process_id) 
        {
            $this->initialize();
        }

        return $this->process_id;
    }

    /**
     * This method compares the loaded state values to the actual conditions on 
     * the system. If the loaded state is not in conflict with the system state, 
     * true is returned. Otherwise, false is returned.
     *
     * @param int actual_pid
     * @return bool
     * @access private
     */
    private function verifyState(int $actual_pid = null) : bool 
    {
        return $this->process_id === $actual_pid ? true : false;
    } 
} 
