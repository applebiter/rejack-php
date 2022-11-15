<?php
namespace Rejack\Network;

/**
 * class NetworkConfig
 * 
 */
class NetworkConfig
{
    /**
     * The outward-facing IP address of the ReJACK server.
     * @var string
     * @access public
     */
    public $local_ip_address = null;
    
    /**
     * An array of port numbers defined in the configuration that are the only 
     * ports available to the app.
     * @var int[] 
     * @access public
     */
    public $ports_to_use = null;
    
    /**
     * The path to a file in which a JSON string representing the Network 
     * component state may be stored.
     * @var string
     * @access public
     */
    public $statefile = null;

    /**
     * The name of the user under whom the apache2 server and any instances of 
     * JackTrip started by the server will run.
     * @var string 
     * @access public
     */
    public $username = null;
} 