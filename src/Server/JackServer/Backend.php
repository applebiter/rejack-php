<?php
namespace Rejack\Server\JackServer;

/**
 * class Backend
 * 
 */
abstract class Backend
{
    
    const DITHER_RECTANGULAR = 'r';
    const DITHER_TRIANGULAR = 't';
    const DITHER_SHAPED = 's';
    const DITHER_NONE = 'n';

    const MIDI_RAW = 'raw';
    const MIDI_SEQ = 'seq';

    const NPERIOD_NORMAL = 2;
    const NPERIOD_SLOW = 3;

    const PERIOD_LOW = 128;
    const PERIOD_MEDIUM = 256;
    const PERIOD_HIGH = 512;
    const PERIOD_MAX = 1024;

    const RATE_LOW = 44100;
    const RATE_MEDIUM = 48000;
    const RATE_HIGH = 88200;
    const RATE_MAX = 96000;
    
    /** @return string  */
    abstract public function __toString();
} 
