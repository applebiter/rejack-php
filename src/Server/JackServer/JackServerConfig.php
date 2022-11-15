<?php
namespace Rejack\Server\JackServer;


/**
 * class JackServerConfig
 * Class JackServerConfig models the inputs required by the JackServer class 
 * upon initialization.
 */
class JackServerConfig
{

    /**
     * Configuration object for the JACK backend. Required.
     * @var Rejack\Server\JackServer\Backend\JackBackendConfig
     * @access public
     */
    public $backend = null;

    /**
     * -c, --clocksource ( h(pet) | s(ystem)  )
     * 
     * Select a specific wall clock (HPET timer, System timer). Optional.
     * @var string
     * @access public
     */
    public $clocksource = null;

    /**
     * -d, --driver ( string )
     * 
     * Required.
     * @var string
     * @access public
     */
    public $driver = null;

    /**
     * -n, --name ( string )
     * 
     * Name this jackd instance. If unspecified, this name comes from the
     * $JACK_DEFAULT_SERVER environment variable. It will be "default" if that 
     * is not defined. Optional.
     * @var string
     * @access public
     */
    public $name = null;

    /**
     * -m, --no-mlock
     * 
     * Do not attempt to lock memory, even if realtime is enabled. Optional.
     * @var bool
     * @access public
     */
    public $no_mlock = null;

    /**
     * -Z, --nozombies
     * 
     * Prevent JACK from ever kicking out clients because they were too slow. 
     * This cancels the effect any specified timeout value, but JACK and its 
     * clients are still subject to the supervision of the wtchdog thread or its 
     * equivalent. Optional.
     * @var bool
     * @access public
     */
    public $nozombies = null;

    /**
     * -p, --port-max ( int )
     * 
     * Set the maximum number of ports the JACK server can manage. The default 
     * value is 256. Optional.
     * @var int
     * @access public
     */
    public $port_max = null;

    /**
     * ex.: /usr/bin/jackd
     * 
     * Path the jackd server binary. Required.
     * @var string
     * @access public
     */
    public $program_path = null;

    /**
     * -R, --realtime
     * 
     * Use realtime scheduling (default = true). This is needed for reliable
     * low-latency performance. On many systems, it requires jackd to run with 
     * special scheduler and memory allocation privileges, which may be obtained 
     * in several ways. Required.
     * @var bool
     * @access public
     */
    public $realtime = null;

    /**
     * -P, --realtime-priority ( int )
     * 
     * When running in realtime mode, set the scheduler priority to an int value
     * between 1-99. Optional.
     * @var int
     * @access public
     */
    public $realtime_priority = null;

    /**
     * --replace-registry
     * 
     * Remove the shared memory registry used by all JACK server instances 
     * before startup. This should rarely be used, and is intended only for 
     * occasions when the structure of this registry changes in ways that are 
     * incompatible across JACK versions (which is rare). Optional.
     * @var bool
     * @access public
     */
    public $replace_registry = null;

    /**
     * --silent
     * 
     * Silence any output during operation. Optional.
     * @var bool
     * @access public
     */
    public $silent = null;

    /**
     * -T, --temporary
     * 
     * Exit once all clients have closed their connections. Optional.
     * @var bool
     * @access public
     */
    public $temporary = null;

    /**
     * -t, --timeout ( int )
     * 
     * Set client timeout limit in milliseconds. The default is 500 msec. In 
     * realtime mode the client timeout must be smaller than the watchdog 
     * timeout (5000 msec).
     * @var int
     * @access public
     */
    public $timeout = null;

    /**
     * -u, --unlock
     * 
     * Unlock libraries GTK+, QT, FLTK, Wine. Optional.
     * @var bool
     * @access public
     */
    public $unlock = null;
}
