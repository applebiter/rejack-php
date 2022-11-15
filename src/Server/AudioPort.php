<?php
namespace Rejack\Server;

use Rejack\Server\AudioPort\AudioPortConfig;


/**
 * class AudioPort
 * Class AudioPort provides useful information about every available port on the
 * JACK bus.
 */
class AudioPort
{

    const SOURCE = 1;
    const SINK = 0;
    
    /**
     * Derived from the output of:
     * 
     * jack_lsp -A
     * 
     * Lists aliases for the port.
     * @var string
     * @access private
     */
    private $aliases;

    /**
     * The name of the Client on the JACK bus.
     * @var string
     * @access private
     */
    private $client_name;

    /**
     * If the port is a source, then the connections array will contain 0 or 
     * more names of AudioPort objects of the sink type, and vice versa.
     * @var string[]
     * @access private
     */
    private $connections;

    /**
     * Derived from the output of:
     * 
     * jack_lsp -t
     * @var string
     * @access private
     */
    private $content_type;

    /**
     * The name of the port on the JACK bus.
     * @var string
     * @access private
     */
    private $name;

    /**
     * Derived from the output of:
     * 
     * jack_lsp -l
     * 
     * Display per-port latency in frames at this port.
     * @var int
     * @access private
     */
    private $port_playback_latency;

    /**
     * Derived from the output of:
     * 
     * jack_lsp -l
     * 
     * Display per-port latency in frames at this port.
     * @var int
     * @access private
     */
    private $port_capture_latency;

    /**
     * Audio ports are either sources of audio or sinks waiting for audio input. 
     * This class provides matching constants:
     *     AudioPort::SOURCE = 1
     *     AudioPort::SINK = 0
     * @var int
     * @access private
     */
    private $port_type;

    /**
     * Derived from the output of:
     * 
     * jack_lsp -p
     * 
     * Display port properties. Output may include: input|output, can-monitor,
     * physical, terminal
     * @var string
     * @access private
     */
    private $properties;

    /**
     * Derived from the output of:
     * 
     * jack_lsp -L
     * 
     * Display total latency in frames at this port.
     * @var int
     * @access private
     */
    private $total_latency;


    /**
     * Class constructor
     * @param AudioPortConfig config 
     * @return AudioPort
     * @access public
     */
    public function __construct(AudioPortConfig $config) 
    {
        $this->aliases = $config->aliases ? utf8_encode($config->aliases) : null;
        $this->client_name = $config->client_name ? utf8_encode($config->client_name) : null;
        $this->connections = $config->connections;
        $this->content_type = $config->content_type ? utf8_encode($config->content_type) : null;
        $this->name = $config->name ? utf8_encode($config->name) : null;
        $this->port_playback_latency = $config->port_playback_latency;
        $this->port_capture_latency = $config->port_capture_latency;
        $this->port_type = $config->port_type;
        $this->properties = $config->properties ? utf8_encode($config->properties) : null;
        $this->total_latency = $config->total_latency;
    } 

    /**
     * Lists aliases for the port.
     * @return string|null
     * @access public
     */
    public function getAliases() : string|null 
    {
        return $this->aliases;
    } 

    /**
     * Returns the client name.
     * @return string 
     * @access public
     */
    public function getClientName() : string 
    {
        return $this->client_name;
    }

    /**
     * If the port is a source, then the connections array will contain 0 or 
     * more names of AudioPort objects of the sink type, and vice versa. 
     * @return string[]
     * @access public
     */
    public function getConnections() : array 
    {
        return $this->connections;
    } 

    /**
     * Returns useful information about the audio stream type of each AudioPort. 
     * Default is "32 bit float mono audio".
     * @return string
     * @access public
     */
    public function getContentType() : string 
    {
        return $this->content_type;
    } 

    /**
     * Get the name of the port on the JACK bus. 
     * @return string
     * @access public
     */
    public function getName() : string 
    {
        return $this->name;
    } 

    /**
     * Get the playback latency at this port measured in frames.
     * @return int
     * @access public
     */
    public function getPortPlaybackLatency() : int 
    {
        return $this->port_playback_latency;
    } 

    /**
     * Get the capture latency at this port measured in frames.
     * @return int
     * @access public
     */
    public function getPortCaptureLatency() : int 
    {
        return $this->port_playback_latency;
    }  

    /**
     * Get the port type. This class provides matching constants:
     *     AudioPort::SOURCE = 1
     *     AudioPort::SINK = 0
     * @return int
     * @access public
     */
    public function getPortType() : int 
    {
        return $this->port_type;
    } 

    /**
     * Returns port properties. Output may include: input|output, can-monitor, 
     * physical, terminal
     * @return string
     * @access public
     */
    public function getProperties() : string 
    {
        return $this->properties;
    }

    /**
     * Get the total latency at this port measured in frames.
     * @return int
     * @access public
     */
    public function getTotalLatency() : int 
    {
        return $this->total_latency;
    } 

    /**
     * array toArray ( )
     * 
     * Returns the port data as an associative array.
     * @return array 
     */
    public function toArray() : array
    {
        return [
            "aliases" => $this->aliases,
            "client_name" => $this->client_name,
            "connections" => $this->connections,
            "content_type" => $this->content_type,
            "name" => $this->name,
            "port_playback_latency" => $this->port_playback_latency,
            "port_capture_latency" => $this->port_capture_latency,
            "port_type" => $this->port_type,
            "properties" => $this->properties,
            "total_latency" => $this->total_latency
        ];
    }

    /**
     * string toString ( )
     * 
     * Returns the full port name <client name>:<port name>, ex.: 
     * pulse_out:front-right
     * @return string
     * @access public
     */
    public function toString() : string 
    {
        return "{$this->client_name}:{$this->name}";
    }
} 
