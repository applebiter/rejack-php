# rejack-php

JACK server relay built as a CakePHP 4 plugin.

Rejack is a minimally viable Web-based sound studio application that leverages
existing FOSS command-line programs commonly found in Linux software
repositories to implement eight core subsystems:

-   JACK Server
-   Remote Connection
-   Patchbay
-   JACK Transport
-   Record
-   Play
-   Broadcast
-   Rejack API

## JACK Server

-   Configures the server.
-   Starts and stops the JACK server.
-   Reports the process ID of the server.
-   Reports the server status.
-   Reports sample rate and buffer period size.
-   Returns current clients in an array of objects or structs.
-   Returns an individual client by name.
-   Audio Ports are categorized as either "sources" or "sinks".
-   Clients contain an arbitrary number of audio ports.
-   Optionally, the JACK server's console output is logged.

## Remote Connection

-   Uses JackTrip for remote network connections over UDP and TCP.
-   Network ports on the host system are managed.
-   The manager opens and closes firewall ports on an as-needed basis.
-   Optionally, an audio compressor and reverb can be applied to incoming and/or outgoing channels
-   User sends and received one or more channels.
-   The number of channels sent need not match the number received.
-   Rejack sends instructions for the user to connect using JackTrip.
-   Optionally, JackTrip's console output is logged.

## Patchbay

-   All of the audio ports available on the JACK bus can be connected.
-   Ports are categorized as either sources or sinks.
-   Sink ports may connect to one or more source ports.
-   Source ports may connect to one or more sink ports.
-   Connection state can be named, saved as a preset, and restored in a
    subsequent session.

## JACK Transport

-   The JACK transport can be stopped and started.
-   The transport can seek to a specific frame.
-   The transport can set the tempo.
-   The JACK server can be made the timebase master.
-   The JACK server can be made to release the timebase.

## Record

-   User selects how many channels the output should have.
-   User matches available source audio ports to channels.
-   User selects audio file format, the format may have further settings.
-   User determines whether recording is timed or open-ended.
-   User may stop recording in progress.
-   User may insert meta tag data if the format supports it.
-   Any console messages from the jack_capture application are logged.
-   Recording program is JACK transport-aware.

## Play

-   Load and play many audio file formats.
-   The player is JACK transport-aware, and thus seekable.
-   More than one track can be armed to start playing simultaneously.
-   Players and recorders can be armed to start simultaneously.

## Broadcast

-   Selected source audio ports can be sent to an Icecast server.
-   The audio stream will be encoded on-the-fly to Ogg format.
-   Metadata can be set into the outgoing Ogg stream.
-   The service can also use as input playlists of recorded audio.
-   The service can use as input a command-line program that returns the path to
    an audio file, and it will call upon the program for a new file at the end
    of each audio file's playback.

## Rejack API

The Rejack API offers a single interface for the combined functionality of the
Rejack components.

## Requirements

-   PHP >=7.4
-   The command-line programs used are found in the Ubuntu repos and I daresay
    are available in most other major Linux repos, too. The programs are:
    -   jack_bufsize
    -   jack_capture
    -   jack_connect
    -   jack_disconnect
    -   jack_lsp
    -   jack_samplerate
    -   jack-stdout
    -   jack_thru
    -   jack_transport
    -   jack-play
    -   jackd
    -   jacktrip
    -   ices2
    -   lame
    -   flac & metaflac
    -   vorbis-tools
-   The web server must have permission to control the JACK server, and my
    solution has been to run the apache2 webserver as my user account. This is
    probably not the "right" way to do things.
-   The web server must have permission to issue commands to the system
    firewall, and my solution has been to add a line to the sudoers file
    providing the necessary permission. This, also, is probably not the right
    way to go about it. However, this seems adequate as I'm only using Rejack on a private network in the first place.

## New Feature Ideas (in no particular order)

-   Rack Component - Load/unload LV2 and VST filters
-   Logging Component
-   ReJACK-to-ReJACK Component
