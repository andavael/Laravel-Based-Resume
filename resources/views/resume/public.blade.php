<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $nickname }} - Resume</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">                                                 
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <section class='header'>
    <div class='name-bubble'>
      <img src='{{ asset('assets/logo.png') }}' alt='Logo' class='logo-icon'>
      {{ $nickname }} Vael
    </div>
    
    <div class='auth-buttons'>
      <a href="{{ route('login') }}" class='auth-btn login-btn'>Login</a>
    </div>
</section>

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
          @foreach($personalInfo as $key => $value)
            <p>{{ $key }}: <span>{{ $value }}</span></p>
          @endforeach

          <h2>EDUCATION</h2>
          @foreach($education as $edu)
            <p><strong>{{ $edu[0] }}</strong><br><em>{{ $edu[1] }}</em></p>
          @endforeach

          <h2>LEADERSHIP AFFILIATIONS</h2>
          @foreach($leadership as $org => $roles)
            <p><strong>{{ $org }}</strong></p>
            <ul>
              @foreach($roles as $role)
                <li>{{ $role }}</li>
              @endforeach
            </ul>
          @endforeach
        </div>

        <div class='column-box'>
          <h2>FIELD OF INTEREST</h2>
          <ul>
            @foreach($interests as $interest)
              <li>{{ $interest }}</li>
            @endforeach
          </ul>

          <h2>AWARDS AND RECOGNITION</h2>
          @foreach($awards as $award)
            <p><strong>{{ $award[0] }}</strong><br>{{ $award[1] }} <br><em>{{ $award[2] }}</em></p>
          @endforeach

          <h2>PROJECTS</h2>
          @foreach($projects as $proj)
            <a href='{{ $proj[1] }}' class='project-btn' target='_blank'>{{ $proj[0] }}</a>
          @endforeach
        </div>
      </div>

      <a href='{{ asset('assets/Anda.pdf') }}' class='download-btn' download>⬇ Download Resume</a>

      <footer id='contact'>
        <p><strong>{{ strtoupper($name) }}</strong></p>
        <p>Email: {{ $email }}</p>
        <p>Phone: {{ $phone }}</p>
        <p>{{ $address }}</p>
      </footer>
    </div>
  </section>

</body>
</html>