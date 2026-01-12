@extends('layouts.master')

@section('title')
  Pesanan Saya
@endsection

@section('css')
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Tiket Saya
    @endslot
    @slot('li_2')
      {{ route('tickets.userTickets') }}
    @endslot
    @slot('title')
      Tiket Saya
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            {{-- Filter Section --}}
            <div class="col-lg-3">
              <div class="mb-3">
                <label for="formrow-inputCity" class="form-label">@lang('Terminal Keberangkatan')</label>
                <select class="form-select select2 filter-input" id="origin" name="origin" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($cities as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="mb-3">
                <label for="formrow-inputCity" class="form-label">@lang('Terminal Tujuan')</label>
                <select class="form-select select2 filter-input" id="destination" name="destination" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($cities as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="mb-3">
                <label>@lang('PO Bus')</label>
                <select class="form-select select2 filter-input" id="airline" name="airline" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($airlines as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>

            <div class="col-lg-3">
              <div class="mb-3">
                <label>@lang('Tanggal Keberangkatan')</label>
                <div class="input-daterange input-group" id="datepicker" data-date-format="yyyy-m-d" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker'>
                  <input type="text" class="form-control filter-input" id="departure" name="departure" placeholder="{{ __('translation.flight.departure') }}" onchange="dateChanged()" />
                  <input type="text" class="form-control filter-input" id="arrival" name="arrival" placeholder="{{ __('translation.flight.arrival') }}" onchange="dateChanged()" />
                </div>
              </div>
            </div>
          </div>

          <div>
            <button class="btn btn-light w-sm" onclick="reset()">@lang('buttons.reset')</button>
          </div>

        </div>
      </div>
    </div> </div> <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-end mb-4" id="action_btns">
          </div>
          <table id="datatable" class="table-hover table-bordered nowrap w-100 table">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th> Nomor Perjalanan</th>
                <th>Rute</th>
                <th>Waktu</th>
                <th>Harga</th>
                <th>Nomor Kursi</th>
                <th>Status</th>
                <th> @lang('translation.actions')</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

        </div>
      </div>
    </div> </div> @endsection

@section('script')
  <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
  {{-- bootstrap-datepicker --}}
  <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>


  {{-- datatable init --}}
  <script type="text/javascript">
    let table;

    //datepicker Init
    $("#datepicker").datepicker();

    function dateChanged() {
      table.draw();
    }

    function reset() {
      $('.filter-input').val('').trigger('change')
      table.draw();
    }

    $(function() {
      table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthChange: true,
        lengthMenu: [10, 20, 50, 100],
        pageLength: 10,
        scrollX: true,
        
        // [FIX UTAMA] Kosongkan order default biar gak error sorting DT_RowIndex
        order: [], 

        // text transalations
        language: {
          search: "@lang('translation.search')",
          lengthMenu: "@lang('translation.lengthMenu1') _MENU_ @lang('translation.lengthMenu2')",
          processing: "@lang('translation.processing')",
          info: "@lang('translation.infoShowing') _START_ @lang('translation.infoTo') _END_ @lang('translation.infoOf') _TOTAL_ @lang('translation.infoEntries')",
          emptyTable: "@lang('translation.emptyTable')",
          paginate: {
            "first": "@lang('translation.paginateFirst')",
            "last": "@lang('translation.paginateLast')",
            "next": "@lang('translation.paginateNext')",
            "previous": "@lang('translation.paginatePrevious')"
          },
        },
        ajax: {
          url: "{{ route('tickets.userTickets') }}",
          method: "GET",
          data: function(d) {
            d.origin = $("#origin").find(":selected").val()
            d.destination = $("#destination").find(":selected").val();
            d.airline = $("#airline").find(":selected").val();
            d.departure = $("#departure").val();
            d.arrival = $("#arrival").val();
          }
        },
        columnDefs: [{
          className: "text-center",
          targets: [5, 6]
        }],
        columns: [{
            // [FIX KEDUA] Matikan orderable & searchable buat kolom nomor urut
            data: 'DT_RowIndex', 
            name: 'DT_RowIndex',
            orderable: false, 
            searchable: false
          },
          {
            data: 'flight_info',
            searchable: false,
          },
          {
            data: 'route',
            searchable: false,
          },
          {
            data: 'time',
            searchable: false,
          },
          {
            data: 'price', name: 'price',
            searchable: false,
          },
          {
            data: 'seat_number'
          },
          {
            data: 'status'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false
          },
        ],
      })

      // select dropdown for change the page length
      $('.dataTables_length select').addClass('form-select form-select-sm');

      // add margin top to the pagination and info 
      $('.dataTables_info, .dataTables_paginate').addClass('mt-3');
    });
  </script>

  <script>
    $(document).on('click', '.cancel-btn', function(e) {
      e.preventDefault();
      const id = $(this).data('id');

      // send ajax request to the server
      Swal.fire({
        title: "{{ __('messages.are_you_sure') }}",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "{{ __('buttons.yes_cancel') }}",
        cancelButtonText: "{{ __('buttons.close') }}",
        customClass: {
          confirmButton: 'btn btn-success mt-2',
          cancelButton: 'btn btn-light ms-2 mt-2'
        },
        buttonsStyling: !1
      }).then(function(willCancel) {
        if (willCancel.isConfirmed) {
          // send ajax request to cancel the ticket, pass ticket id 

          $.ajax({
            url: "{{ route('tickets.destroy', '') }}/" + id,
            method: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              _method: "DELETE",
              id: id
            },
            success: function(data) {
              Swal.fire({
                timer: "2000",
                text: "Tiket berhasil dibatalkan",
                icon: "success"
              })
              $('#datatable').DataTable().ajax.reload();
            },
            error: function(data) {
              Swal.fire({
                timer: "4000",
                title: "Gagal",
                text: "Terjadi kesalahan saat membatalkan",
                icon: "error",
              });
            }
          })

        }
      });
    })
  </script>
@endsection