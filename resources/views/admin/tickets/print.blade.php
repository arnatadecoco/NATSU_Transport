<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $ticket->flight->flight_number }}</title>
    <link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        body { background-color: #f3f4f6; padding: 40px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .ticket-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            border-left: 10px solid #556ee6; /* Warna utama */
        }
        .ticket-header {
            padding: 20px 30px;
            border-bottom: 2px dashed #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .airline-name { font-size: 24px; font-weight: 800; color: #556ee6; text-transform: uppercase; }
        .ticket-body { padding: 30px; }
        .label-text { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .value-text { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px; }
        .flight-route { font-size: 28px; font-weight: bold; color: #333; display: flex; align-items: center; gap: 15px; }
        .flight-time { font-size: 16px; color: #555; background: #f0f2f5; padding: 5px 10px; border-radius: 5px; }
        .status-badge { background: #d1fae5; color: #065f46; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .footer-note { font-size: 11px; color: #999; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }
        
        /* Print Button Hide on Print */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; padding: 0; }
            .ticket-container { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="d-flex justify-content-end mb-3 no-print max-w-800" style="max-width: 800px; margin: auto;">
            <button onclick="window.print()" class="btn btn-primary me-2">
                <i class="mdi mdi-printer"></i> Download PDF / Print
            </button>
            <button onclick="window.close()" class="btn btn-light">Tutup</button>
        </div>

        <div class="ticket-container">
            <div class="ticket-header">
                <div>
                    <div class="airline-name">
                        {{ $ticket->flight->airline->name }} ({{ $ticket->flight->plane->name }})
                    </div>
                    <span class="badge bg-primary">E-Boarding Pass</span>
                </div>
                <div class="text-end">
                    <div class="status-badge">Payment Success</div>
                </div>
            </div>

            <div class="ticket-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="label-text">Rute Perjalanan Bus</div>
                                <div class="flight-route">
                                    {{ $ticket->flight->origin->name }} ({{ $ticket->flight->origin->city->name }})
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    {{ $ticket->flight->destination->name }} ({{ $ticket->flight->destination->city->name }})
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="label-text">Nama Penumpang</div>
                                <div class="value-text">{{ $ticket->user->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="label-text">Nomor Perjalanan</div>
                                <div class="value-text">{{ $ticket->flight->flight_number }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="label-text">Tanggal & Waktu Keberangkatan</div>
                                <div class="value-text">{{ $ticket->flight->departure }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="label-text">Nomor Kursi</div>
                                <div class="value-text text-primary" style="font-size: 24px;">{{ $ticket->seat_number }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-center border-start">
                        <div class="label-text mb-2">Scan untuk Boarding</div>
                        <div id="qrcode" style="display: flex; justify-content: center; margin-bottom: 10px;"></div>
                        
                        <div class="mt-2">
                            <small class="text-muted">Ticket ID: #{{ $ticket->id }}</small>
                        </div>
                    </div>
                </div>

                <div class="footer-note">
                    * Harap tunjukkan QR Code ini kepada petugas saat Boarding.<br>
                    * Boarding dapat dilakukan 1 Jam sebelum keberangkatan.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR Code dari Data Tiket (ID - Nama - Kursi)
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "TICKET-{{ $ticket->id }}-{{ $ticket->user->name }}-SEAT:{{ $ticket->seat_number }}",
            width: 140,
            height: 140,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>

</body>
</html>