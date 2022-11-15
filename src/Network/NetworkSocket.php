<?php
namespace Rejack\Network;

use Rejack\Network\NetworkSocket\NetworkSocketConfig;
use Rejack\Network\NetworkSocket\NetworkSocketEffect;
use Rejack\Network\NetworkSocket\NetworkSocketEffect\EffectConfig;

/**
 * class NetworkSocket
 * This class models the JackTrip program's inputs and operations.
 */
class NetworkSocket
{
    const MODE_P2P_SERVER = 0;
    const MODE_HUB_SERVER = 1;
    const MODE_P2P_CLIENT = 2;
    const MODE_HUB_CLIENT = 3;
    
    const BUFFER_STRATEGY_A = 0;
    const BUFFER_STRATEGY_B = 1;
    const BUFFER_STRATEGY_C = 2;

    const PATCH_SERVER_TO_CLIENTS = 0;
    const PATCH_LOOPBACK = 1;
    const PATCH_FAN_OUT_IN_NOLOOPBACK = 2;
    const PATCH_TUB = 3;
    const PATCH_FULL_MIX = 4;
    const PATCH_NO_AUTO_PATCH = 5;

    const OVERFLOW_LIMITING_INCOMING = 0;
    const OVERFLOW_LIMITING_OUTGOING = 1;
    const OVERFLOW_LIMITING_BIDIRECTIONAL = 2;
    const OVERFLOW_LIMITING_NONE = 3;
    
    /**
     * --appendthreadid
     * 
     * Append the thread (process) ID to client names.
     * @var bool
     * @access private
     */
    private $append_thread_id;

    /**
     * -a. --assumednumclients ( int )
     * 
     * Assumed number of clients (sources) mixing at Hub Server (otherwise, 2 
     * assumed).
     * @var int
     * @access private
     */
    private $assumed_num_clients;

    /**
     * -B, --bindport ( int )
     * 
     * Set only the bindport number (default = 4464).
     * @var int
     * @access private
     */
    private $bind_port;

    /**
     * -b, --bitres ( int )
     * 
     * Set the audio bit rate resolution (default = 16, 32 uses floating-point).
     * @var int
     * @access private
     */
    private $bit_resolution;

    /**
     * --broadcast ( int )
     * 
     * Duplicate receive ports with the specified broadcast queue length. 
     * Broadcast outputs have higher latency but less packet loss.
     * @var int
     * @access private
     */
    private $broadcast;

    /**
     * --bufstrategy ( 0|1|2 )
     * 
     * Use alternative jitter buffer.
     * @var int
     * @access private
     */
    private $buffer_strategy;

    /**
     * -J, --clientname ( string )
     * 
     * Change the client name from its default, JackTrip.
     * @var string
     * @access private
     */
    private $client_name;

    /**
     * -f, --effects ( string )
     * 
     * Turn on incoming and/or outgoing compressor and/or reverb in client. This 
     * attribute holds an array of EffectConfig objects, if any.
     * @var EffectConfig[]
     * @access private
     */
    private $effects;

    /**
     * -p, --hubpatch ( int )
     * 
     * Hub audio patch, only has effect if running in HUB_SERVER mode.
     * 0=server-to-clients, 1=client loopback, 2=client fan out/in but not 
     * loopback, 3=reserved for TUB, 4=full mix, 5=no auto patching. Default is 
     * 0.
     * @var int
     * @access private
     */
    private $hubpatch;

    /**
     * -i, --includeserver
     * 
     * Include audio to and from the server in the mix when patching. Only 
     * affects -p 2 (client fan out/in but not loopback) and -p 4 (full mix) 
     * patch modes.
     * @var bool
     * @access private
     */
    private $include_server_audio;

    /**
     * -j, --jamlink
     * 
     * Run in JamLink mode (connect to a JamLink box).
     * @var bool
     * @access private
     */
    private $jamlink;

    /**
     * -L, --localaddress ( string )
     * 
     * Change the localhost IP address from its default, 127.0.0.1.
     * @var string
     * @access private
     */
    private $local_address;

    /**
     * -l, --loopback
     * 
     * Run in Loop-Back mode.
     * @var bool
     * @access private
     */
    private $loopback;

    /**
     * -s, --server - P2P server mode
     * -c, --client ( peer_hostname|peer_ip_address ) - P2P client mode
     * -S, --jacktripserver - Hub server mode
     * -C, --pingtoserver ( peer_name|peer_ip_address )
     * @var string|null
     * @access private
     */
    private $mode;

    /**
     * -D, --nojackportsconnect
     * 
     * Don't connect default audio ports in JACK.
     * @var bool
     * @access private
     */
    private $no_default_audio_port_connections;

    /**
     * -n, --numchannels ( int )
     * 
     * Number of input and output channels (default = 2).
     * @var int
     * @access private
     */
    private $num_channels;

    /**
     * -O, --overflowlimiting ( i|o[w]|io[w]|n )
     * 
     * Use audio limiter(s) in client. i=incoming from network, o=outgoing to 
     * network, io=both, w=warn if limiting. Default is 'n'.
     * @var int
     * @access private
     */
    private $overflow_limiting;

    /**
     * -P, --peerport ( int )
     * 
     * Set only the peer port number (default = 4464).
     * @var int
     * @access private
     */
    private $peer_port;

    /**
     * -o, --portoffset ( int )
     * 
     * Receiving bind port and peer port offset from default 4464.
     * @var int
     * @access private
     */
    private $port_offset;

    /**
     * The path to the JackTrip program.
     * @var string
     * @access private
     */
    private $program_path;

    /**
     * -q, --queue ( int )
     * 
     * Queue buffer length in packet size (default = 4). The value must be 2 or
     * greater.
     * @var int
     * @access private
     */
    private $queue;

    /**
     * --receivechannels ( int )
     * 
     * Number of channels to receive from the remote host
     * @var int
     * @access private
     */
    private $receive_channels;

    /**
     * -r, --redundancy ( int )
     * 
     * Packet redundancy to avoid glitches with packet losses (default = 1).
     * @var int
     * @access private
     */
    private $redundancy;

    /**
     * The IP address of the user.
     * @var string
     * @access private
     */
    private $remote_ip_address;

    /**
     * -K, --remotename ( string )
     * 
     * Change the remote client name when connecting to a hub server (default = 
     * host's external-facing IP address).
     * @var string
     * @access private
     */
    private $remote_name;

    /**
     * --sendchannels ( int )
     * 
     * Number of channels to send to the remote host.
     * @var int
     * @access private
     */
    private $send_channels;

    /**
     * -t, --timeout
     * 
     * Quit after ten seconds of no activity.
     * @var bool
     * @access private
     */
    private $timeout;

    /**
     * -U, --udpbaseport
     * 
     * Set only the server UDP base port number (default = 61002).
     * @var int
     * @access private
     */
    private $udp_base_port;

    /**
     * --udprt
     * 
     * Use realtime thread priority for network I/O.
     * @var bool
     * @access private
     */
    private $udprt;

    /**
     * -u, --upmix
     * 
     * Upmix mono clients to stereo when patching.
     * @var bool
     * @access private
     */
    private $upmix;

    /**
     * -z, --zerounderrun
     * 
     * Set buffer to zeros when underrun occurs (default = wavetable reliance).
     * @var bool
     * @access private
     */
    private $zero_underrun;


    /**
     * Class constructor
     * @param NetworkSocketConfig config
     * @return NetworkSocket
     * @access public
     */
    public function __construct(NetworkSocketConfig $config) 
    {
        $this->append_thread_id = $config->append_thread_id;
        $this->assumed_num_clients = $config->assumed_num_clients;
        $this->bind_port = $config->bind_port;
        $this->bit_resolution = $config->bit_resolution;
        $this->broadcast = $config->broadcast;
        $this->buffer_strategy = $config->buffer_strategy;
        $this->client_name = $config->client_name ? utf8_encode($config->client_name) : null;
        $this->effects = $config->effects;
        $this->hubpatch = $config->hubpatch;
        $this->include_server_audio = $config->include_server_audio;
        $this->jamlink = $config->jamlink;
        $this->local_address = $config->local_address ? utf8_encode($config->local_address) : null;
        $this->loopback = $config->loopback;
        $this->mode = $config->mode ? utf8_encode($config->mode) : null;
        $this->no_default_audio_port_connections = $config->no_default_audio_port_connections;
        $this->num_channels = $config->num_channels;
        $this->overflow_limiting = $config->overflow_limiting;
        $this->peer_port = $config->peer_port;
        $this->port_offset = $config->port_offset;
        $this->program_path = $config->program_path ? utf8_encode($config->program_path) : null;
        $this->queue = $config->queue;
        $this->receive_channels = $config->receive_channels;
        $this->redundancy = $config->redundancy;
        $this->remote_ip_address = $config->remote_ip_address ? utf8_encode($config->remote_ip_address) : null;
        $this->remote_name = $config->remote_name ? utf8_encode($config->remote_name) : null;
        $this->send_channels = $config->send_channels;
        $this->timeout = $config->timeout;
        $this->udp_base_port = $config->udp_base_port;
        $this->udprt = $config->udprt;
        $this->upmix = $config->upmix;
        $this->zero_underrun = $config->zero_underrun;
    } 

    /**
     * Converts the object's attributes into a single line of code executable as 
     * a command on the host system to launch an instance of JackTrip.
     * @return string
     * @access public
     */
    public function toString() : string
    {
        $cmd = "{$this->program_path} ";

        switch ($this->mode) 
        {
            case NetworkSocket::MODE_P2P_CLIENT:
                $cmd .= "-c {$this->remote_ip_address} ";
                break;
            case NetworkSocket::MODE_HUB_CLIENT:
                $cmd .= "-C {$this->remote_ip_address} ";
                break;
            case NetworkSocket::MODE_P2P_SERVER:
                $cmd .= "-s ";
                break;
            case NetworkSocket::MODE_HUB_SERVER:
                $cmd .= "-S ";
                $cmd .= $this->assumed_num_clients !== null ? "-a {$this->assumed_num_clients} " : null;
                break;
        }

        $cmd .= $this->num_channels !== null ? "-n {$this->num_channels} " : null;
        $cmd .= $this->receive_channels !== null ? "--receivechannels {$this->receive_channels} " : null;
        $cmd .= $this->send_channels !== null ? "--sendchannels {$this->send_channels} " : null;
        $cmd .= $this->queue !== null ? "-q {$this->queue} " : null;
        $cmd .= $this->redundancy !== null ? "-r {$this->redundancy} " : null;
        $cmd .= $this->port_offset !== null ? "-o {$this->port_offset} " : null;
        $cmd .= $this->bind_port !== null ? "-B {$this->bind_port} " : null;
        $cmd .= $this->peer_port !== null ? "-P {$this->peer_port} " : null;
        $cmd .= $this->udp_base_port !== null ? "-U {$this->udp_base_port} " : null;
        $cmd .= $this->bit_resolution !== null ? "-b {$this->bit_resolution} " : null;
        $cmd .= $this->zero_underrun ? "-z " : null;
        $cmd .= $this->timeout ? "-t " : null;
        $cmd .= $this->loopback ? "-l " : null;
        $cmd .= $this->jamlink ? "-j " : null;
        $cmd .= $this->client_name !== null ? "-J {$this->client_name} " : null;
        $cmd .= $this->remote_name !== null ? "-k {$this->remote_name} " : null;
        $cmd .= $this->append_thread_id ? "--appendthreadid " : null;
        $cmd .= $this->local_address !== null ? "-L {$this->local_address} " : null;
        $cmd .= $this->no_default_audio_port_connections ? "-d " : null;

        if ($this->buffer_strategy !== null) 
        {
            switch ($this->buffer_strategy)
            {
                case NetworkSocket::BUFFER_STRATEGY_A:
                    $cmd .= "--bufstrategy 0 ";
                    break;
                case NetworkSocket::BUFFER_STRATEGY_B:
                    $cmd .= "--bufstrategy 1 ";
                    break;
                case NetworkSocket::BUFFER_STRATEGY_C:
                    $cmd .= "--bufstrategy 2 ";
                    break;
            }
        }

        $cmd .= $this->broadcast !== null ? "--broadcast {$this->broadcast} " : null;
        $cmd .= $this->udprt ? "--udprt " : null;

        if ($this->hubpatch !== null) 
        {
            switch ($this->hubpatch) 
            {
                case NetworkSocket::PATCH_SERVER_TO_CLIENTS:
                    $cmd .= "-p 0 ";
                    break;
                case NetworkSocket::PATCH_LOOPBACK:
                    $cmd .= "-p 1 ";
                    break;
                case NetworkSocket::PATCH_FAN_OUT_IN_NOLOOPBACK:
                    $cmd .= "-p 2 ";
                    break;
                case NetworkSocket::PATCH_TUB: 
                    $cmd .= "-p 3 ";
                    break;
                case NetworkSocket::PATCH_FULL_MIX:
                    $cmd .= "-p 4 ";
                    break;
                case NetworkSocket::PATCH_NO_AUTO_PATCH:
                    $cmd .= "-p 5 ";
                    break;
            }
        }

        $cmd .= $this->include_server_audio ? "-i " : null;
        $cmd .= $this->upmix ? "-u " : null;

        if ($this->overflow_limiting !== null) 
        {
            switch ($this->overflow_limiting) 
            {
                case NetworkSocket::OVERFLOW_LIMITING_INCOMING:
                    $cmd .= "-O i ";
                    break;
                case NetworkSocket::OVERFLOW_LIMITING_OUTGOING:
                    $cmd .= "-O o ";
                    break;
                case NetworkSocket::OVERFLOW_LIMITING_BIDIRECTIONAL:
                    $cmd .= "-O io ";
                    break;
                case NetworkSocket::OVERFLOW_LIMITING_NONE:
                    $cmd .= "-O n ";
                    break;
            }
        }

        if ($this->effects !== null) 
        {
            if (is_array($this->effects))
            {
                foreach ($this->effects as $config) 
                {
                    $effect = new NetworkSocketEffect($config);
                    $cmd .= sprintf("-f %s ", $effect->toString());
                }
            }
        }

        return $cmd;
    } 
} 
