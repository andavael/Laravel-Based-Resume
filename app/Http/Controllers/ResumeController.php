<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumeController extends Controller
{
    // Get resume data from database or defaults
    private function getResumeData()
    {
        $resume = DB::table('resume')->first();

        if ($resume) {
            return [
                'name' => $resume->full_name,
                'nickname' => $resume->nickname,
                'title' => $resume->title,
                'university' => $resume->university,
                'description' => $resume->description,
                'personalInfo' => json_decode($resume->personal_info, true) ?? [],
                'education' => json_decode($resume->education, true) ?? [],
                'leadership' => json_decode($resume->leadership, true) ?? [],
                'interests' => json_decode($resume->interests, true) ?? [],
                'awards' => json_decode($resume->awards, true) ?? [],
                'projects' => json_decode($resume->projects, true) ?? [],
                'email' => $resume->email,
                'phone' => $resume->phone,
                'address' => $resume->address,
            ];
        }

        // Default data if no database record exists
        return $this->getDefaultResumeData();
    }

    private function getDefaultResumeData()
    {
        return [
            'name' => "Anthonina Dhapniella C. Vael",
            'nickname' => "Anda",
            'title' => "BSCS Student",
            'university' => "Batangas State University – TNEU, Alangilan Campus",
            'description' => "BS in Computer Science student skilled in web development, database management, programming, and networking. Active student leader with proven leadership experience at Batangas State University – TNEU, Alangilan Campus.",
            'personalInfo' => [
                "Date of Birth" => "March 5, 2005",
                "Place of Birth" => "Batangas City",
                "Civil Status" => "Single",
                "Citizenship" => "Filipino"
            ],
            'education' => [
                ["CENTEX Batangas", "Year Graduated: 2017"],
                ["Batangas State University - Integrated School Department", "Year Graduated: 2021"],
                ["Batangas State University - Integrated School Department", "Expected Year of Graduation: 2023"]
            ],
            'leadership' => [
                "College of Informatics and Computing Sciences Student Council" => [
                    "Committee Member for Technical Affairs | A.Y. 2022 – 2025",
                    "Co-Head for Live Production and Streaming | A.Y. 2025 – 2026"
                ],
                "Association of Committed Computer Science Students" => [
                    "Organization Member | A.Y. 2023 – 2024",
                    "Associate Director for Technical and Publicity | A.Y. 2024 – 2025",
                    "Director for Technical Affairs | A.Y. 2025 – 2026"
                ],
                "Junior Philippine Computer Society" => [
                    "Member | A.Y. 2023 – Present"
                ]
            ],
            'interests' => ["Programming", "UX and UI Design", "Digital Media Production", "Live Production and Streaming"],
            'awards' => [
                ["CICS Dean's Lister", "1st to 2nd Year, 1st Semester – 2nd Semester", "2023 – 2025"],
                ["Mastering Programming and Data Analysis | Committee member", "", "October 1, 2024"],
                ["Technofusion 2025 Parke Pasiklaban, Cultural Competitions | Co-Head", "", "April 2025"],
                ["Technofusion 2025 Live Committee | Co-Head", "", "April 2025"],
                ["Technofusion 2025 Battle of the Bands Competition | Co-Head", "", "April 2025"],
                ["Technofusion 2025 InterCICSkwela | Committee Member", "", "April 2025"]
            ],
            'projects' => [
                ["FitSpace", "https://github.com/andavael/FitSpace"],
                ["Baraco", "https://github.com/ailadonayre/BARACO-Batangas-Railway-Corporation-"],
                ["Coralis", "https://github.com/andavael"]
            ],
            'email' => "andavael05@gmail.com",
            'phone' => "+63 9672954793",
            'address' => "Purok 7, Bolbok, Batangas City, Philippines"
        ];
    }

    // Public view (default landing page)
    // Update this method in ResumeController.php
    public function showPublic(Request $request)
    {
        // Get id from query parameter, default to 1
        $id = $request->query('id', 1);
        
        // Fetch resume by id
        $resume = DB::table('resume')->where('id', $id)->first();

        if ($resume) {
            $data = [
                'name' => $resume->full_name,
                'nickname' => $resume->nickname,
                'title' => $resume->title,
                'university' => $resume->university,
                'description' => $resume->description,
                'personalInfo' => json_decode($resume->personal_info, true) ?? [],
                'education' => json_decode($resume->education, true) ?? [],
                'leadership' => json_decode($resume->leadership, true) ?? [],
                'interests' => json_decode($resume->interests, true) ?? [],
                'awards' => json_decode($resume->awards, true) ?? [],
                'projects' => json_decode($resume->projects, true) ?? [],
                'email' => $resume->email,
                'phone' => $resume->phone,
                'address' => $resume->address,
            ];
        } else {
            // Use default data if no resume found
            $data = $this->getDefaultResumeData();
        }
        
        return view('resume.public', $data);
    }

    // Edit view (for authenticated users)
    public function edit()
    {
        $data = $this->getResumeData();
        return view('resume.edit', $data);
    }

    // Update resume (for authenticated users)
    // Replace the entire update method in ResumeController.php
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        // Process education
        $education = [];
        if ($request->has('education')) {
            foreach ($request->education as $edu) {
                if (!empty($edu[0]) || !empty($edu[1])) {
                    $education[] = [$edu[0] ?? '', $edu[1] ?? ''];
                }
            }
        }

        // Process interests
        $interests = array_filter($request->interests ?? [], function($item) {
            return !empty(trim($item));
        });

        // Process leadership - FIXED VERSION
        $leadership = [];
        if ($request->has('leadership_orgs')) {
            foreach ($request->leadership_orgs as $index => $org) {
                $org = trim($org);
                if (!empty($org)) {
                    // Get roles for this specific organization using the index
                    $rolesKey = 'leadership_roles_' . $index;
                    $roles = $request->input($rolesKey, []);
                    
                    // Filter out empty roles
                    $roles = array_filter($roles, function($role) {
                        return !empty(trim($role));
                    });
                    
                    // Only add if there's at least one role
                    if (!empty($roles)) {
                        $leadership[$org] = array_values($roles);
                    }
                }
            }
        }

        // Process awards
        $awards = [];
        if ($request->has('awards')) {
            foreach ($request->awards as $award) {
                if (!empty($award[0])) {
                    $awards[] = [
                        $award[0] ?? '',
                        $award[1] ?? '',
                        $award[2] ?? ''
                    ];
                }
            }
        }

        // Process projects
        $projects = [];
        if ($request->has('projects')) {
            foreach ($request->projects as $project) {
                if (!empty($project[0]) || !empty($project[1])) {
                    $projects[] = [$project[0] ?? '', $project[1] ?? ''];
                }
            }
        }

        // Prepare data for database
        $resumeData = [
            'full_name' => $request->name,
            'nickname' => $request->nickname,
            'title' => $request->title,
            'university' => $request->university,
            'description' => $request->description,
            'personal_info' => json_encode($request->personal_info ?? []),
            'education' => json_encode($education),
            'leadership' => json_encode($leadership),
            'interests' => json_encode(array_values($interests)),
            'awards' => json_encode($awards),
            'projects' => json_encode($projects),
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Check if resume exists
        $resumeExists = DB::table('resume')->where('id', 1)->exists();

        if ($resumeExists) {
            // Update existing resume
            DB::table('resume')->where('id', 1)->update($resumeData);
        } else {
            // Insert new resume with id = 1
            $resumeData['id'] = 1;
            DB::table('resume')->insert($resumeData);
        }

        return redirect()->route('resume.edit')->with('success', 'Resume updated successfully! Leadership and all other sections have been saved.');
    }
}