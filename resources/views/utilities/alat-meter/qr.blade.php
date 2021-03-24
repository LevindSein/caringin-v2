<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Print QR Code</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Aplikasi PT. Pengelola Pusat Perdagangan Caringin di Kota Bandung">
        <meta name="author" content="">
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
        <script src="{{asset('argon/vendor/jquery/dist/jquery.min.js')}}"></script>
        
        <!-- Custom fonts for this template -->
        <link
            href="{{asset('https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i')}}"
            rel="stylesheet">
        
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="{{asset('argon/css/argon.css')}}" type="text/css">

        <link rel="icon" href="{{asset('img/logo.png')}}">
    </head>

    <body id="page-top">
        <div style="text-align:center">
            <h1>Print QR Code Alat {{$fasilitas}} untuk {{$kontrol}}</h1>
            <hr />
            <button type="button" class="btn btn-primary" onclick="print();" id="printqr">Print</button><br><br>
            <span id="spanqr"></span>
        </div>

        <script src="{{asset('js/printmanager/zip.js')}}"></script>
        <script src="{{asset('js/printmanager/zip-ext.js')}}"></script>
        <script src="{{asset('js/printmanager/deflate.js')}}"></script>
        <script src="{{asset('js/printmanager/JSPrintManager.js')}}"></script>

        <script>
            //WebSocket settings
            JSPM.JSPrintManager.auto_reconnect = true;
            JSPM.JSPrintManager.start();

            //Check JSPM WebSocket status
            function jspmWSStatus() {
                if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
                    return true;
                else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
                    alert('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
                    return false;
                }
                else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
                    alert('JSPM has blocked this website!');
                    return false;
                }
            }

            //Do printing...
            function print() {
                buttonPrint = $('#printqr');
                statusPrint = $('#spanqr');
                buttonPrint.text('Printing . . .');
                if (jspmWSStatus()) {
                    statusPrint.text('');
                    //Create a ClientPrintJob
                    var cpj = new JSPM.ClientPrintJob();
                    //Set Printer type (Refer to the help, there many of them!)
                    cpj.clientPrinter = new JSPM.InstalledPrinter('Feeling');
                    //Set content to print...
                    //Create Zebra ZPL commands for sample label
                    var cmds =  "^XA";
                    cmds += "^FO150,50^ABB,30,18^BQN,2,10^FDQA {{$kode}} ^FS";
                    cmds += "^FO110,50^ABB,25,12^FD {{$fasilitas}} ^FS";
                    cmds += "^FO70,50^ABB,25,12^FD {{$kontrol}} ^FS";
                    cmds += "^XZ";
                    
                    cpj.printerCommands = cmds;
                    //Send print job to printer!
                    cpj.sendToClient();
                    window.close();
                }
                else{
                    buttonPrint.text('Print');
                    statusPrint.text('Printer Belum Siap, Klik lagi');
                }
            }
        
        </script>
    </body>
</html>