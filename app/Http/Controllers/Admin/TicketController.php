<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airline;
use App\Models\City;
use App\Models\Flight;
use DataTables; 
use Illuminate\Http\Response;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ticket::with(['flight.airline', 'flight.origin', 'flight.destination', 'flight.plane'])
                ->orderByDesc('created_at')
                ->when($request->status, function ($query) use ($request) {
                    switch ($request->status) {
                        case 'menunggu': return $query->where('status', 0);
                        case 'pending': return $query->where('status', 1);
                        case 'approved': return $query->where('status', 2);
                        case 'canceled': return $query->where('status', 3);
                        default: return $query;
                    }
                });

            return Datatables::of($data)->addIndexColumn()
                ->setRowClass(fn ($row) => 'align-middle')
                ->addColumn('action', function ($row) {
                    // Karena sudah Auto-ACC, Admin sebenarnya gak perlu tombol ini lagi
                    // Tapi kita biarkan saja buat jaga-jaga kalau ada case khusus
                    if($row->status == 1) {
                         return '
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-success" onclick="updateStatus('.$row->id.', \'approve\')">ACC</button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="updateStatus('.$row->id.', \'reject\')">Tolak</button>
                            </div>
                         ';
                    }
                    return '-';
                })
                ->editColumn('flight_info', function ($row) {
                    return '<b>' . $row->flight->airline->name . '</b><br>' . $row->flight->flight_number;
                })
                ->editColumn('route', function ($row) {
                    return $row->flight->origin->name . ' -> ' . $row->flight->destination->name;
                })
                ->editColumn('time', function ($row) {
                    return 'Berangkat: ' . $row->flight->departure;
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->flight->price, 0, ',', '.');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) return '<span class="badge bg-warning">Menunggu Bayar</span>';
                    if ($row->status == 1) return '<span class="badge bg-info">Butuh Verifikasi</span>';
                    if ($row->status == 2) return '<span class="badge bg-success">Valid / ACC</span>';
                    return '<span class="badge bg-danger">Batal</span>';
                })
                ->rawColumns(['flight_info', 'status', 'action'])
                ->make(true);
        }

        $cities = City::pluck('name', 'id')->toArray();
        $airlines = Airline::pluck('name', 'id');
        return view('admin.tickets.index', compact('cities', 'airlines'));
    }

    public function showFlights(Request $request)
    {
        if ($request->ajax()) {
            $data = Flight::with(['airline', 'origin', 'destination', 'plane'])->where('remain_seats', '>', 0);
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button data-id="' . $row->id . '" class="btn btn-primary btn-sm book-btn">Pesan</button>';
                })
                ->editColumn('flight_info', function ($row) {
                    return $row->airline->name . ' (' . $row->flight_number . ')';
                })
                ->editColumn('route', function ($row) {
                    return $row->origin->name . ' - ' . $row->destination->name;
                })
                ->editColumn('time', function ($row) {
                    return $row->departure;
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('capacity', function ($row) {
                    return $row->remain_seats . ' Kursi';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $cities = City::pluck('name', 'id')->toArray();
        $airlines = Airline::pluck('name', 'id');
        return view('admin.tickets.booking', compact('cities', 'airlines'));
    }

    public function userTickets(Request $request)
    {
        if ($request->ajax()) {
            $data = Ticket::with(['flight.airline', 'flight.origin', 'flight.destination'])
                ->whereUserId(auth()->id())
                ->orderByDesc('created_at');

            return Datatables::of($data)->addIndexColumn()
                ->setRowClass(fn ($row) => 'align-middle')
                ->addColumn('action', function ($row) {
                    $btn = '';
                    
                    // [1] STATUS 0 (Menunggu Bayar): Muncul Tombol BAYAR
                    if ($row->status == 0) { 
                        $urlBayar = route('tickets.pay', $row->id);
                        $btn .= '<a href="' . $urlBayar . '" target="_blank" class="btn btn-success btn-sm d-block w-100 mb-2">Bayar</a>';
                    }
                    
                    // [2] STATUS 0 & 1: Muncul Tombol BATAL
                    if ($row->status == 0 || $row->status == 1) {
                        $urlBatal = route('tickets.destroy', $row->id);
                        $csrf = csrf_field();
                        $method = method_field('DELETE');
                        
                        $btn .= '
                            <form action="'.$urlBatal.'" method="POST" onsubmit="return confirm(\'Yakin mau batalin tiket ini?\')">
                                '.$csrf.'
                                '.$method.'
                                <button type="submit" class="btn btn-outline-danger btn-sm d-block w-100">
                                    Batal
                                </button>
                            </form>
                        ';
                    }

                    // [3] STATUS 2 (LUNAS): Muncul Tombol CEK TIKET
                    if ($row->status == 2) {
                        $urlPrint = route('tickets.print', $row->id);
                        $btn .= '<a href="' . $urlPrint . '" target="_blank" class="btn btn-info btn-sm d-block w-100"><i class="mdi mdi-ticket-confirmation me-1"></i> Cek Tiket</a>';
                    }

                    return $btn;
                })
                ->editColumn('flight_info', function ($row) {
                    return '<b>' . $row->flight->airline->name . '</b><br><small>' . $row->flight->flight_number . '</small>';
                })
                ->editColumn('route', function ($row) {
                    return $row->flight->origin->name . ' ke ' . $row->flight->destination->name;
                })
                ->editColumn('time', function ($row) {
                    return function_exists('formatDateWithTimezone') 
                        ? formatDateWithTimezone($row->flight->departure) 
                        : $row->flight->departure;
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format(optional($row->flight)->price, 0, ',', '.');
                })
                ->editColumn("status", function ($row) {
                    if ($row->status == 0) return '<span class="badge badge-pill badge-soft-warning font-size-14 p-2">Menunggu Pembayaran</span>';
                    if ($row->status == 1) return '<span class="badge badge-pill badge-soft-primary font-size-14 p-2">Sedang Diproses</span>';
                    if ($row->status == 2) return '<span class="badge badge-pill badge-soft-success font-size-14 p-2">Tiket Terbit</span>';
                    return '<span class="badge badge-pill badge-soft-danger font-size-14 p-2">Dibatalkan</span>';
                })
                ->rawColumns(['flight_info', 'action', 'status'])
                ->make(true);
        }

        $cities = City::pluck('name', 'id')->toArray();
        $airlines = Airline::pluck('name', 'id');
        return view('admin.tickets.user-tickets', compact('cities', 'airlines'));
    }

    public function book(Request $request)
    {
        $flightId = $request->input('id') ?? $request->input('flight_id');
        if (!$flightId) return response()->json(['message' => 'Gagal: ID Penerbangan tidak ditemukan.'], 422);

        $flight = Flight::find($flightId);
        if (!$flight || $flight->remain_seats < 1) return response()->json(['message' => 'Kursi penuh'], 400);

        Ticket::create([
            'user_id' => auth()->id(),
            'flight_id' => $flight->id,
            'seat_number' => rand(1, 40),
            'status' => 0, 
        ]);

        $flight->decrement('remain_seats');
        return response()->json(['message' => 'Tiket berhasil dipesan!']);
    }

    public function pay($id)
    {
         $ticket = Ticket::with('flight.airline', 'flight.origin', 'flight.destination')->where('user_id', auth()->id())->findOrFail($id);
         if($ticket->status != 0) {
             return redirect()->route('tickets.userTickets')->with('error', 'Tiket ini sudah dibayar atau dibatalkan.');
         }
         return view('admin.tickets.payment', compact('ticket')); 
    }

    // === FUNCTION AUTO VERIFY (LANGSUNG TIKET JADI) ===
    public function processPayment(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required',
            'payment_method' => 'required',
            'payer_name' => 'required',
        ]);

        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($request->ticket_id);

        // [LOGIKA AUTO ACC]
        // Langsung ubah status ke 2 (Approved/Valid)
        // Bypass proses admin
        $ticket->update([
            'status' => 2 
        ]);

        return redirect()->route('tickets.userTickets')->with('success', 'Pembayaran Berhasil! Tiket Anda sudah terbit. Silakan Cek Tiket.');
    }
    // ==================================================

    public function printTicket($id)
    {
        $ticket = Ticket::with(['flight.airline', 'flight.origin', 'flight.destination', 'user'])
                    ->where('user_id', auth()->id())
                    ->findOrFail($id);
        
        // Hanya tiket status 2 (Lunas) yang bisa diprint
        if($ticket->status != 2) {
            abort(403, 'Tiket belum terbit atau belum lunas.');
        }

        return view('admin.tickets.print', compact('ticket'));
    }

    public function changeStatus(Request $request, Ticket $ticket)
    {
        $status = ($request->status == 'approve') ? 2 : 3;
        $ticket->update(['status' => $status]);
        
        if ($status == 3 && $ticket->flight) {
            $ticket->flight->increment('remain_seats');
        }
        return response()->json(['message' => 'Status berhasil diubah']);
    }

    public function destroy($id)
    {
        $ticket = Ticket::where('user_id', auth()->id())->findOrFail($id);
        if ($ticket->flight) $ticket->flight->increment('remain_seats');
        $ticket->update(['status' => 3]); 
        return back()->with('success', 'Tiket berhasil dibatalkan');
    }
}