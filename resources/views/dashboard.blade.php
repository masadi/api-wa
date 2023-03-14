<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="form">
                        <h1>WhatsApp API QR</h1>
                        <div id="qrcode-container">
                            <img src="./assets/loader.gif" alt="loading" id="qrcode" style="width: 250px;">
                        </div>
                        <div class="card">
                            <div class="title">Panduan</div>
                            <div class="body">
                                <p>
                                    <li>Scan kode QR berikut dengan aplikasi WhatsApp anda, sebagaimana Whatsapp Web
                                        biasanya.</li>
                                    <li>Sesi Whatsapp Web yang aktif akan keluar, diganti dengan server ini.</li>
                                    <li><b>Gunakan dengan bijak.</b></li>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.js" crossorigin="anonymous"></script>
    <script>
        const qrcode = document.getElementById("qrcode");
        //const socket = io();
        const socket = io('http://server.mas-adi.net:8080', {
            transports: ['websocket', 'polling', 'flashsocket']
        });
        socket.emit('connection', 'asd')
        socket.on("opening", t => {
            console.log(t);
        })
        console.log(socket);
        socket.on("qr", src => {
            console.log(src);
            qrcode.setAttribute("src", src);
            qrcode.setAttribute("alt", "qrcode");
        });
        socket.on("qrstatus", src => {
            console.log(src);
            qrcode.setAttribute("src", src);
            qrcode.setAttribute("alt", "loading");
        });
        socket.on("log", log => {
            console.log(log);
        })
    </script>
</x-app-layout>
