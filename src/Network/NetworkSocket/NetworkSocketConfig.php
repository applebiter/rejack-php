<?php
namespace Rejack\Network\NetworkSocket;

use Rejack\Network\NetworkSocket\NetworkSocketEffect;


/**
 * class NetworkSocketConfig
 * This class models the inputs required by the NetworkSocket class upon 
 * initialization.
 */
class NetworkSocketConfig
{
    /**
     * --appendthreadid
     * 
     * Append the thread (process) ID to client names.
     * @var bool
     * @access public
     */
    public $append_thread_id = null;

    /**
     * -a. --assumednumclients ( int )
     * 
     * Assumed number of clients (sources) mixing at Hub Server (otherwise, 2 
     * assumed).
     * @var int
     * @access public
     */
    public $assumed_num_clients = null;

    /**
     * -B, --bindport ( int )
     * 
     * Set only the bindport number (default = 4464).
     * @var int
     * @access public
     */
    public $bind_port = null;

    /**
     * -b, --bitres ( int )
     * 
     * Set the audio bit rate resolution (default = 16, 32 uses floating-point).
     * @var int
     * @access public
     */
    public $bit_resolution = null;

    /**
     * --broadcast ( int )
     * 
     * Duplicate receive ports with the specified broadcast queue length. 
     * Broadcast outputs have higher latency but less packet loss.
     * @var int
     * @access public
     */
    public $broadcast = null;

    /**
     * --bufstrategy ( 0|1|2 )
     * 
     * Use alternative jitter buffer.
     * @var int
     * @access public
     */
    public $buffer_strategy = null;

    /**
     * -J, --clientname ( string )
     * 
     * Change the client name from its default, JackTrip.
     * @var string
     * @access public
     */
    public $client_name = null;

    /**
     * -f, --effects ( string )
     * 
     * Turn on incoming and/or outgoing compressor and/or reverb in client. This 
     * attribute holds an array of EffectConfig objects if any are configured.
     * @var EffectConfig[]
     * @access public
     */
    public $effects = null;

    /**
     * -p, --hubpatch ( int )
     * 
     * Hub audio patch, only has effect if running in HUB_SERVER mode.
     * 0=server-to-clients, 1=client loopback, 2=client fan out/in but not 
     * loopback, 3=reserved for TUB, 4=full mix, 5=no auto patching. Default is 
     * 0.
     * @var int
     * @access public
     */
    public $hubpatch = null;

    /**
     * -i, --includeserver
     * 
     * Include audio to and from the server in the mix when patching. Only 
     * affects -p 2 (client fan out/in but not loopback) and -p 4 (full mix) 
     * patch modes.
     * @var bool
     * @access public
     */
    public $include_server_audio = null;

    /**
     * -j, --jamlink
     * 
     * Run in JamLink mode (connect to a JamLink box).
     * @var bool
     * @access public
     */
    public $jamlink = null;

    /**
     * -L, --localaddress ( string )
     * 
     * Change the localhost IP address from its default, 127.0.0.1.
     * @var string
     * @access public
     */
    public $local_address = null;

    /**
     * -l, --loopback
     * 
     * Run in Loop-Back mode.
     * @var bool
     * @access public
     */
    public $loopback = null;

    /**
     * -s, --server - P2P server mode
     * -c, --client ( peer_hostname|peer_ip_address ) - P2P client mode
     * -S, --jacktripserver - Hub server mode
     * -C, --pingtoserver ( peer_name|peer_ip_address )
     * @var string
     * @access public
     */
    public $mode = null;

    /**
     * -D, --nojackportsconnect
     * 
     * Don't connect default audio ports in JACK.
     * @var bool
     * @access public
     */
    public $no_default_audio_port_connections = null;

    /**
     * -n, --numchannels ( int )
     * 
     * Number of input and output channels (default = 2).
     * @var int
     * @access public
     */
    public $num_channels = null;

    /**
     * -O, --overflowlimiting ( i|o[w]|io{w]|n )
     * 
     * Use audio limiter(s) in client. i=incoming from network, o=outgoing to 
     * network, io=both, w=warn if limiting. Default is 'n'.
     * @var int
     * @access public
     */
    public $overflow_limiting = null;

    /**
     * -P, --peerport ( int )
     * 
     * Set only the peer port number (default = 4464).
     * @var int
     * @access public
     */
    public $peer_port = null;

    /**
     * -o, --portoffset ( int )
     * 
     * Receiving bind port and peer port offset from default 4464.
     * @var int
     * @access public
     */
    public $port_offset = null;

    /**
     * The path to the JackTrip program.
     * @var string
     * @access public
     */
    public $program_path = null;

    /**
     * -q, --queue ( int )
     * 
     * Queue buffer length in packet size (default = 4). The value must be 2 or
     * greater.
     * @var int
     * @access public
     */
    public $queue = null;

    /**
     * --receivechannels ( int )
     * 
     * Number of channels to receive from the remote host
     * @var int
     * @access public
     */
    public $receive_channels = null;

    /**
     * -r, --redundancy ( int )
     * 
     * Packet redundancy to avoid glitches with packet losses (default = 1).
     * @var int
     * @access public
     */
    public $redundancy = null;

    /**
     * The IP address of the user.
     * @var string
     * @access public
     */
    public $remote_ip_address = null;

    /**
     * -K, --remotename ( string )
     * 
     * Change the remote client name when connecting to a hub server (default = host's
     * external-facing IP address).
     * @var string
     * @access public
     */
    public $remote_name = null;

    /**
     * --sendchannels ( int )
     * 
     * Number of channels to send to the remote host.
     * @var int
     * @access public
     */
    public $send_channels = null;

    /**
     * -t, --timeout
     * 
     * Quit after ten seconds of no activity.
     * @var bool
     * @access public
     */
    public $timeout = null;

    /**
     * -U, --udpbaseport
     * 
     * Set only the server UDP base port number (default = 61002).
     * @var int
     * @access public
     */
    public $udp_base_port = null;

    /**
     * --udprt
     * 
     * Use realtime thread priority for network I/O.
     * @var bool
     * @access public
     */
    public $udprt = null;

    /**
     * -u, --upmix
     * 
     * Upmix mono clients to stereo when patching.
     * @var bool
     * @access public
     */
    public $upmix = null;

    /**
     * -z, --zerounderrun
     * 
     * Set buffer to zeros when underrun occurs (default = wavetable reliance).
     * @var bool
     * @access public
     */
    public $zero_underrun = null;
} 
