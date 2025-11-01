<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Resume - {{ $nickname }}</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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

    @if ($errors->any())
      <div class="error-message">
        <strong>Please fix the following errors:</strong>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('resume.update') }}">
      @csrf

      <!-- Basic Information -->
      <div class="section-divider">
        <h2>BASIC INFORMATION</h2>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="name">Full Name * <span class="char-count">(Max: 100 characters)</span></label>
          <input type="text" id="name" name="name" value="{{ old('name', $name) }}" required maxlength="100">
        </div>

        <div class="form-group">
          <label for="nickname">Nickname * <span class="char-count">(Max: 50 characters)</span></label>
          <input type="text" id="nickname" name="nickname" value="{{ old('nickname', $nickname) }}" required maxlength="50">
        </div>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="title">Title/Position * <span class="char-count">(Max: 100 characters)</span></label>
          <input type="text" id="title" name="title" value="{{ old('title', $title) }}" required maxlength="100">
        </div>

        <div class="form-group">
          <label for="university">University * <span class="char-count">(Max: 200 characters)</span></label>
          <input type="text" id="university" name="university" value="{{ old('university', $university) }}" required maxlength="200">
        </div>
      </div>

      <div class="form-group">
        <label for="description">Description * <span class="char-count">(Max: 1000 characters)</span></label>
        <textarea id="description" name="description" required maxlength="1000">{{ old('description', $description) }}</textarea>
      </div>

      <!-- Contact Information -->
      <div class="section-divider">
        <h2>CONTACT INFORMATION</h2>
      </div>

      <div class="form-group">
        <label for="email">Email * <span class="char-count">(Max: 100 characters)</span></label>
        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required maxlength="100">
      </div>

      <div class="form-group">
        <label>Phone Numbers * <span class="char-count">(Max: 20 characters each)</span></label>
        <div id="phones-container">
          @foreach($phones as $index => $phone)
            <div class="phone-item">
              <input type="text" name="phones[]" value="{{ old('phones.' . $index, $phone) }}" required maxlength="20" placeholder="+63 9XX XXX XXXX">
              @if($index > 0)
                <button type="button" class="btn-remove" onclick="removePhone(this)">Remove</button>
              @endif
            </div>
          @endforeach
        </div>
        <button type="button" class="btn-add" onclick="addPhone()">+ Add Another Phone</button>
      </div>

      <div class="form-group">
        <label>Complete Address *</label>
        <div class="address-grid">
          <div class="form-group">
            <label for="house">House/Building Number * <span class="char-count">(Max: 100)</span></label>
            <input type="text" id="house" name="address[house]" value="{{ old('address.house', $address['house'] ?? '') }}" required maxlength="100" placeholder="e.g., Purok 7">
          </div>
          
          <div class="form-group">
            <label for="barangay">Barangay * <span class="char-count">(Max: 100)</span></label>
            <input type="text" id="barangay" name="address[barangay]" value="{{ old('address.barangay', $address['barangay'] ?? '') }}" required maxlength="100" placeholder="e.g., Bolbok">
          </div>

          <div class="form-group">
            <label for="city">City/Municipality * <span class="char-count">(Max: 100)</span></label>
            <input type="text" id="city" name="address[city]" value="{{ old('address.city', $address['city'] ?? '') }}" required maxlength="100" placeholder="e.g., Batangas City">
          </div>

          <div class="form-group">
            <label for="province">Province * <span class="char-count">(Max: 100)</span></label>
            <input type="text" id="province" name="address[province]" value="{{ old('address.province', $address['province'] ?? '') }}" required maxlength="100" placeholder="e.g., Batangas">
          </div>

          <div class="form-group">
            <label for="zip">ZIP Code * <span class="char-count">(Max: 10)</span></label>
            <input type="text" id="zip" name="address[zip]" value="{{ old('address.zip', $address['zip'] ?? '') }}" required maxlength="10" placeholder="e.g., 4200">
          </div>

          <div class="form-group">
            <label for="country">Country * <span class="char-count">(Max: 100)</span></label>
            <input type="text" id="country" name="address[country]" value="{{ old('address.country', $address['country'] ?? '') }}" required maxlength="100" placeholder="e.g., Philippines">
          </div>
        </div>
        <div class="address-preview">
          <strong>Address Preview:</strong> <span id="address-display"></span>
        </div>
      </div>

      <!-- Personal Info -->
      <div class="section-divider">
        <h2>PERSONAL INFORMATION</h2>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label for="dob">Date of Birth * <span class="char-count">(YYYY-MM-DD)</span></label>
          <input type="date" id="dob" name="personal_info[Date of Birth]" value="{{ old('personal_info.Date of Birth', $personalInfo['Date of Birth'] ?? '') }}" required max="{{ date('Y-m-d') }}">
        </div>

        <div class="form-group">
          <label for="pob">Place of Birth * <span class="char-count">(Max: 100)</span></label>
          <input type="text" id="pob" name="personal_info[Place of Birth]" value="{{ old('personal_info.Place of Birth', $personalInfo['Place of Birth'] ?? '') }}" required maxlength="100">
        </div>

        <div class="form-group">
          <label for="civil">Civil Status *</label>
          <select id="civil" name="personal_info[Civil Status]" required>
            <option value="" disabled selected style="color: #888;">Select Status</option>
            <option value="Single" {{ old('personal_info.Civil Status', $personalInfo['Civil Status'] ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
            <option value="In a relationship" {{ old('personal_info.Civil Status', $personalInfo['Civil Status'] ?? '') == 'In a relationship' ? 'selected' : '' }}>In a relationship</option>
            <option value="Married" {{ old('personal_info.Civil Status', $personalInfo['Civil Status'] ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
            <option value="Widowed" {{ old('personal_info.Civil Status', $personalInfo['Civil Status'] ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
            <option value="Divorced" {{ old('personal_info.Civil Status', $personalInfo['Civil Status'] ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
          </select>
        </div>

        <div class="form-group">
          <label for="citizen">Citizenship * <span class="char-count">(Max: 50)</span></label>
          <input type="text" id="citizen" name="personal_info[Citizenship]" value="{{ old('personal_info.Citizenship', $personalInfo['Citizenship'] ?? '') }}" required maxlength="50">
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
              <label>School Name <span class="char-count">(Max: 200)</span></label>
              <input type="text" name="education[{{ $index }}][0]" value="{{ old('education.' . $index . '.0', $edu[0] ?? '') }}" maxlength="200">
            </div>
            <div class="form-group">
              <label>Year Graduated / Details <span class="char-count">(Max: 100)</span></label>
              <input type="text" name="education[{{ $index }}][1]" value="{{ old('education.' . $index . '.1', $edu[1] ?? '') }}" maxlength="100">
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
            <input type="text" name="interests[]" value="{{ old('interests.' . $index, $interest) }}" maxlength="100">
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
              <label style="margin: 0; color: #ff0000; font-weight: 700;">Organization Name <span class="char-count">(Max: 200)</span></label>
              <button type="button" class="btn-remove" onclick="removeLeadership(this)">Remove Org</button>
            </div>
            <input type="text" class="org-name" name="leadership_orgs[]" value="{{ old('leadership_orgs.' . $loop->index, $org) }}" maxlength="200">
            
            <div class="roles-container" style="margin-top: 15px;">
              <label style="color: #fff; font-weight: 600;">Roles <span class="char-count">(Max: 200 each)</span>:</label>
              @foreach($roles as $roleIndex => $role)
                <div class="role-item">
                  <input type="text" name="leadership_roles_{{ $loop->parent->index }}[]" value="{{ old('leadership_roles_' . $loop->parent->index . '.' . $roleIndex, $role) }}" maxlength="200">
                  <button type="button" class="btn-remove" onclick="removeRole(this)">Remove</button>
                </div>
              @endforeach
              <button type="button" class="btn-add" onclick="addRole(this)" style="margin-left: 20px;">+ Add Role</button>
            </div>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-add" onclick="addLeadership()">+ Add Organization</button>

      <!-- Achievements -->
      <div class="section-divider">
        <h2>ACHIEVEMENTS</h2>
      </div>

      <div id="awards-container">
        @foreach($awards as $index => $award)
          <div class="repeater-item award-item">
            <div class="repeater-item-header">
              <strong>Achievement #{{ $index + 1 }}</strong>
              <button type="button" class="btn-remove" onclick="removeAward(this)">Remove</button>
            </div>
            <div class="form-group">
              <label>Achievement Title <span class="char-count">(Max: 200)</span></label>
              <input type="text" name="awards[{{ $index }}][0]" value="{{ old('awards.' . $index . '.0', $award[0] ?? '') }}" maxlength="200">
            </div>
            <div class="form-group">
              <label>Details <span class="char-count">(Max: 200)</span></label>
              <input type="text" name="awards[{{ $index }}][1]" value="{{ old('awards.' . $index . '.1', $award[1] ?? '') }}" maxlength="200">
            </div>
            <div class="form-group">
              <label>Date <span class="char-count">(Max: 50)</span></label>
              <input type="text" name="awards[{{ $index }}][2]" value="{{ old('awards.' . $index . '.2', $award[2] ?? '') }}" maxlength="50">
            </div>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-add" onclick="addAward()">+ Add Achievement</button>

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
              <label>Project Name <span class="char-count">(Max: 100)</span></label>
              <input type="text" name="projects[{{ $index }}][0]" value="{{ old('projects.' . $index . '.0', $project[0] ?? '') }}" maxlength="100">
            </div>
            <div class="form-group">
              <label>Project URL <span class="char-count">(Max: 500)</span></label>
              <input type="url" name="projects[{{ $index }}][1]" value="{{ old('projects.' . $index . '.1', $project[1] ?? '') }}" maxlength="500">
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

    // Address preview
    function updateAddressPreview() {
      const house = document.getElementById('house').value;
      const barangay = document.getElementById('barangay').value;
      const city = document.getElementById('city').value;
      const province = document.getElementById('province').value;
      const zip = document.getElementById('zip').value;
      const country = document.getElementById('country').value;

      const parts = [house, barangay, city, province, zip, country].filter(p => p.trim() !== '');
      document.getElementById('address-display').textContent = parts.join(', ') || 'Enter address details above';
    }

    document.getElementById('house').addEventListener('input', updateAddressPreview);
    document.getElementById('barangay').addEventListener('input', updateAddressPreview);
    document.getElementById('city').addEventListener('input', updateAddressPreview);
    document.getElementById('province').addEventListener('input', updateAddressPreview);
    document.getElementById('zip').addEventListener('input', updateAddressPreview);
    document.getElementById('country').addEventListener('input', updateAddressPreview);

    // Initialize address preview
    updateAddressPreview();

    // Phone functions
    function addPhone() {
      const container = document.getElementById('phones-container');
      const html = `
        <div class="phone-item">
          <input type="text" name="phones[]" value="" required maxlength="20" placeholder="+63 9XX XXX XXXX">
          <button type="button" class="btn-remove" onclick="removePhone(this)">Remove</button>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
    }

    function removePhone(btn) {
      const container = document.getElementById('phones-container');
      if (container.querySelectorAll('.phone-item').length > 1) {
        btn.closest('.phone-item').remove();
      } else {
        alert('You must have at least one phone number.');
      }
    }

    function addEducation() {
      const container = document.getElementById('education-container');
      const html = `
        <div class="repeater-item education-item">
          <div class="repeater-item-header">
            <strong>Education #${educationIndex + 1}</strong>
            <button type="button" class="btn-remove" onclick="removeEducation(this)">Remove</button>
          </div>
          <div class="form-group">
            <label>School Name <span class="char-count">(Max: 200)</span></label>
            <input type="text" name="education[${educationIndex}][0]" value="" maxlength="200">
          </div>
          <div class="form-group">
            <label>Year Graduated / Details <span class="char-count">(Max: 100)</span></label>
            <input type="text" name="education[${educationIndex}][1]" value="" maxlength="100">
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
          <input type="text" name="interests[]" value="" maxlength="100">
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
            <label style="margin: 0; color: #ff0000; font-weight: 700;">Organization Name <span class="char-count">(Max: 200)</span></label>
            <button type="button" class="btn-remove" onclick="removeLeadership(this)">Remove Org</button>
          </div>
          <input type="text" class="org-name" name="leadership_orgs[]" value="" maxlength="200">
          
          <div class="roles-container" style="margin-top: 15px;">
            <label style="color: #fff; font-weight: 600;">Roles <span class="char-count">(Max: 200 each)</span>:</label>
            <div class="role-item">
              <input type="text" name="leadership_roles_${leadershipIndex}[]" value="" maxlength="200">
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
          <input type="text" name="leadership_roles_${orgIndex}[]" value="" maxlength="200">
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
            <strong>Achievement #${awardIndex + 1}</strong>
            <button type="button" class="btn-remove" onclick="removeAward(this)">Remove</button>
          </div>
          <div class="form-group">
            <label>Achievement Title <span class="char-count">(Max: 200)</span></label>
            <input type="text" name="awards[${awardIndex}][0]" value="" maxlength="200">
          </div>
          <div class="form-group">
            <label>Details <span class="char-count">(Max: 200)</span></label>
            <input type="text" name="awards[${awardIndex}][1]" value="" maxlength="200">
          </div>
          <div class="form-group">
            <label>Date <span class="char-count">(Max: 50)</span></label>
            <input type="text" name="awards[${awardIndex}][2]" value="" maxlength="50">
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
            <label>Project Name <span class="char-count">(Max: 100)</span></label>
            <input type="text" name="projects[${projectIndex}][0]" value="" maxlength="100">
          </div>
          <div class="form-group">
            <label>Project URL <span class="char-count">(Max: 500)</span></label>
            <input type="url" name="projects[${projectIndex}][1]" value="" maxlength="500">
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