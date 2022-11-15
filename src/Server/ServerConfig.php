<?php
namespace Rejack\Server;


/**
 * class ServerConfig
 * Models the inputs required by the Server class upon initialization.
 */
class ServerConfig
{

    /**
     * If present, it represents the path to a file where console output can be
     * redirected when starting the JACK server process. Optional.
     * @var string
     * @access public
     */
    public $console_log = null;

    /**
     * The jack_config attribute contains an instance of JackServerConfig. This
     * parameter is required.
     * @var Rejack\Server\JackServer\JackServerConfig
     * @access public
     */
    public $jack_config = null;

    /**
     * The path to the console program jack_bufsize. Required.
     * @var string
     * @access public
     */
    public $jack_bufsize_path = null;

    /**
     * The path to the console program Jack_lsp.
     * @var string
     * @access public
     */
    public $jack_lsp_path = null;

    /**
     * The path to the console program jack_samplerate. Required.
     * @var string
     */
    public $jack_samplerate_path = null;

    /**
     * The path to a file where state data can be stored. If no statefile is 
     * specified, then Rejack will not store state data and no error is thrown. 
     * Optional.
     * @var string
     * @access public
     */
    public $statefile = null;
} 
