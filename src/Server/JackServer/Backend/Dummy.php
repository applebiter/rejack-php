<?php
namespace Rejack\Server\JackServer\Backend;

use Rejack\Server\JackServer\Backend;


/**
 * class Dummy
 * Class Dummy models the parameters that initialize the Dummy backend.
 */
class Dummy extends Backend
{

    /**
     * -C, --capture ( int )
     * 
     * Specify the number of capture ports. The default is 2.
     * @var int
     * @access private
     */
    private $capture;

    /**
     * -m, --monitor
     * 
     * Provide monitor ports for the output (default = false).
     * @var bool
     * @access private
     */
    private $monitor;

    /**
     * -p, --period ( int )
     * 
     * Specify the number of frames between JACK process() calls. This value 
     * must be a power of 2, and the default is 1024. If you need low latency, 
     * set -p as low as you can go without seeing xruns. A larger period size 
     * yields higher latency, but makes xruns less likely. The JACK capture 
     * latency in seconds is --period divided by --rate. This class' parent 
     * provides matching constants:
     *     Rejack\Server\JackServer\Backend::PERIOD_LOW = 128;
     *     Rejack\Server\JackServer\Backend::PERIOD_MEDIUM = 256;
     *     Rejack\Server\JackServer\Backend::PERIOD_HIGH = 512;
     *     Rejack\Server\JackServer\Backend::PERIOD_MAX = 1024;
     * @var int
     * @access private
     */
    private $period;

    /**
     * -P, --playback ( int )
     * 
     * Specify the number of playback ports. The default value is 2.
     * @var int
     * @access private
     */
    private $playback;

    /**
     * -r, --rate ( int )
     * 
     * Specify the sample rate. The default value is 48000. This class' parent 
     * provides matching constants:
     *     Rejack\Server\JackServer\Backend::RATE_LOW = 44100
     *     Rejack\Server\JackServer\Backend::RATE_MEDIUM = 48000
     *     Rejack\Server\JackServer\Backend::RATE_HIGH = 88200
     *     Rejack\Server\JackServer\Backend::RATE_MAX = 96000
     * @var int
     * @access private
     */
    private $rate;

    /**
     * -w, --wait ( int )
     * 
     * Specify the number of usecs to wait between engine processes. The default 
     * value is 21333.
     * @var int
     * @access private
     */
    private $wait;


    /**
     * 
     *
     * @param JackBackendConfig config 
     * @return void
     * @access public
     */
    public function __construct(JackBackendConfig $config) 
    {
        $this->capture = $config->capture;
        $this->monitor = $config->monitor;
        $this->period = $config->period;
        $this->playback = $config->playback;
        $this->rate = $config->rate;
        $this->wait = $config->wait;
    } 

    /**
     * Renders the Dummy model to a single line of code to be appended to the 
     * output of a JackServer model.
     *
     * @return string
     * @access public
     */
    public function __toString() 
    {
        $str = "";
        $str .= $this->capture !== null ? "-C {$this->capture} " : null;
        $str .= $this->monitor !== null ? "-m " : null;
        $str .= "-p {$this->period} ";
        $str .= $this->playback !== null ? "-P {$this->playback} " : null;
        $str .= "-r {$this->rate} ";
        $str .= $this->wait !== null ? "-w {$this->wait} " : null;
        return $str;
    } 
}
