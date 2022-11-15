<?php
namespace Rejack\Network;

use Rejack\Network\Firewall\FirewallConfig;
use Rejack\Network\NetworkPortState;


/**
 * class Firewall
 * 
 */
class Firewall
{

    /**
     * The outward-facing IP address of the ReJACK server.
     * @var string
     * @access private
     */
    private $local_ip_address;


    /**
     * Class constructor
     * @param FirewallConfig config 
     * @return Firewall
     * @access public
     */
    public function __construct(FirewallConfig $config) 
    {
        $this->local_ip_address = $config->local_ip_address ? utf8_encode($config->local_ip_address) : null;
    } 

    /**
     * void closePort ( NetworkPortState )
     *
     * Closes a specific protocol and port to a specific ip address.
     * @param NetworkPortState state 
     * @return void
     * @access public
     */
    public function closePort(NetworkPortState $state) : void
    {
        shell_exec(sprintf("ufw deny from %s to %s port %d",
            $state->remote_ip_address,
            $this->local_ip_address, 
            $state->port_number));
    } 

    /**
     * void openPort ( NetworkPortState )
     *
     * Opens a specific protocol and port to a specific ip address.
     * @param NetworkPortState state
     * @return void
     * @access public
     */
    public function openPort(NetworkPortState $state) : void
    {
        shell_exec(sprintf("ufw allow from %s to %s port %d",
            $state->remote_ip_address,
            $this->local_ip_address, 
            $state->port_number));
    } 
} 
