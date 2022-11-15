<?php
namespace Rejack\Network\NetworkSocket;


/**
 * class EffectConfig
 * This class models the inputs required for the effects processing offered by 
 * JackTrip. 
 */
class EffectConfig
{
    /**
     * If this flag is true then this effect is applied to outgoing signal.
     * @var bool
     * @access public
     */
    public $outgoing = null;

    /**
     * If this flag is true then this effect is applied to incoming signal.
     * @var bool
     * @access public
     */
    public $incoming = null;

    /**
     * If this flag is true then this effect applies a compressor.
     * @var bool
     * @access public
     */
    public $compressor = null;

    /**
     * If this flag is set then this effect uses freeverb to apply reverb.
     * @var bool
     * @access public
     */
    public $freeverb = null;

    /**
     * If this flag is set then this effect uses zitarev to apply reverb.
     * @var bool
     * @access public
     */
    public $zitarev = null;

    /**
     * Values from 0 to 1 are applied using freeverb and higher values from 1-2 
     * are applied using zitarev. 
     * @var float
     * @access public
     */
    public $reverb_wetness = null;

    /**
     * The compression ratio to apply. Examples include but are not limited to:
     *      2 = (designed for voice) 
     *      3 = (for horns)
     *      6 = (for snare)
     * @var float
     * @access public
     */
    public $compression_ratio = null;

    /**
     * Defines the target threshold dB.
     * @var float
     * @access public
     */
    public $threshold_db = null;

    /**
     * Defines the attack time in milliseconds.
     * @var float
     * @access public
     */
    public $attack_time_ms = null;

    /**
     * Defines the release time in milliseconds.
     * @var float
     * @access public
     */
    public $release_time_ms = null;

    /**
     * Defines the gain.
     * @var float
     * @access public
     */
    public $makeup_gain_db = null;
} 
