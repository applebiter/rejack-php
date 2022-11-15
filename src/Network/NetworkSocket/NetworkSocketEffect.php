<?php
namespace Rejack\Network\NetworkSocket;

use Rejack\Network\NetworkSocket\NetworkSocketEffect\EffectConfig;


/**
 * class NetworkSocketEffect
 * This class class models the portion of the JackTrip launching command that 
 * provides for compressor and/or reverb filters on incoming and/or outgoing 
 * audio signals.
 */
class NetworkSocketEffect
{
    /**
     * If this flag is true then this effect is applied to outgoing signal.
     * @var bool
     * @access private
     */
    private $outgoing;

    /**
     * If this flag is true then this effect is applied to incoming signal.
     * @var bool
     * @access private
     */
    private $incoming;

    /**
     * If this flag is true then this effect applies a compressor.
     * @var bool
     * @access private
     */
    private $compressor;

    /**
     * If this flag is set then this effect uses freeverb to apply reverb.
     * @var bool
     * @access private
     */
    private $freeverb;

    /**
     * If this flag is set then this effect uses zitarev to apply reverb.
     * @var bool
     * @access private
     */
    private $zitarev;

    /**
     * Values from 0 to 1 are applied using freeverb and higher values from 1-2 
     * are applied using zitarev. 
     * @var float
     * @access private
     */
    private $reverb_wetness;

    /**
     * The compression ratio to apply. Examples include but are not limited to:
     *      2 = (designed for voice) 
     *      3 = (for horns)
     *      6 = (for snare)
     * @var float
     * @access private
     */
    private $compression_ratio;

    /**
     * Defines the target threshold dB.
     * @var float
     * @access private
     */
    private $threshold_db;

    /**
     * Defines the attack time in milliseconds.
     * @var float
     * @access private
     */
    private $attack_time_ms;

    /**
     * Defines the release time in milliseconds.
     * @var float
     * @access private
     */
    private $release_time_ms;

    /**
     * Defines the gain.
     * @var float
     * @access private
     */
    private $makeup_gain_db;


    /**
     * Class constructor
     * @param EffectConfig config
     * @return NetworkSocketEffect
     * @access public
     */
    public function __construct(EffectConfig $config) 
    {
        $this->outgoing = $config->outgoing;
        $this->incoming = $config->incoming;
        $this->compressor = $config->compressor;
        $this->freeverb = $config->freeverb;
        $this->zitarev = $config->zitarev;
        $this->reverb_wetness = $config->reverb_wetness;
        $this->compression_ratio = $config->compression_ratio;
        $this->threshold_db = $config->threshold_db;
        $this->attack_time_ms = $config->attack_time_ms;
        $this->release_time_ms = $config->release_time_ms;
        $this->makeup_gain_db = $config->makeup_gain_db;
    } 

    /**
     * string toString ( )
     *
     * Translates the object's attribute values into a portion of the JackTrip 
     * launching command.
     * @return string
     * @access public
     */
    public function toString() : string
    {
        $cmd = "";

        if ($this->outgoing)
        {
            $cmd .= "o:";
        }
        elseif ($this->incoming)
        {
            $cmd .= "i:";
        }
        else 
        {
            return "";
        }

        if ($this->compressor) 
        {
            $cmd .= "c(";
            $cmd .= "c:{$this->compression_ratio} ";
            $cmd .= "t:{$this->threshold_db} ";
            $cmd .= "a:{$this->attack_time_ms} ";
            $cmd .= "r:{$this->release_time_ms} ";
            $cmd .= "g:{$this->makeup_gain_db}";
            $cmd .= ") ";
        }
        elseif ($this->freeverb) 
        {
            $cmd .= "f({$this->reverb_wetness}) ";
        }
        elseif ($this->zitarev)
        {
            $cmd .= "z({$this->reverb_wetness}) ";
        }
        else 
        {
            return "";
        }

        return $cmd;
    } 
} 
