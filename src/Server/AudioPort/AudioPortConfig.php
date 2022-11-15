<?php
namespace Rejack\Server\AudioPort;

/**
 * class AudioPortConfig
 * Class AudioPortConfig models the inputs required by the AudioPort class upon
 * initialization.
 */
class AudioPortConfig
{

    /**
     * A list of aliases for the port.
     * @var string
     * @access public
     */
    public $aliases = null;

    /**
     * The name of the Client on the JACK bus.
     * @var string
     * @access public
     */
    public $client_name = null;

    /**
     * If the port is a source, then the connections array will contain 0 or 
     * more names of AudioPort objects of the sink type, and vice versa.
     * @var string[]
     * @access public
     */
    public $connections = null;

    /**
     * Useful information about the audio stream type of each AudioPort. Default 
     * is "32 bit float mono audio".
     * @var string
     * @access public
     */
    public $content_type = null;

    /**
     * The name of the port on the JACK bus.
     * @var string
     * @access public
     */
    public $name = null;

    /**
     * The playback latency at this port measured in frames.
     * @var int
     * @access public
     */
    public $port_playback_latency = null;

    /**
     * The capture latency at this port measured in frames.
     * @var int
     * @access public
     */
    public $port_capture_latency = null;

    /**
     * Audio ports are either sources of audio or sinks waiting for audio
     * input: 
     *     AudioPort::SOURCE = 1
     *     AudioPort::SINK = 0
     * @var int
     * @access public
     */
    public $port_type = null;

    /**
     * Display port properties. Output may include: input|output, can-monitor,
     * physical, terminal
     * @var string
     * @access public
     */
    public $properties = null;

    /**
     * Displays total latency in frames at this port.
     * @var int
     * @access public
     */
    public $total_latency = null;
} 
