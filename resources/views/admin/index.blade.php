@extends('layouts.master')

@section('title')
  Dashboard
@endsection

@section('css')
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Dashboards
    @endslot
    @slot('title')
      Dashboard
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="row">
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('Total PO Bus')</p>
                  <h4 class="mb-0">{{ $data['totalAirline'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                    <span class="avatar-title">
                      <i class='bx bx-bus font-size-24'></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('Total Bus')</p>
                  <h4 class="mb-0">{{ $data['totalPlane'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bxs-bus font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <div class="flex-grow-1">
                    <p class="text-muted fw-medium">@lang('Total Terminal')</p>
                    <h4 class="mb-0">{{ $data['totalAirport'] }}</h4>
                  </div>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-buildings font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end row -->
    </div>
  </div>
  <!-- end row -->

  <div class="row">
    <div class="col-xl-12">
      <div class="row">
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('Total Perjalanan')</p>
                  <h4 class="mb-0">{{ $data['totalFlight'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bxs-plane-alt font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('translation.dashboard.total_ticket')</p>
                  <h4 class="mb-0">{{ $data['totalTicket'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                    <span class="avatar-title">
                      <i class='bx bx-purchase-tag-alt font-size-24'></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('translation.dashboard.total_customer')</p>
                  <h4 class="mb-0">{{ $data['totalCustomer'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-user font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end row -->
    </div>
  </div>
  <!-- end row -->

  {{-- chart1 --}}
  <div class="row">
    <div class="col-xl-6">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title mb-4">@lang('Total PO Aktif')</h4>
              <div class="table-responsive">
                <table class="table-nowrap mb-0 table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th class="align-middle">#</th>
                      <th class="align-middle">PO Bus</th>
                      <th class="align-middle">@lang('translation.airline.code')</th>
                      <th class="align-middle">@lang('Jumlah Perjalanan')</th>
                      <th class="align-middle">@lang('Jumlah Armada Bus')</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($data['activeAirlines'] as $airline)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-info fw-bold">{{ ucfirst($airline->name) }}</td>
                        <td>{{ ucfirst($airline->code) }}</td>
                        <td><span class="badge badge-pill badge-soft-warning font-size-13">{{ ucfirst($airline->flights_count) }}</span></td>
                        <td><span class="badge badge-pill badge-soft-success font-size-13">{{ ucfirst($airline->planes_count) }}</span></td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="10" class="text-center">@lang('translation.emptyTable')</td>
                      </tr>
                    @endforelse

                  </tbody>
                </table>
              </div>
              <!-- end table-responsive -->
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end col -->

    <div class="col-xl-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">@lang('Status Perjalanan')</h4>
          <div class="row text-center">
            @forelse ($data['flightStatusChart'] as $flight)
              <div class="col-2">
                <h5 class="mb-0">{{ $flight['total'] }}</h5>
                <p class="text-muted text-truncate">{{ $flight['status'] ? __('Siap Jalan') : __('Perpal/Perawatan') }}</p>
              </div>
            @empty
              <div class="col-2">
                <p class="text-muted text-truncate">@lang('translation.no_data')</p>
              </div>
            @endforelse
          </div>
          <canvas id="flightStatusChart" height="200"></canvas>
        </div>
      </div>
    </div> <!-- end col -->
  </div> <!-- end row -->

  {{-- last 10 flights --}}
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">@lang('Perjalanan terbaru')</h4>
          <div class="table-responsive">
            <table class="table-nowrap mb-0 table align-middle">
              <thead class="table-light">
                <tr>
                  <th class="align-middle">#</th>
                  <th class="align-middle">@lang('Nomor Perjalanan')</th>
                  <th class="align-middle">@lang('Bus')</th>
                  <th class="align-middle">@lang('Terminal Keberangkatan')</th>
                  <th class="align-middle">@lang('Terminal Tujuan')</th>
                  <th class="align-middle">@lang('Jadwal Keberangkatan')</th>
                  <th class="align-middle">@lang('Jadwal Tiba')</th>
                  <th class="align-middle">@lang('Harga')</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($data['lastFlights'] as $flight)
                  <tr>
                    <td><a href="javascript: void(0);" class="text-body fw-bold">#{{ $loop->iteration }}</a> </td>
                    <td class="text-info fw-bold">{{ ucfirst($flight->flight_number) }}</td>
                    <td>{{ ucfirst($flight->airline->name) }}</td>
                    <td><span class="badge badge-pill badge-soft-info font-size-13">{{ ucfirst($flight->origin->name) }}</span></td>
                    <td><span class="badge badge-pill badge-soft-success font-size-13">{{ ucfirst($flight->destination->name) }}</span></td>
                    <td>{{ formatDateWithTimezone($flight->departure) }}</td>
                    <td>{{ formatDateWithTimezone($flight->arrival) }}</td>
                    <td class="text-primary fw-bold">{{ formatPrice($flight->price) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="10" class="text-center">@lang('translation.emptyTable')</td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>
          <!-- end table-responsive -->
        </div>
      </div>
    </div>
  </div>
  <!-- end row -->
@endsection
@section('script')
  <!-- Chart JS -->
  <script src="{{ URL::asset('/assets/libs/chart-js/chart-js.min.js') }}"></script>
  <!-- Magnific Popup-->
  <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

  {{-- 
  @if ($data['expenseChart'])
    {!! $data['expenseChart']->renderJs() !!}
  @endif --}}

  <script>
    // light box init
    $(".productImageLightBox").magnificPopup({
      type: "image",
      closeOnContentClick: !0,
      closeBtnInside: !1,
      fixedContentPos: !0,
      mainClass: "mfp-no-margins mfp-with-zoom",
      image: {
        verticalFit: !0
      },
      zoom: {
        enabled: !0,
        duration: 300
      }
    });

    // order status chart
    let flightStatusChart = @json($data['flightStatusChart']);
    let flightStatusLabel = [];
    let flightStatusData = [];
    let flightStatusColor = [];

    flightStatusChart.forEach(item => {
      flightStatusLabel.push(item.label);
      flightStatusData.push(item.total);
      flightStatusColor.push(item.color);
    });

    ! function(l) {
      "use strict";

      function r() {}

      r.prototype.respChart = function(r, o, e, a) {
        Chart.defaults.global.defaultFontColor = "#8791af",
          Chart.defaults.scale.gridLines.color = "rgba(166, 176, 207, 0.1)";
        var t = r.get(0).getContext("2d"),
          n = l(r).parent();

        function i() {
          r.attr("width", l(n).width());

          switch (o) {
            case "Line":
              new Chart(t, {
                type: "line",
                data: e,
                options: a
              });
              break;

            case "Doughnut":
              new Chart(t, {
                type: "doughnut",
                data: e,
                options: a
              });
              break;

            case "Pie":
              new Chart(t, {
                type: "pie",
                data: e,
                options: a
              });
              break;

            case "Bar":
              new Chart(t, {
                type: "bar",
                data: e,
                options: a
              });
              break;
          }
        }

        l(window).resize(i), i();
      }, r.prototype.init = function() {
        // order payment chart
        this.respChart(l("#flightStatusChart"), "Doughnut", {
        //   labels: ["Take Off", "Landing", "Canceled"],
          labels: ["Berdinas", "Perpal"],
          datasets: [{
            data: flightStatusData,
            backgroundColor: flightStatusColor,
            hoverBackgroundColor: flightStatusColor,
            hoverBorderColor: "#fff"
          }]
        });
      }, l.ChartJs = new r(), l.ChartJs.Constructor = r;
    }(window.jQuery),
    function() {
      "use strict";

      window.jQuery.ChartJs.init();
    }();
  </script>
@endsection
