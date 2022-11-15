<?php
namespace Rejack\Server;

use Rejack\Server\Client\ClientConfig;
use Rejack\Server\AudioPort;


/**
 * class Client
 * Class Client models the parameters that initialize the Client objects.
 */
class Client
{

    /**
     * The client name on the JACK bus.
     * @var string
     * @access private
     */
    private $name;

    /**
     * An array of AudioPort objects belonging to this Client.
     * @var AudioPort[]
     * @access private
     */
    private $ports;


    /**
     * 
     *
     * @param ClientConfig config
     * @return Client
     * @access public
     */
    public function __construct(ClientConfig $config) 
    {
        $this->name = $config->name ? utf8_encode($config->name) : null;
    } 

    /**
     * Add another AudioPort to the array. 
     * @param AudioPort $port 
     * @return void 
     * @access public
     */
    public function addPort(AudioPort $port) : void 
    {
        $this->ports[] = $port;
    }

    /**
     * string getName ( )
     * 
     * Returns the Client name. 
     * @return string
     * @access public
     */
    public function getName() : string 
    {
        return $this->name;
    } 

    /**
     * AudioPort getPortByName ( string )
     * 
     * Returns an AudioPort object having the specified name. 
     * @param string name 
     * @return AudioPort|null
     * @access public
     */
    public function getPortByName(string $name) : AudioPort|null 
    {
        foreach ($this->ports as $port)
        {
            if ($port->getName() == $name)
            {
                return $port;
            }
        }

        return null;
    } 

    /**
     * AudioPort[] getPorts ( )
     * 
     * Returns all AudioPorts contained by the Client.
     * @return AudioPort[]
     * @access public
     */
    public function getPorts() : array 
    {
        return $this->ports;
    } 

    /**
     * Returns the Client in array form.
     * @return (string|string[]|int[])[]
     * @access public
     */
    public function toArray() : array
    {
        $ports = [];

        if (is_array($this->ports))
        {
            foreach ($this->ports as $idx => $port)
            {
                $ports[$idx] = $port->toArray();
            }
        }

        return [
            "name" => $this->name,
            "ports" => $ports
        ];
    }
}
