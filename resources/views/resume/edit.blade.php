<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Resume - {{ $nickname }}</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    .edit-page {
      background: url("{{ asset('assets/bg.png') }}") no-repeat center center;
      background-size: cover;
      background-attachment: fixed;
      min-height: 100vh;
      padding: 100px 20px 40px;
    }
    .edit-container {
      max-width: 1200px;
      margin: 0 auto;
      background: rgba(0, 0, 0, 0.85);
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
    .edit-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #ff0000;
    }
    .edit-header h1 {
      font-size: 2em;
      color: #ff0000;
      margin: 0;
      font-weight: 700;
    }
    .view-public-btn {
      padding: 12px 24px;
      background-color: #ff0000;
      color: #fff;
      text-decoration: none;
      border-radius: 25px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 14px;
    }
    .view-public-btn:hover {
      background-color: #fff;
      color: #ff0000;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #fff;
      font-size: 14px;
    }
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 6px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      box-sizing: border-box;
      background: #111;
      color: #fff;
    }
    .form-group input::placeholder,
    .form-group textarea::placeholder {
      color: #ccc;
    }
    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .btn-primary {
      padding: 15px 40px;
      background: #ff0000;
      color: #fff;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .btn-primary:hover {
      background: #fff;
      color: #ff0000;
    }
    .btn-add {
      padding: 10px 20px;
      background: #ff0000;
      color: #fff;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 14px;
      margin-top: 10px;
      transition: all 0.3s;
    }
    .btn-add:hover {
      background: #fff;
      color: #ff0000;
    }
    .btn-remove {
      padding: 6px 16px;
      background: rgba(255, 0, 0, 0.2);
      color: #ff0000;
      border: 1px solid #ff0000;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
      transition: all 0.3s;
    }
    .btn-remove:hover {
      background: #ff0000;
      color: #fff;
    }
    .success-message {
      background: rgba(255, 0, 0, 0.2);
      color: #fff;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
      text-align: center;
      border: 1px solid #ff0000;
    }
    .section-divider {
      margin: 40px 0 20px;
      padding-top: 20px;
      border-top: 2px solid #444;
    }
    .section-divider h2 {
      color: #ff0000;
      font-size: 1.5em;
      margin-bottom: 20px;
      font-weight: 600;
      border-bottom: 2px solid #fff;
      padding-bottom: 6px;
      display: inline-block;
    }
    .note {
      background: rgba(255, 200, 0, 0.15);
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      border-left: 4px solid #ffc107;
      color: #ffd54f;
      font-size: 14px;
    }
    .repeater-item {
      background: rgba(255, 255, 255, 0.05);
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 15px;
      border: 1px solid #444;
    }
    .repeater-item-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    .repeater-item-header strong {
      color: #fff;
      font-size: 16px;
    }
    .interest-item {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
      align-items: center;
    }
    .interest-item input {
      flex: 1;
    }
    .leadership-org {
      background: rgba(255, 255, 255, 0.05);
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 15px;
      border: 1px solid #ff0000;
    }
    .leadership-org input[type="text"]:first-child {
      font-weight: 600;
      margin-bottom: 10px;
    }
    .role-item {
      display: flex;
      gap: 10px;
      margin-bottom: 8px;
      margin-left: 20px;
      align-items: center;
    }
    .role-item input {
      flex: 1;
    }
    .info-text {
      margin-top: 30px;
      text-align: center;
      color: #ccc;
      font-size: 14px;
      font-style: italic;
    }
    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      .edit-header {
        flex-direction: column;
        gap: 15px;
      }
    }
  </style>
</head>
<body class="edit-page">

  <!-- Header -->
  <section class='header'>
    <div class='name-bubble'>
      <img src='{{ asset('assets/logo.png') }}' alt='Logo' class='logo-icon'>
      {{ $nickname }} Vael
    </div>
    
    <div class='auth-buttons'>
      <a href="{{ route('resume.public', ['id' => 1]) }}" class='auth-btn login-btn' target="_blank">View Public</a>
    </div>
  </section>

  <div class="edit-container">
    <div class="edit-header">
      <h1>📝 EDIT RESUME</h1>
      <a href="{{ route('resume.public', ['id' => 1]) }}" class="view-public-btn" target="_blank">👁️ View Public Resume</a>
    </div>

    <div class="note">
      <strong>Note:</strong> You are editing the shared resume (ID: 1). All logged-in users edit the same resume, and changes will be visible on the public page.
    </div>

    @if (session('success'))
      <div class="success-message">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('resume.update') }}">
      @csrf

      <!-- Basic Information -->
      <div class="section-divider">
        <h2>BASIC INFORMATION</h2>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="name">Full Name *</label>
          <input type="text" id="name" name="name" value="{{ old('name', $name) }}" required>
        </div>

        <div class="form-group">
          <label for="nickname">Nickname *</label>
          <input type="text" id="nickname" name="nickname" value="{{ old('nickname', $nickname) }}" required>
        </div>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="title">Title/Position *</label>
          <input type="text" id="title" name="title" value="{{ old('title', $title) }}" required>
        </div>

        <div class="form-group">
          <label for="university">University *</label>
          <input type="text" id="university" name="university" value="{{ old('university', $university) }}" required>
        </div>
      </div>

      <div class="form-group">
        <label for="description">Description *</label>
        <textarea id="description" name="description" required>{{ old('description', $description) }}</textarea>
      </div>

      <!-- Contact Information -->
      <div class="section-divider">
        <h2>CONTACT INFORMATION</h2>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone *</label>
          <input type="text" id="phone" name="phone" value="{{ old('phone', $phone) }}" required>
        </div>
      </div>

      <div class="form-group">
        <label for="address">Address *</label>
        <input type="text" id="address" name="address" value="{{ old('address', $address) }}" required>
      </div>

      <!-- Personal Info -->
      <div class="section-divider">
        <h2>PERSONAL INFORMATION</h2>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <input type="text" id="dob" name="personal_info[Date of Birth]" value="{{ old('personal_info.Date of Birth', $personalInfo['Date of Birth'] ?? '') }}">
        </div>

        <div class="form-group">
          <label for="pob">Place of Birth</label>
          <input type="text" id="pob" name="personal_info[Place of Birth]" value="{{ old('personal_info.Place of Birth', $personalInfo['Place of Birth'] ?? '') }}">
        </div>

        <div class="form-group">
          <label for="civil">Civil Status</label>
          <input type="text" id="civil" name="personal_info[Civil Status]" value="{{ old('personal_info.Civil Status', $personalInfo['Civil Status'] ?? '') }}">
        </div>

        <div class="form-group">
          <label for="citizen">Citizenship</label>
          <input type="text" id="citizen" name="personal_info[Citizenship]" value="{{ old('personal_info.Citizenship', $personalInfo['Citizenship'] ?? '') }}">
        </div>
      </div>

      <!-- Education -->
      <div class="section-divider">
        <h2>EDUCATION</h2>
      </div>

      <div id="education-container">
        @foreach($education as $index => $edu)
          <div class="repeater-item education-item">
            <div class="repeater-item-header">
              <strong>Education #{{ $index + 1 }}</strong>
              <button type="button" class="btn-remove" onclick="removeEducation(this)">Remove</button>
            </div>
            <div class="form-group">
              <label>School Name</label>
              <input type="text" name="education[{{ $index }}][0]" value="{{ old('education.' . $index . '.0', $edu[0] ?? '') }}">
            </div>
            <div class="form-group">
              <label>Year Graduated / Details</label>
              <input type="text" name="education[{{ $index }}][1]" value="{{ old('education.' . $index . '.1', $edu[1] ?? '') }}">
            </div>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-add" onclick="addEducation()">+ Add Education</button>

      <!-- Field of Interest -->
      <div class="section-divider">
        <h2>FIELD OF INTEREST</h2>
      </div>

      <div id="interests-container">
        @foreach($interests as $index => $interest)
          <div class="interest-item">
            <input type="text" name="interests[]" value="{{ old('interests.' . $index, $interest) }}">
            <button type="button" class="btn-remove" onclick="removeInterest(this)">Remove</button>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-add" onclick="addInterest()">+ Add Interest</button>

      <!-- Leadership Affiliations -->
      <div class="section-divider">
        <h2>LEADERSHIP AFFILIATIONS</h2>
      </div>

      <div id="leadership-container">
        @foreach($leadership as $org => $roles)
          <div class="leadership-org">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <label style="margin: 0; color: #ff0000; font-weight: 700;">Organization Name</label>
              <button type="button" class="btn-remove" onclick="removeLeadership(this)">Remove Org</button>
            </div>
            <input type="text" class="org-name" name="leadership_orgs[]" value="{{ old('leadership_orgs.' . $loop->index, $org) }}">
            
            <div class="roles-container" style="margin-top: 15px;">
              <label style="color: #fff; font-weight: 600;">Roles:</label>
              @foreach($roles as $roleIndex => $role)
                <div class="role-item">
                  <input type="text" name="leadership_roles_{{ $loop->parent->index }}[]" value="{{ old('leadership_roles_' . $loop->parent->index . '.' . $roleIndex, $role) }}">
                  <button type="button" class="btn-remove" onclick="removeRole(this)">Remove</button>
                </div>
              @endforeach
              <button type="button" class="btn-add" onclick="addRole(this)" style="margin-left: 20px;">+ Add Role</button>
            </div>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-add" onclick="addLeadership()">+ Add Organization</button>

      <!-- Awards and Recognition -->
      <div class="section-divider">
        <h2>AWARDS AND RECOGNITION</h2>
      </div>

      <div id="awards-container">
        @foreach($awards as $index => $award)
          <div class="repeater-item award-item">
            <div class="repeater-item-header">
              <strong>Award #{{ $index + 1 }}</strong>
              <button type="button" class="btn-remove" onclick="removeAward(this)">Remove</button>
            </div>
            <div class="form-group">
              <label>Award Title</label>
              <input type="text" name="awards[{{ $index }}][0]" value="{{ old('awards.' . $index . '.0', $award[0] ?? '') }}">
            </div>
            <div class="form-group">
              <label>Details</label>
              <input type="text" name="awards[{{ $index }}][1]" value="{{ old('awards.' . $index . '.1', $award[1] ?? '') }}">
            </div>
            <div class="form-group">
              <label>Date</label>
              <input type="text" name="awards[{{ $index }}][2]" value="{{ old('awards.' . $index . '.2', $award[2] ?? '') }}">
            </div>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-add" onclick="addAward()">+ Add Award</button>

      <!-- Projects -->
      <div class="section-divider">
        <h2>PROJECTS</h2>
      </div>

      <div id="projects-container">
        @foreach($projects as $index => $project)
          <div class="repeater-item project-item">
            <div class="repeater-item-header">
              <strong>Project #{{ $index + 1 }}</strong>
              <button type="button" class="btn-remove" onclick="removeProject(this)">Remove</button>
            </div>
            <div class="form-group">
              <label>Project Name</label>
              <input type="text" name="projects[{{ $index }}][0]" value="{{ old('projects.' . $index . '.0', $project[0] ?? '') }}">
            </div>
            <div class="form-group">
              <label>Project URL</label>
              <input type="url" name="projects[{{ $index }}][1]" value="{{ old('projects.' . $index . '.1', $project[1] ?? '') }}">
            </div>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-add" onclick="addProject()">+ Add Project</button>

      <!-- Submit Button -->
      <div style="margin-top: 50px; text-align: center;">
        <button type="submit" class="btn-primary">
          💾 SAVE ALL CHANGES
        </button>
      </div>

      <div class="info-text">
        <p>All changes will be reflected on the public resume page immediately after saving.</p>
      </div>
    </form>
  </div>

  <script>
    let educationIndex = {{ count($education) }};
    let leadershipIndex = {{ count($leadership) }};
    let awardIndex = {{ count($awards) }};
    let projectIndex = {{ count($projects) }};

    function addEducation() {
      const container = document.getElementById('education-container');
      const html = `
        <div class="repeater-item education-item">
          <div class="repeater-item-header">
            <strong>Education #${educationIndex + 1}</strong>
            <button type="button" class="btn-remove" onclick="removeEducation(this)">Remove</button>
          </div>
          <div class="form-group">
            <label>School Name</label>
            <input type="text" name="education[${educationIndex}][0]" value="">
          </div>
          <div class="form-group">
            <label>Year Graduated / Details</label>
            <input type="text" name="education[${educationIndex}][1]" value="">
          </div>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
      educationIndex++;
    }

    function removeEducation(btn) {
      btn.closest('.education-item').remove();
    }

    function addInterest() {
      const container = document.getElementById('interests-container');
      const html = `
        <div class="interest-item">
          <input type="text" name="interests[]" value="">
          <button type="button" class="btn-remove" onclick="removeInterest(this)">Remove</button>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
    }

    function removeInterest(btn) {
      btn.closest('.interest-item').remove();
    }

    function addLeadership() {
      const container = document.getElementById('leadership-container');
      const html = `
        <div class="leadership-org">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <label style="margin: 0; color: #ff0000; font-weight: 700;">Organization Name</label>
            <button type="button" class="btn-remove" onclick="removeLeadership(this)">Remove Org</button>
          </div>
          <input type="text" class="org-name" name="leadership_orgs[]" value="">
          
          <div class="roles-container" style="margin-top: 15px;">
            <label style="color: #fff; font-weight: 600;">Roles:</label>
            <div class="role-item">
              <input type="text" name="leadership_roles_${leadershipIndex}[]" value="">
              <button type="button" class="btn-remove" onclick="removeRole(this)">Remove</button>
            </div>
            <button type="button" class="btn-add" onclick="addRole(this)" style="margin-left: 20px;">+ Add Role</button>
          </div>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
      leadershipIndex++;
    }

    function removeLeadership(btn) {
      btn.closest('.leadership-org').remove();
    }

    function addRole(btn) {
      const rolesContainer = btn.closest('.roles-container');
      const orgDiv = btn.closest('.leadership-org');
      const orgIndex = Array.from(document.querySelectorAll('.leadership-org')).indexOf(orgDiv);
      
      const html = `
        <div class="role-item">
          <input type="text" name="leadership_roles_${orgIndex}[]" value="">
          <button type="button" class="btn-remove" onclick="removeRole(this)">Remove</button>
        </div>
      `;
      btn.insertAdjacentHTML('beforebegin', html);
    }

    function removeRole(btn) {
      btn.closest('.role-item').remove();
    }

    function addAward() {
      const container = document.getElementById('awards-container');
      const html = `
        <div class="repeater-item award-item">
          <div class="repeater-item-header">
            <strong>Award #${awardIndex + 1}</strong>
            <button type="button" class="btn-remove" onclick="removeAward(this)">Remove</button>
          </div>
          <div class="form-group">
            <label>Award Title</label>
            <input type="text" name="awards[${awardIndex}][0]" value="">
          </div>
          <div class="form-group">
            <label>Details</label>
            <input type="text" name="awards[${awardIndex}][1]" value="">
          </div>
          <div class="form-group">
            <label>Date</label>
            <input type="text" name="awards[${awardIndex}][2]" value="">
          </div>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
      awardIndex++;
    }

    function removeAward(btn) {
      btn.closest('.award-item').remove();
    }

    function addProject() {
      const container = document.getElementById('projects-container');
      const html = `
        <div class="repeater-item project-item">
          <div class="repeater-item-header">
            <strong>Project #${projectIndex + 1}</strong>
            <button type="button" class="btn-remove" onclick="removeProject(this)">Remove</button>
          </div>
          <div class="form-group">
            <label>Project Name</label>
            <input type="text" name="projects[${projectIndex}][0]" value="">
          </div>
          <div class="form-group">
            <label>Project URL</label>
            <input type="url" name="projects[${projectIndex}][1]" value="">
          </div>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
      projectIndex++;
    }

    function removeProject(btn) {
      btn.closest('.project-item').remove();
    }
  </script>

</body>
</html>