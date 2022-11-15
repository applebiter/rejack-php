<?php
namespace Rejack\Network;


/**
 * class NetworkPortState
 * This class models the data stored in the statefile on the server between page 
 * loads.
 */
class NetworkPortState
{
    /**
     * The network port number used to make the remote connection.
     * @var int
     * @access public
     */
    public $port_number = null;

    /**
     * The PID of the JackTrip instance using this port.
     * @var int
     * @access public
     */
    public $process_id = null;

    /**
     * The user's IP address. 
     * @var string
     * @access private
     */
    public $remote_ip_address;
} 
