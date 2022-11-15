<?php 
$rejack_root = ROOT . DS . "plugins" . DS . "rejack-php";
return [
    "Rejack" => [  
        "" => "",
        "Theme" => [
            "themefile" => $rejack_root . "/storage/theme" 
        ],
        "Server" => [
            "console_log" => $rejack_root . "/logs/server.log",
            "jack_config" => [
                "backend" => [
                    "device" => "hw:A96",
                    "duplex" => true,
                    "midi_driver" => "seq",
                    "period" => 256,
                    "rate" => 48000
                ],
                "driver" => "alsa",
                "program_path" => "/usr/bin/jackd",
                "realtime" => true
            ],
            "jack_bufsize_path" => "/usr/bin/jack_bufsize",
            "jack_lsp_path" => "/usr/bin/jack_lsp",
            "jack_samplerate_path" => "/usr/bin/jack_samplerate",
        ],
        "Patchbay" => [
            "presets_dir" => $rejack_root . "/storage/patchbay",
            "jack_connect_path" => "/usr/bin/jack_connect",
            "jack_disconnect_path" => "/usr/bin/jack_disconnect",
        ],
    ]
];