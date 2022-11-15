<?php
namespace Rejack\Server\JackServer\Backend;


/**
 * class JackBackendConfig
 * Models the input parameters for either of the available backends, and renders 
 * from them a string to be appended to the JackServer object's toString() 
 * output.
 */
class JackBackendConfig
{

    /**
     * It the backend driver is ALSA, the following describes this parameter:
     * 
     * -C, --capture ( string )
     * 
     * Provide only capture ports, unless combined with -D or -P. Optionally set 
     * the capture device name. If you don't pass a string, it behaves as a 
     * boolean flag, but if you pass a string it it looks for capture ports on 
     * the device you name. Optional.
     * 
     * If the backend driver is Dummy, then the following describes this 
     * parameter:
     * 
     * -C, --capture ( int )
     * 
     * Specify the number of capture ports. The default is 2. Optional.
     * @var string|int
     * @access public
     */
    public $capture = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -d, --device ( string )
     * 
     * The ALSA pcm device name to use. If none is specified, JACK will use 
     * "hw:0", the first hardware card defined in /etc/modules.conf. Required.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var string
     * @access public
     */
    public $device = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -z, --dither ( r(ectangular)|t(riangular)|s(haped)|n(one) )
     * 
     * Set dithering mode. If "none" or unspecified, dithering is off. Only the 
     * first letter of the mode name is required. Optional. 
     * 
     * The Dummy backend driver does not use this parameter.
     * @var string
     * @access public
     */
    public $dither = null;

    /**
     * If the backend driver is ALSA then the following describes this 
     * parameter:
     * 
     * -D, --duplex
     * 
     * Provide both capture and playback ports. Defaults to on unless only one 
     * of -P or -C is specified. Optional.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var bool
     * @access public
     */
    public $duplex = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -M, --hwmeter
     * 
     * Enable hardware metering for devices that support it. Other wise, use 
     * software
     * metering. Optional.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var bool
     * @access public
     */
    public $hardware_metering = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
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
     * Delta series, Terratec, and others) support --hwmon. In the future, 
     * some consumer cards may also be supported by modifying their mixer 
     * settings.
     * 
     * Without --hwmon, port monitoring requires JACK to read audio into system 
     * memory, then copy it back out to the hardware again, imposing the basic 
     * JACK system latency determined by the --period and --nperiods parameters.
     * 
     * Optional.
     * @var bool
     * @access public
     */
    public $hardware_monitoring = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -i, --inchannels ( int )
     * 
     * Number of capture channels. Default is the maximum supported by the 
     * hardware. Optional.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var int
     * @access public
     */
    public $inchannels = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -I, --input-latency ( int )
     * 
     * Extra input latency (in frames) (default = 0). Optional.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var int
     * @access public
     */
    public $input_latency = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -X, --midi-driver ( raw|seq )
     * 
     * ALSA MIDI driver (default = none). Optional.
     * 
     * The Dummy driver backend does not use this parameter.
     * @var string
     * @access public
     */
    public $midi_driver = null;

    /**
     * -m, --monitor
     * 
     * Provide monitor ports for the output (default = false). Optional.
     * @var bool
     * @access public
     */
    public $monitor = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
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
     * default.
     * 
     * Optional.
     * 
     * The Dummy driver backend does not use this parameter.
     * @var int
     * @access public
     */
    public $nperiods = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -o, --outchannels ( int )
     * 
     * Number of playback channels. Default is maximum supported by hardware.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var int
     * @access public
     */
    public $outchannels = null;

    /**
     * If the backend driver is ALSA then the following describes this 
     * parameter:
     * 
     * -O, --output-latency ( int )
     * 
     * Extra output latency in frames (default = 0). Optional.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var int
     * @access public
     */
    public $output_latency = null;

    /**
     * -p, --period ( int )
     * 
     * Specify the number of frames between JACK process() calls. This value 
     * must be a power of 2, and the default is 1024. If you need low latency, 
     * set -p as low as you can go without seeing xruns. A larger period size 
     * yields higher latency, but makes xruns less likely. The JACK capture 
     * latency in seconds is --period divided by --rate. Required.
     * @var int
     * @access public
     */
    public $period = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -P, --playback ( string )
     * 
     * Provide only playback ports, unless combined with -D or -C. Optionally 
     * set the playback device name. The flag is treated as a boolean true 
     * unless a name is also passed, in which case its still a bool but the name 
     * is going to tell the driver which device to scan for playback ports. 
     * 
     * Optional.
     * 
     * If the backend driver is Dummy, then the following describes this 
     * parameter:
     * 
     * -P, --playback ( int )
     * 
     * Specify the number of playback ports. The default value is 2. Optional.
     * @var string|int
     * @access public
     */
    public $playback = null;

    /**
     * -r, --rate ( int )
     * 
     * Specify the sample rate in Hz. The default is 48000. Required.
     * @var int
     * @access public
     */
    public $rate = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -S, --shorts
     * 
     * Try to configure the card for 16-bit samples first, only trying 32-bit 
     * samples if unsuccessful. Default is to prefer 32-bit samples. Optional.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var bool
     * @access public
     */
    public $shorts = null;

    /**
     * If the backend driver is ALSA, then the following describes this 
     * parameter:
     * 
     * -s, --softmode
     * 
     * Ignores xruns reported by the ALSA driver. This makes JACK less likely to
     * disconnect unresponsive ports when running without --realtime. Optional.
     * 
     * The Dummy backend driver does not use this parameter.
     * @var bool
     * @access public
     */
    public $soft_mode = null;

    /**
     * If the backend driver is Dummy, then the following describes this 
     * parameter:
     * 
     * -w, --wait ( int )
     * 
     * Specify the number of usecs to wait between engine processes. The default 
     * value is 21333. Optional.
     * 
     * The ALSA backend driver does not use this parameter.
     * @var int
     * @access public
     */
    public $wait = null;
} 
