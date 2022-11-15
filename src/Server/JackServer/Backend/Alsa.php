<?php
namespace Rejack\Server\JackServer\Backend;

use Rejack\Server\JackServer\Backend;


/**
 * class Alsa
 * Class Alsa models the parameters that initialize the ALSA backend.
 */
class Alsa extends Backend
{

    /**
     * -C, --capture ( string )
     * 
     * Provide only capture ports, unless combined with -D or -P. Optionally set 
     * the capture device name. If you don't pass a string, it behaves as a 
     * boolean flag, but if you pass a string it it looks for capture ports on 
     * the device you name.
     * @var string
     * @access private
     */
    private $capture;

    /**
     * -d, --device ( string )
     * 
     * The ALSA pcm device name to use. If none is specified, JACK will use 
     * "hw:0", the first hardware card defined in /etc/modules.conf.
     * @var string
     * @access private
     */
    private $device;

    /**
     * -z, --dither ( r(ectangular)|t(riangular)|s(haped)|n(one) )
     * 
     * Set dithering mode. If "none" or unspecified, dithering is off. Only the 
     * first letter of the mode name is required. This class' parent provides 
     * matching constants:
     *     Rejack\Server\JackServer\Backend::DITHER_RECTANGULAR = 'r'
     *     Rejack\Server\JackServer\Backend::DITHER_TRIANGULAR = 't';
     *     Rejack\Server\JackServer\Backend::DITHER_SHAPED = 's';
     *     Rejack\Server\JackServer\Backend::DITHER_NONE = 'n';
     * @var string
     * @access private
     */
    private $dither;

    /**
     * -D, --duplex
     * 
     * Provide both capture and playback ports. Defaults to on unless only one 
     * of -P or -C is specified.
     * @var bool
     * @access private
     */
    private $duplex;

    /**
     * -M, --hwmeter
     * 
     * Enable hardware metering for devices that support it. Other wise, use 
     * software metering.
     * @var bool
     * @access private
     */
    private $hardware_metering;

    /**
     * -H, --hwmon
     * 
     * Enable hardware monitoring of capture ports. This is a method for 
     * obtaining "zero latency" monitoring of audio input. It requires support 
     * in hardware and from the underlying ALSA device driver.
     * 
     * When enabled, requests to monitor capture ports will be satisfied by 
     * creating a direct signal path between audio interface input and output 
     * connectors, with no processing by the host computer at all. This offers 
     * the lowest possible latency for the monitored signal.
     * 
     * Presently ([the source documentation written in] March 2003), only the 
     * RME Hammerfall series and cards based on the ICE1712 chipset (M-Audio 
     * Delta series, Terratec, and others) support --hwmon. In the future, some 
     * consumer cards may also be supported by modifying their mixer settings.
     * 
     * Without --hwmon, port monitoring requires JACK to read audio into system 
     * memory, then copy it back out to the hardware again, imposing the basic 
     * JACK system latency determined by the --period and --nperiods parameters.
     * @var bool
     * @access private
     */
    private $hardware_monitoring;

    /**
     * -i, --inchannels ( int )
     * 
     * Number of capture channels. Default is the maximum supported by the 
     * hardware.
     * @var int
     * @access private
     */
    private $inchannels;

    /**
     * -I, --input-latency ( int )
     * 
     * Extra input latency (in frames) (default = 0).
     * @var int
     * @access private
     */
    private $input_latency;

    /**
     * -X, --midi-driver ( raw|seq )
     * 
     * ALSA MIDI driver (default = none). This class' parent  provides matching 
     * constants:
     *     Rejack\Server\JackServer\Backend::MIDI_RAW = 'raw'
     *     Rejack\Server\JackServer\Backend::MIDI_SEQ = 'seq'
     * @var string
     * @access private
     */
    private $midi_driver;

    /**
     * -m, --monitor
     * 
     * Provide monitor ports for the output (default = false).
     * @var bool
     * @access private
     */
    private $monitor;

    /**
     * -n, --nperiods ( int )
     * 
     * Specify the number of periods of playback latency. In seconds, this 
     * corresponds to --nperiods times --period divided by --rate. The default 
     * is 2, the minimum allowable. For most devices, there is no need for any 
     * other value with the --realtime option. Without realtime privileges or 
     * with boards providing unreliable interrupts (like ymfpci), a larger value 
     * may yield fewer xruns. This can also help if the system is not tuned for 
     * reliable realtime scheduling.
     * 
     * For most ALSA devices, the hardware buffer has exactly --period times 
     * --nperiods frames. Some devices demand a larger buffer. If so, JACK will 
     * use the smallest possible buffer containing at least --nperiods, but the 
     * playback latency does not increase.
     * 
     * For USB audio devices it is recommended to use -n 3. Firewire devices 
     * supported by FFADO (formerly Freebob) are configured with -n 3 by 
     * default.This class' parent provides matching constants:
     *     Rejack\Server\JackServer\Backend::NPERIODS_NORMAL = 2
     *     Rejack\Server\JackServer\Backend::NPERIODS_SLOW = 3
     * @var int
     * @access private
     */
    private $nperiods;

    /**
     * -o, --outchannels ( int )
     * 
     * Number of playback channels. Default is maximum supported by hardware.
     * @var int
     * @access private
     */
    private $outchannels;

    /**
     * -O, --output-latency ( int )
     * 
     * Extra output latency in frames (default = 0).
     * @var int
     * @access private
     */
    private $output_latency;

    /**
     * -p, --period ( int )
     * 
     * Specify the number of frames between JACK process() calls. This value 
     * must be a power of 2, and the default is 1024. If you need low latency, 
     * set -p as low as you can go without seeing xruns. A larger period size 
     * yields higher latency, but makes xruns less likely. The JACK capture 
     * latency in seconds is --period divided by --rate. This class' parent 
     * provides matching constants:
     *     Rejack\Server\JackServer\Backend::PERIOD_LOW = 128
     *     Rejack\Server\JackServer\Backend::PERIOD_MEDIUM = 256
     *     Rejack\Server\JackServer\Backend::PERIOD_HIGH = 512
     *     Rejack\Server\JackServer\Backend::PERIOD_MAX = 1024
     * @var int
     * @access private
     */
    private $period;

    /**
     * -P, --playback ( string )
     * 
     * Provide only playback ports, unless combined with -D or -C. Optionally 
     * set the playback device name. The flag is treated as a boolean true 
     * unless a name is also passed, in which case its still a bool but the name 
     * is going to tell the driver which device to scan for playback ports.
     * @var string
     * @access private
     */
    private $playback;

    /**
     * -r, --rate ( int )
     * 
     * Specify the sample rate. The default is 48000. This class' parent 
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
     * -S, --shorts
     * 
     * Try to configure the card for 16-bit samples first, only trying 32-bit 
     * samples if unsuccessful. Default is to prefer 32-bit samples.
     * @var bool
     * @access private
     */
    private $shorts;

    /**
     * -s, --softmode
     * 
     * Ignores xruns reported by the ALSA driver. This makes JACK less likely to
     * disconnect unresponsive ports when running without --realtime.
     * @var bool
     * @access private
     */
    private $soft_mode;


    /**
     * 
     *
     * @param JackBackendConfig config
     * @return Alsa
     * @access public
     */
    public function __construct(JackBackendConfig $config) 
    {
        $this->capture = $config->capture;
        $this->device = $config->device;
        $this->dither = $config->dither;
        $this->duplex = $config->duplex;
        $this->hardware_metering = $config->hardware_metering;
        $this->hardware_monitoring = $config->hardware_monitoring;
        $this->inchannels = $config->inchannels;
        $this->input_latency = $config->input_latency;
        $this->midi_driver = $config->midi_driver;
        $this->monitor = $config->monitor;
        $this->nperiods = $config->nperiods;
        $this->outchannels = $config->outchannels;
        $this->output_latency = $config->output_latency;
        $this->period = $config->period;
        $this->playback = $config->playback;
        $this->rate = $config->rate;
        $this->shorts = $config->shorts;
        $this->soft_mode = $config->soft_mode;
    } 

    /**
     * Renders the backend model into a single line of code to be appended to 
     * the output of the JackServer model.
     *
     * @return string
     * @access public
     */
    public function __toString() 
    {
        $str = "";
        $str .= $this->capture !== null ? "-C \"{$this->capture}\" " : null;
        $str .= "-d \"{$this->device}\" ";
        $str .= $this->dither !== null ? "-z {$this->dither} " : null;
        $str .= $this->duplex !== null ? "-D " : null;
        $str .= $this->hardware_metering !== null ? "-M " : null;
        $str .= $this->hardware_monitoring !== null ? "-H " : null;
        $str .= $this->inchannels !== null ? "-i {$this->inchannels} " : null;
        $str .= $this->input_latency !== null ? "-I {$this->input_latency} " : null;
        $str .= $this->midi_driver !== null ? "-X {$this->midi_driver} " : null;
        $str .= $this->monitor !== null ? "-m " : null;
        $str .= $this->nperiods !== null ? "-n {$this->nperiods} " : null;
        $str .= $this->outchannels !== null ? "-o {$this->outchannels} " : null;
        $str .= $this->output_latency !== null ? "-O {$this->output_latency} " : null;
        $str .= "-p {$this->period} ";
        $str .= $this->playback !== null ? "-P \"{$this->playback}\" " : null;
        $str .= "-r {$this->rate} ";
        $str .= $this->shorts !== null ? "-S " : null;
        $str .= $this->soft_mode !== null ? "-s " : null;
        return $str;
    } 
} 
