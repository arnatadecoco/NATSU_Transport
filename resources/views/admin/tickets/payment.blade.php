@extends('layouts.master')

@section('title') Pembayaran Tiket @endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="my-0 text-white"><i class="mdi mdi-ticket-account me-2"></i>Form Pembayaran</h5>
                </div>
                <div class="card-body">
                    
                    {{-- DETAIL TIKET --}}
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <div class="font-size-24 me-3"><i class="mdi mdi-airplane"></i></div>
                        <div>
                            <h5 class="alert-heading font-size-16 mb-1">{{ $ticket->flight->airline->name }} ({{ $ticket->flight->flight_number }})</h5>
                            <p class="mb-0">
                                {{ $ticket->flight->origin->name }} <i class="mdi mdi-arrow-right mx-1"></i> {{ $ticket->flight->destination->name }}<br>
                                Jadwal: {{ $ticket->flight->departure }}
                            </p>
                        </div>
                        <div class="ms-auto text-end">
                            <h4 class="text-danger fw-bold mb-0">Rp {{ number_format($ticket->flight->price, 0, ',', '.') }}</h4>
                            <span class="badge badge-soft-warning">Menunggu Pembayaran</span>
                        </div>
                    </div>

                    <form action="{{ route('tickets.processPayment') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                        {{-- 1. BIODATA PEMBAYAR --}}
                        <h5 class="font-size-14 mb-3 text-uppercase border-bottom pb-2"><i class="mdi mdi-account-details me-2"></i>Data Penumpang</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="payer_name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control bg-light" value="{{ auth()->user()->email }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" class="form-control bg-light" value="{{ auth()->user()->phone ?? '-' }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Kursi</label>
                                <input type="text" class="form-control bg-light" value="{{ $ticket->seat_number }}" readonly>
                            </div>
                        </div>

                        {{-- 2. METODE PEMBAYARAN --}}
                        <h5 class="font-size-14 mb-3 text-uppercase border-bottom pb-2"><i class="mdi mdi-wallet me-2"></i>Pilih Metode Pembayaran</h5>
                        
                        <div class="accordion" id="paymentAccordion">
                            
                            {{-- OPSI 1: QRIS --}}
                            <div class="accordion-item border rounded mb-2">
                                <h2 class="accordion-header" id="headingQris">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseQris">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="method_qris" value="QRIS" required>
                                            <label class="form-check-label fw-bold cursor-pointer" for="method_qris">
                                                QRIS (Scan Barcode)
                                            </label>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseQris" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body text-center bg-light">
                                        <p class="mb-2">Scan QR Code di bawah ini:</p>
                                        {{-- CONTOH QR --}}
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS" class="img-thumbnail" style="width: 150px;">
                                        <p class="mt-2 text-muted small">Support: GoPay, OVO, Dana, ShopeePay, Mobile Banking</p>
                                    </div>
                                </div>
                            </div>

                            {{-- OPSI 2: TRANSFER BANK --}}
                            <div class="accordion-item border rounded mb-2">
                                <h2 class="accordion-header" id="headingBank">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBank">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="method_bank" value="BANK">
                                            <label class="form-check-label fw-bold cursor-pointer" for="method_bank">
                                                Transfer Bank (Manual)
                                            </label>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseBank" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body bg-light">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="card card-body border shadow-none mb-0">
                                                    <h6 class="text-primary mb-1">BCA</h6>
                                                    <h5 class="mb-0">123-456-7890</h5>
                                                    <small>a.n PT Tiket Bus</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="card card-body border shadow-none mb-0">
                                                    <h6 class="text-primary mb-1">MANDIRI</h6>
                                                    <h5 class="mb-0">987-000-1111</h5>
                                                    <small>a.n PT Tiket Bus</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="form-label">Upload Bukti Transfer (Opsional)</label>
                                            <input type="file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- OPSI 3: TUNAI --}}
                            <div class="accordion-item border rounded mb-2">
                                <h2 class="accordion-header" id="headingTunai">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTunai">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="method_tunai" value="TUNAI">
                                            <label class="form-check-label fw-bold cursor-pointer" for="method_tunai">
                                                Tunai / Cash (Di Loket)
                                            </label>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseTunai" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                    <div class="accordion-body bg-light">
                                        <div class="alert alert-warning mb-0">
                                            Silakan tunjukkan <strong>Kode Booking: #{{ $ticket->id }}</strong> ke petugas Agen PO Bus  di Terminal untuk melakukan pembayaran tunai.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg font-size-18">
                                <i class="mdi mdi-check-circle-outline me-1"></i> Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script otomatis buka accordion saat radio button dipilih
    document.querySelectorAll('input[name="payment_method"]').forEach((elem) => {
        elem.addEventListener("change", function(event) {
            var targetId = "";
            if(this.value === "QRIS") targetId = "#collapseQris";
            if(this.value === "BANK") targetId = "#collapseBank";
            if(this.value === "TUNAI") targetId = "#collapseTunai";
            
            // Tutup semua
            document.querySelectorAll('.accordion-collapse').forEach(el => el.classList.remove('show'));
            // Buka yang dipilih
            if(targetId) document.querySelector(targetId).classList.add('show');
        });
    });
</script>
@endsection