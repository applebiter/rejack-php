<?php
namespace Rejack\Server;

use Rejack\Server\JackServer\JackServerConfig;
use Rejack\Server\JackServer\Backend;
use Rejack\Server\JackServer\Backend\Alsa;
use Rejack\Server\JackServer\Backend\Dummy;

/**
 * class JackServer
 * Class JackServer models the parameters that initialize the JACK server.
 */
class JackServer
{

    const CLOCKSOURCE_HPET = 'h';
    const CLOCKSOURCE_SYSTEM = 's';

    const DRIVER_ALSA = 'alsa';
    const DRIVER_DUMMY = 'dummy';

    /**
     * Represented by either an Alsa or Dummy Backend object. 
     * @var Backend
     * @access private
     */
    private $backend;

    /**
     * -c, --clocksource ( h(pet) | s(ystem)  )
     * 
     * Select a specific wall clock (HPET timer, System timer). This class 
     * provides matching constants:
     *     self::CLOCKSOURCE_HPET = 'h'
     *     self::CLOCKSOURCE_SYSTEM = 's'
     * @var string
     * @access private
     */
    private $clocksource;

    /**
     * -d, --driver ( string )This class 
     * provides matching constants:
     *     self::DRIVER_ALSA = 'alsa'
     *     self::DRIVER_DUMMY = 'dummy'
     * @var string
     * @access private
     */
    private $driver;

    /**
     * -n, --name (server-name)
     * 
     * Name this jackd instance. If unspecified, this name comes from the
     * $JACK_DEFAULT_SERVER environment variable. It will be "default" if that 
     * is not defined.
     * @var string
     * @access private
     */
    private $name;

    /**
     * -m, --no-mlock
     * 
     * Do not attempt to lock memory, even if realtime is enabled.
     * @var bool
     * @access private
     */
    private $no_mlock;

    /**
     * -Z, --nozombies
     * 
     * Prevent JACK from ever kicking out clients because they were too slow. 
     * This cancels the effect any specified timeout value, but JACK and its 
     * clients are still subject to the supervision of the watchdog thread or 
     * its equivalent.
     * @var bool
     * @access private
     */
    private $nozombies;

    /**
     * -p, --port-max ( int )
     * 
     * Set the maximum number of ports the JACK server can manage. The default 
     * value is 256.
     * @var int
     * @access private
     */
    private $port_max;

    /**
     * ex.: /usr/bin/jackd
     * 
     * Path the jackd server binary.
     * @var string
     * @access private
     */
    private $program_path;

    /**
     * -R, --realtime
     * 
     * Use realtime scheduling (default = true). This is needed for reliable
     * low-latency performance. On many systems, it requires jackd to run with 
     * special scheduler and memory allocation privileges, which may be obtained 
     * in several ways.
     * @var bool
     * @access private
     */
    private $realtime;

    /**
     * -P, --realtime-priority ( int )
     * 
     * When running in realtime mode, set the scheduler priority to an int value
     * between 1-99.
     * @var int
     * @access private
     */
    private $realtime_priority;

    /**
     * --replace-registry
     * 
     * Remove the shared memory registry used by all JACK server instances 
     * before startup. This should rarely be used, and is intended only for 
     * occasions when the structure of this registry changes in ways that are 
     * incompatible across JACK versions (which is rare).
     * @var bool
     * @access private
     */
    private $replace_registry;

    /**
     * --silent
     * 
     * Silence any output during operation.
     * @var bool
     * @access private
     */
    private $silent;

    /**
     * -T, --temporary
     * 
     * Exit once all clients have closed their connections.
     * @var bool
     * @access private
     */
    private $temporary;

    /**
     * -t, --timeout ( int )
     * 
     * Set client timeout limit in milliseconds. The default is 500 msec. In 
     * realtime mode the client timeout must be smaller than the watchdog 
     * timeout (5000 msec).
     * @var int
     * @access private
     */
    private $timeout;

    /**
     * -u, --unlock
     * 
     * Unlock libraries GTK+, QT, FLTK, Wine.
     * @var bool
     * @access private
     */
    private $unlock;


    /**
     * 
     *
     * @param JackServerConfig config
     * @return JackServer
     * @access public
     */
    public function __construct(JackServerConfig $config) 
    {
        $this->clocksource = $config->clocksource;
        $this->driver = $config->driver;
        $this->name = $config->name;
        $this->no_mlock = $config->no_mlock;
        $this->nozombies = $config->nozombies;
        $this->port_max = $config->port_max;
        $this->program_path = $config->program_path;
        $this->realtime = $config->realtime;
        $this->realtime_priority = $config->realtime_priority;
        $this->replace_registry = $config->replace_registry;
        $this->silent = $config->silent;
        $this->temporary = $config->temporary;
        $this->timeout = $config->timeout;
        $this->unlock = $config->unlock;
        if (strtolower($this->driver) === 'alsa') {
            $this->backend = new Alsa($config->backend);
        }else {
            $this->backend = new Dummy($config->backend);
        }
    } 

    /**
     * string toString ( )
     * 
     * Render this model to single line command that can be executed on the 
     * console.
     * @return string
     * @access public
     */
    public function __toString() : string 
    {
        $str = "{$this->program_path} ";
        $str .= $this->clocksource !== null ? "-c {$this->clocksource} " : null;
        $str .= $this->name !== null ? "-n \"{$this->name}\" " : null;
        $str .= $this->no_mlock !== null ? "-m " : null;
        $str .= $this->nozombies !== null ? "-Z " : null;
        $str .= $this->port_max !== null ? "-p {$this->port_max} " : null;
        $str .= $this->realtime !== null ? "-R " : null;
        $str .= $this->realtime_priority !== null ? "-P {$this->realtime_priority} " : null;
        $str .= $this->replace_registry !== null ? "--replace-registry " : null;
        $str .= $this->silent !== null ? "--silent " : null;
        $str .= $this->temporary !== null ? "-T " : null;
        $str .= $this->timeout !== null ? "-t {$this->timeout} " : null;
        $str .= $this->unlock !== null ? "-u " : null;
        $str .= "-d {$this->driver} ";
        $str .= $this->backend->__toString();
        return $str;
    } 
} 
