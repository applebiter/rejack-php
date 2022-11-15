<?php
namespace Rejack\Patchbay;

/**
 * class PatchbayConfig
 * @package Rejack
 */
class PatchbayConfig
{
    /**
     * The path to the console program jack_connect.
     * @var string
     * @access public
     */
    public $jack_connect_path = null;

    /**
     * The path to the console program jack_disconnect.
     * @var string
     * @access public
     */
    public $jack_disconnect_path = null;

    /**
     * All of the current audio ports together in an array, separate from the 
     * individual clients to which they belong.
     * @var AudioPort[]
     * @access public
     */
    public $ports = null;

    /**
     * The path to a writeable folder wherein a connection state may be stored 
     * for later use.
     * @var string
     * @access public
     */
    public $presets_path = null;
}
