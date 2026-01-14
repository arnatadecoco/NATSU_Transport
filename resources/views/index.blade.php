<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  {{-- BARIS DI BAWAH INI ADALAH OBATNYA --}}
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
  <title>NATSU TRANSPORT</title>
</head>

<body>
  <div class="conatiner">
    <section class="front-page">
      <img class="hero" src="{{ asset('frontend/assets/hero.png') }}" alt="meditation" autoplay />
      <video muted autoplay loop class="hero" src="{{ asset('frontend/assets/video.mp4') }}"></video>
      <nav>
        <div class="logo">
          <img src="{{ asset('frontend/assets/logosaya.png') }}" alt="mind & body" style="width: 10rem" />
          {{-- <h1>SULAYMANIYAH INTERNATIONAL AIRPORT</h1> --}}
        </div>
        <div class="links">
          @auth
            @if (Auth::user()->is_admin)
              <a href="{{ route('root') }}">Dashbaord</a>
            @else
              <a href="{{ route('root') }}">Dashbaord</a>
              <a href="{{ route('tickets.flights') }}">Book a Flight</a>
              <a href="{{ route('tickets.userTickets') }}">My Booking</a>
            @endif
            <a href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 me-1 text-danger align-middle"></i> @lang('translation.Logout')</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          @else
            <a href="{{ route('login') }}">Login</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}">Register</a>
            @endif
          @endauth
        </div>
        <svg width="44" height="18" viewBox="0 0 44 18" fill="none" xmlns="http://www.w3.org/2000/svg">
          <line class="line" y1="1" x2="44" y2="1" stroke="white" stroke-width="2" />
          <line class="line" y1="9" x2="27" y2="9" stroke="white" stroke-width="2" />
          <line class="line" y1="17" x2="11" y2="17" stroke="white" stroke-width="2" />
        </svg>

      </nav>
      <div class="primary-overlay">
        <div class="selling-point">
          <h2>PESAN BUS MUDAH, AMAN, DAN NYAMAN DENGAN KITA</h2>
          <h3>
            Menghubungkan ke seluruh Negeri, Melayani dengan sepenuh hati
          </h3>
          <div class="ctas">
            @auth
              <button class="cta-main">
                @if (Auth::user()->is_admin)
                  <a href="{{ route('root') }}">Dashboard</a>
                @else
                  <a href="{{ route('tickets.flights') }}">Book A Flight</a>
                @endif
              </button>
            @else
              <button class="cta-main">
                <a href="{{ route('tickets.flights') }}">Pesan Tiketmu Sekarang</a>
              </button>
              <button class="cta-sec">
                <a href="{{ route('register') }}">Daftarkan Dirimu</a>
              </button>
            @endauth
          </div>
        </div>
      </div>
    </section>


    <section class="classes">
      <div class="classes-description">
        <h2>Tentang Perusahan Bus Kami</h2>
        <h3>Menghubungkan ke Seluruh Negeri, Melayani dengan sepenuh hati</h3>
      </div>
      <div class="image">
        <div>
          <h3>Pilates</h3>
          <img class="image" src="{{ asset('frontend/assets/64a5b6896f27e.jpeg') }}" alt="Pilates">
        </div>
        <div>
          <h3>Yoga</h3>
          <img class="image" src="{{ asset('frontend/assets/2275232940.jpg') }}" alt="Yoga">
        </div>
        <div>
          <h3>Meditation</h3>
          <img class="image" src="{{ asset('frontend/assets/busworld-mbtech-7.jpg') }}" alt="Meditation">
        </div>
        <div>
          <h3>Meditation</h3>
          <img class="image" src="{{ asset('frontend/assets/pexels-photo-33069936.webp') }}" alt="Meditation">
        </div>
        
      </div>
    </section>
    <section class="about">
      <div class="our-story">
        <h2>Tentang Kami</h2>
        <p>
          Kami adalah perusahaan otobus yang berdedikasi menghubungkan negeri dari kota besar hingga pelosok nusantara, 
          menghadirkan perjalanan yang aman, nyaman, dan tepat waktu melalui armada yang terawat 
          serta layanan ramah yang selalu mengutamakan kepuasan penumpang; dengan semangat untuk terus berkembang, 
          kami berkomitmen memberikan pengalaman perjalanan terbaik bagi setiap orang yang mempercayakan perjalanannya kepada kami.
        </p>
      </div>
      <img src="{{ asset('frontend/assets/bus-volvo.jpg') }}" alt="our-story" />
    </section>

  </div>

  <footer>
    <div>
      <p>©
        <script>
          document.write(new Date().getFullYear())
        </script> {{ config('app.name') }}. Dibuat dengan cinta oleh kami
      </p>
    </div>
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/ScrollTrigger.min.js"></script>
  <script src="{{ asset('frontend/script.js') }}"></script>
</body>

</html>