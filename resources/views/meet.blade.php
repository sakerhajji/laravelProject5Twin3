@extends('layouts.app')

@section('title', 'Video Meeting')

@section('content')
<div class="container py-5 text-center">
    <h2>Join Your Video Meeting</h2>
    <p>Room: <strong>{{ $roomName }}</strong></p>

    <!-- Embed Jitsi -->
    <div id="jitsi-container" style="height: 600px;"></div>

    <script src="https://meet.jit.si/external_api.js"></script>
    <script>
        const domain = "meet.jit.si";
        const options = {
            roomName: "{{ $roomName }}",
            width: "100%",
            height: 600,
            parentNode: document.querySelector("#jitsi-container"),
            configOverwrite: { startWithAudioMuted: true },
            interfaceConfigOverwrite: { DEFAULT_REMOTE_DISPLAY_NAME: 'Guest' }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    </script>
</div>
@endsection
