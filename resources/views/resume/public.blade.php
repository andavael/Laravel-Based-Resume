<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $nickname ?: 'Resume' }} - Resume</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">                                                 
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <section class='header'>
    <div class='name-bubble'>
      <img src='{{ asset('assets/logo.png') }}' alt='Logo' class='logo-icon'>
      {{ $nickname ?: 'Resume' }} Vael
    </div>
    
    <div class='auth-buttons'>
      <a href="{{ route('login') }}" class='auth-btn login-btn'>Login</a>
    </div>
</section>

  @if($resumeId)
    <section class='hero'>
      <div class='container'>
        <div class='hero-text'>
          <h2>Hello, I'm {{ $nickname }}!</h2>
          <h3>{{ $name }}</h3>
          <h1 class='highlight'>{{ $title }}</h1>
          <p>{{ $description }}</p>
          <div class='buttons'>
            <a href='#resume' class='btn'>Resume</a>
            <a href='#contact' class='btn'>Contact</a>
          </div>
        </div>

        <div class='hero-image'>
          <div class='circle-bg'></div>
          <div class='circle-small'></div>
          <div class='circle-smaller'></div>
          <img src='{{ asset('assets/Anda.png') }}' alt='{{ $nickname }} Vael'>
        </div>
      </div>
    </section>

    <section id='resume'>
      <div class='wrapper'>

        <header>
          <h1><span class='name-line'>{{ strtoupper($name) }}</span></h1>
          <h3>Bachelor of Science in Computer Science Student</h3>
        </header>

        <div class='columns'>

          <div class='column-box'>
            <h2>PERSONAL INFO</h2>
            @if(count($personalInfo) > 0)
              @foreach($personalInfo as $key => $value)
                <p>{{ $key }}: <span>{{ $value }}</span></p>
              @endforeach
            @else
              <p><em>No personal information available</em></p>
            @endif

            <h2>EDUCATION</h2>
            @if(count($education) > 0)
              @foreach($education as $edu)
                @if(!empty($edu[0]))
                  <p><strong>{{ $edu[0] }}</strong><br><em>{{ $edu[1] }}</em></p>
                @endif
              @endforeach
            @else
              <p><em>No education records available</em></p>
            @endif

            <h2>LEADERSHIP AFFILIATIONS</h2>
            @if(count($leadership) > 0)
              @foreach($leadership as $org => $roles)
                <p><strong>{{ $org }}</strong></p>
                <ul>
                  @foreach($roles as $role)
                    <li>{{ $role }}</li>
                  @endforeach
                </ul>
              @endforeach
            @else
              <p><em>No leadership affiliations available</em></p>
            @endif
          </div>

          <div class='column-box'>
            <h2>FIELD OF INTEREST</h2>
            @if(count($interests) > 0)
              <ul>
                @foreach($interests as $interest)
                  @if(!empty($interest))
                    <li>{{ $interest }}</li>
                  @endif
                @endforeach
              </ul>
            @else
              <p><em>No interests listed</em></p>
            @endif

            <h2>AWARDS AND RECOGNITION</h2>
            @if(count($awards) > 0)
              @foreach($awards as $award)
                @if(!empty($award[0]))
                  <p><strong>{{ $award[0] }}</strong><br>{{ $award[1] }} <br><em>{{ $award[2] }}</em></p>
                @endif
              @endforeach
            @else
              <p><em>No awards available</em></p>
            @endif

            <h2>PROJECTS</h2>
            @if(count($projects) > 0)
              @foreach($projects as $proj)
                @if(!empty($proj[0]))
                  <a href='{{ $proj[1] }}' class='project-btn' target='_blank'>{{ $proj[0] }}</a>
                @endif
              @endforeach
            @else
              <p><em>No projects available</em></p>
            @endif
          </div>
        </div>

        <footer id='contact'>
          <p><strong>{{ strtoupper($name) }}</strong></p>
          @if($email)
            <p>Email: {{ $email }}</p>
          @endif
          @if($phone)
            <p>Phone: {{ $phone }}</p>
          @endif
          @if($address)
            <p>{{ $address }}</p>
          @endif
        </footer>
      </div>
    </section>
  @else
    {{-- No resume exists yet --}}
    <section class='hero' style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
      <div class='container' style="text-align: center;">
        <div class='hero-text'>
          <h1 class='highlight'>No Resume Available Yet</h1>
          <p style="font-size: 1.2rem; margin: 20px 0;">Be the first to create your resume!</p>
          <div class='buttons'>
            <a href='{{ route('register') }}' class='btn'>Register</a>
            <a href='{{ route('login') }}' class='btn'>Login</a>
          </div>
        </div>
      </div>
    </section>
  @endif

</body>
</html>