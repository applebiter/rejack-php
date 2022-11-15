<?php
namespace Rejack\Network\Firewall;


/**
 * class FirewallConfig
 * This class models the inputs required by calss Firewall upon inititalization.
 */
class FirewallConfig
{
    /**
     * The outward-facing IP address of the ReJACK server.
     * @var string
     * @access public
     */
    public $local_ip_address = null;

    /**
     * The path to the ufw (uncomplicated firewall) program.
     * @var string
     * @access public
     */
    public $program_path = null;
} 
