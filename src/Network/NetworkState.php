<?php
namespace Rejack\Network;


/**
 * class NetworkState
 * This class models the data stored in the statefile on the server between page 
 * loads.
 */
class NetworkState
{
    /**
     * An array of NetworkPortState objects, if any.
     * @var NetworkPortState[]
     * @access public
     */
    public $ports = null;

    /**
     * The ports currently occupied by running JackTrip instances.
     * @var int[]
     * @access public
     */
    public $ports_in_use = null;
} 
