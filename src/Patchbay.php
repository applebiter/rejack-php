<?php
namespace Rejack;

use Rejack\Server\AudioPort;
use Rejack\Patchbay\PatchbayConfig;


/**
 * class Patchbay
 * 
 */
class Patchbay
{

    /**
     * If an error is encountered, a message is generated and stored in this 
     * array of error messages for later retrieval.
     * @var string[]
     * @access private
     */
    private $errors;
    
    /**
     * The path to the jack_connect program.
     * @var string
     * @access private
     */
    private $jack_connect_path;

    /**
     * The path to the jack_disconnect program.
     * @var string
     * @access private
     */
    private $jack_disconnect_path;
    
    /**
     * All of the current audio ports together in an array, separate from the 
     * individual clients to which they belong.
     * @var AudioPort[]
     * @access private
     */
    private $ports;

    /**
     * The path to a writeable folder wherein a connection state may be stored 
     * for later use.
     * @var string
     * @access private
     */
    private $presets_path;


    /**
     * 
     *
     * @param PatchbayConfig config 
     * @return Patchbay
     * @access public
     */
    public function __construct(PatchbayConfig $config) 
    {
        $this->presets_path = $config->presets_path ? utf8_encode($config->presets_path) : null;
        $this->jack_connect_path = $config->jack_connect_path ? utf8_encode($config->jack_connect_path) : null;
        $this->jack_disconnect_path = $config->jack_disconnect_path ? utf8_encode($config->jack_disconnect_path) : null;

        if (is_array($config->ports) && count($config->ports))
        {
            foreach ($config->ports as $port) 
            {
                $this->ports[$port->toString()] = $port;
            }
        }
    }

    /**
     * void addError ( string )
     * 
     * Insert an error message into the Patchbay::errors array.
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
     * Resets the errors array to null.
     * @return void
     * @access public
     */
    public function clearErrors() : void
    {
        $this->errors = null;
    } 

    /**
     * void connect ( string, string )
     *
     * This method connects any two audio ports provided that they are of 
     * opposite type, so SINK to SOURCE or SOURCE to SINK. By convention I put 
     * the source first, but the console program being called doesn't care which 
     * is which.
     * @param string source
     * @param string sink
     * @return void
     * @access public 
     */
    public function connect(string $source, string $sink) 
    {
        shell_exec("{$this->jack_connect_path} \"{$source}\" \"{$sink}\"");
    } 

    /**
     * void disconnect ( string, string )
     *
     * Like the connect() method, I put source before sink by convention, but 
     * the underlying software doesn't care which comes first.
     * @param string source 
     * @param string sink 
     * @return void
     * @access public
     */
    public function disconnect(string $source, string $sink) 
    {
        shell_exec("{$this->jack_disconnect_path} \"{$source}\" \"{$sink}\"");
    } 

    /**
     * void disconnectAll ( )
     *
     * Disconnects all current connections between audio ports.
     * @return void
     * @access public
     */
    public function disconnectAll() 
    {
        foreach ($this->ports as $port) 
        {
            if (is_array($port->getConnections()) && count($port->getConnections())) 
            {
                foreach ($port->getConnections as $connection)
                {
                    shell_exec("{$this->jack_disconnect_path} \"{$port->toString()}\" \"{$connection}\"");
                }
            }
        }
    } 

    /**
     * int|bool exportPreset ( string, bool = true )
     *
     * Takes a string name and a bool indicating whether to overwrite any 
     * existing presets bearing the same name.
     * @param string name 
     * @param bool overwrite 
     * @return int|bool
     * @access public
     */
    public function exportPreset(string $name, bool $overwrite = true) : int|bool
    {
        $filename = str_replace([" ", "?", "\\", "/", "."], "_", $name);
        
        if (file_exists($filename)) 
        {
            if (!$overwrite)
            {
                $this->addError("That file already exists: \"{$filename}\"");
                return false;
            }
        }

        if (!$this->presets_path)
        {
            $this->addError("A storage path for presets has not been specified.");
            return false;
        }

        if (!is_dir($this->presets_path))
        {
            $this->addError("The specified presets storage directory does not exist: \"{$this->presets_path}\"");
            return false;
        }

        if (!is_writable($this->presets_path))
        {
            $this->addError("The specified presets storage directory is not writable: \"{$this->presets_path}\"");
            return false;
        }

        $content = [];

        if (is_array($this->ports) && count($this->ports))
        {
            foreach ($this->ports as $port)
            {
                if (is_array($port->getConnections()))
                {
                    foreach ($port->getConnections() as $connection)
                    {
                        $content[$port->toString()][] = $connection;
                    }
                }
            }
        }

        return file_put_contents($filename, json_encode($content));
    } 

    /**
     * AudioPort|null getAudioPort ( string )
     * 
     * Takes a full <client:port> name and if a matching port is found it is 
     * returned.
     * @param string $name 
     * @return AudioPort|null 
     */
    public function getAudioPort(string $name) : AudioPort|null
    {
        return array_key_exists($name, $this->ports) ? $this->ports[$name] : null;
    }

    /**
     * array getAudioPortAsArray ( string )
     * 
     * Takes a full <client:port> name and if a matching port is found it is 
     * returned as a struct.
     * @param string $name 
     * @return array|null 
     */
    public function getAudioPortAsArray(string $name) : array|null
    {
        return array_key_exists($name, $this->ports) ? $this->ports[$name]->toArray() : null;
    }

    /**
     * AudioPort[] getAudioPorts ( )
     * 
     * Returns the array of AudioPort objects.
     * @return array 
     * @access public
     */
    public function getAudioPorts() : array
    {
        return $this->ports;
    }

    /**
     * array getAudioPortsAsArray ( )
     * 
     * Returns the array of AudioPort objects as an array of structs.
     * @return array 
     * @access public
     */
    public function getAudioPortsAsArray() : array
    {
        $arr = [];

        foreach ($this->ports as $port)
        {
            $arr[$port->toString()] = $port->toArray();
        }

        return $arr;
    }

    /**
     * array getAudioPortsList ( )
     * 
     * Returns a one-dimensional array of client:port names.
     * @return array 
     * @access public
     */
    public function getAudioPortsList() : array 
    {
        return array_keys($this->ports);
    }

    /**
     * string[] getErrors ( )
     * 
     * Returns a one-dimensional array of error messages, if any.
     * @return string[]
     * @access public
     */
    public function getErrors() : array 
    {
        return $this->errors ? $this->errors : [];
    } 

    /**
     * AudioPort[] getSinks ( )
     * 
     * Returns an array of AudioPort::SINK-type (playback) ports.
     * @return array 
     * @access public
     */
    public function getSinks() : array 
    {
        $arr = [];
        
        if (is_array($this->ports))
        {
            foreach ($this->ports as $port)
            {
                if ($port->getPortType() === AudioPort::SINK)
                {
                    $arr[$port->toString()] = $port;
                }
            }
        }

        return $arr;
    }

    /**
     * array getSinksAsArrays ( )
     * 
     * Returns an array of AudioPort::SINK-type (playback) ports as structs.
     * @return array 
     * @access public
     */
    public function getSinksAsArrays() : array 
    {
        $arr = [];
        
        if (is_array($this->ports))
        {
            foreach ($this->ports as $port)
            {
                if ($port->getPortType() === AudioPort::SINK)
                {
                    $arr[$port->toString()] = $port->toArray();
                }
            }
        }

        return $arr;
    }

    /**
     * string[] getSinksList ( )
     * 
     * Returns a one-dimensional array of client:port names of 
     * AudioPort::SINK-type ports.
     * @return array 
     * @access public
     */
    public function getSinksList() : array 
    {
        $arr = [];
        
        if (is_array($this->ports))
        {
            foreach ($this->ports as $port)
            {
                if ($port->getPortType() === AudioPort::SINK)
                {
                    $arr[] = $port->toString();
                }
            }
        }

        return $arr;
    }

    /**
     * AudioPort[] getSources ( )
     * 
     * Returns an array of AudioPort::SOURCE-type (capture) ports.
     * @return array
     * @access public 
     */
    public function getSources() : array 
    {
        $arr = [];
        
        if (is_array($this->ports))
        {
            foreach ($this->ports as $port)
            {
                if ($port->getPortType() === AudioPort::SOURCE)
                {
                    $arr[$port->toString()] = $port;
                }
            }
        }

        return $arr;
    }

    /**
     * array getSourcesAsArrays ( )
     * 
     * Returns an array of AudioPort::SOURCE-type (capture) ports as structs.
     * @return array 
     * @access public
     */
    public function getSourcesAsArrays() : array 
    {
        $arr = [];
        
        if (is_array($this->ports))
        {
            foreach ($this->ports as $port)
            {
                if ($port->getPortType() === AudioPort::SOURCE)
                {
                    $arr[$port->toString()] = $port->toArray();
                }
            }
        }

        return $arr;
    }

    /**
     * string[] getSourcesList ( )
     * 
     * Returns a one-dimensional array of client:port names of 
     * AudioPort::SOURCE-type ports.
     * @return array 
     * @access public
     */
    public function getSourcesList() : array 
    {
        $arr = [];
        
        if (is_array($this->ports))
        {
            foreach ($this->ports as $port)
            {
                if ($port->getPortType() === AudioPort::SOURCE)
                {
                    $arr[] = $port->toString();
                }
            }
        }

        return $arr;
    }

    /**
     * bool importPreset ( string )
     *
     * Takes in a string naming a stored connection state between audio ports 
     * and immediately attempts to impose it on the current audio ports as best 
     * it can.
     * @param string name 
     * @return bool
     * @access public
     */
    public function importPreset(string $name) : bool
    {
        $filename = str_replace([" ", "?", "\\", "/", "."], "_", $name);

        if (!$this->presets_path) 
        {
            $this->addError("A storage path for presets has not been specified.");
            return false;
        }

        if (!is_dir($this->presets_path) || !is_readable($this->presets_path))
        {
            $this->addError("");
            return false;
        }

        $filenames = scandir($this->presets_path);
    
        foreach ($filenames as $fn) 
        {            
            if (strcasecmp($filename, $fn) === 0) 
            {
                $content = file_get_contents($this->presets_path . DS . $fn);
                $content = !empty($content) ? json_decode($content) : $content;
                
                if (is_array($content) && count($content)) 
                {
                    foreach ($content as $portname => $connections)
                    {
                        if (isset($this->ports[$portname]) && is_array($connections) && count($connections)) 
                        {
                            foreach ($connections as $conn)
                            {
                                if (isset($this->ports[$conn]))
                                {
                                    $this->connect($portname, $conn);
                                }
                            }
                        }
                    }
                }
            }
        }

        return true;
    } 
}
