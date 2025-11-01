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
                'phones' => json_decode($resume->phones, true) ?? [$resume->phone],
                'address' => json_decode($resume->address, true) ?? $this->parseAddress($resume->address),
            ];
        }

        // Default data if no database record exists
        return $this->getDefaultResumeData();
    }

    private function parseAddress($addressString)
    {
        // Simple parsing - you may need to adjust based on your data
        return [
            'country' => 'Philippines',
            'province' => '',
            'city' => '',
            'barangay' => '',
            'zip' => '',
            'house' => $addressString
        ];
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
                "Date of Birth" => "2005-03-05",
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
            'phones' => ["+63 9672954793"],
            'address' => [
                'house' => 'Purok 7',
                'barangay' => 'Bolbok',
                'city' => 'Batangas City',
                'province' => 'Batangas',
                'zip' => '4200',
                'country' => 'Philippines'
            ]
        ];
    }

    // Public view (default landing page)
    public function showPublic(Request $request)
    {
        // Get id from query parameter, default to 1
        $id = $request->query('id', 1);
        
        // Fetch resume by id
        $resume = DB::table('resume')->where('id', $id)->first();

        if ($resume) {
            $address = json_decode($resume->address, true);
            $addressString = $this->formatAddressString($address);
            
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
                'phone' => implode(', ', json_decode($resume->phones, true) ?? []),
                'address' => $addressString,
            ];
        } else {
            // Use default data if no resume found
            $defaultData = $this->getDefaultResumeData();
            $defaultData['phone'] = implode(', ', $defaultData['phones']);
            $defaultData['address'] = $this->formatAddressString($defaultData['address']);
            $data = $defaultData;
        }
        
        return view('resume.public', $data);
    }

    private function formatAddressString($address)
    {
        if (is_string($address)) {
            return $address;
        }
        
        $parts = array_filter([
            $address['house'] ?? '',
            $address['barangay'] ?? '',
            $address['city'] ?? '',
            $address['province'] ?? '',
            $address['zip'] ?? '',
            $address['country'] ?? ''
        ]);
        
        return implode(', ', $parts);
    }

    // Edit view (for authenticated users)
    public function edit()
    {
        $data = $this->getResumeData();
        return view('resume.edit', $data);
    }

    // Update resume (for authenticated users)
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nickname' => 'required|string|max:50',
            'title' => 'required|string|max:100',
            'university' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'email' => 'required|email|max:100',
            'phones.*' => 'required|string|max:20',
            'address.house' => 'required|string|max:100',
            'address.barangay' => 'required|string|max:100',
            'address.city' => 'required|string|max:100',
            'address.province' => 'required|string|max:100',
            'address.zip' => 'required|string|max:10',
            'address.country' => 'required|string|max:100',
            'personal_info.Date of Birth' => 'required|date',
            'personal_info.Place of Birth' => 'required|string|max:100',
            'personal_info.Civil Status' => 'required|string|max:50',
            'personal_info.Citizenship' => 'required|string|max:50',
        ]);

        // Process phones - filter out empty values
        $phones = array_filter($request->phones ?? [], function($phone) {
            return !empty(trim($phone));
        });
        $phones = array_values($phones);

        // Process education
        $education = [];
        if ($request->has('education')) {
            foreach ($request->education as $edu) {
                if (!empty($edu[0]) || !empty($edu[1])) {
                    $education[] = [
                        substr($edu[0] ?? '', 0, 200),
                        substr($edu[1] ?? '', 0, 100)
                    ];
                }
            }
        }

        // Process interests
        $interests = array_filter($request->interests ?? [], function($item) {
            return !empty(trim($item));
        });
        $interests = array_map(function($item) {
            return substr(trim($item), 0, 100);
        }, $interests);

        // Process leadership
        $leadership = [];
        if ($request->has('leadership_orgs')) {
            foreach ($request->leadership_orgs as $index => $org) {
                $org = trim($org);
                if (!empty($org) && strlen($org) <= 200) {
                    $rolesKey = 'leadership_roles_' . $index;
                    $roles = $request->input($rolesKey, []);
                    
                    $roles = array_filter($roles, function($role) {
                        return !empty(trim($role));
                    });
                    
                    $roles = array_map(function($role) {
                        return substr(trim($role), 0, 200);
                    }, $roles);
                    
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
                        substr($award[0] ?? '', 0, 200),
                        substr($award[1] ?? '', 0, 200),
                        substr($award[2] ?? '', 0, 50)
                    ];
                }
            }
        }

        // Process projects
        $projects = [];
        if ($request->has('projects')) {
            foreach ($request->projects as $project) {
                if (!empty($project[0]) || !empty($project[1])) {
                    $projects[] = [
                        substr($project[0] ?? '', 0, 100),
                        substr($project[1] ?? '', 0, 500)
                    ];
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
            'phones' => json_encode($phones),
            'address' => json_encode($request->address),
        ];

        // Check if resume exists
        $resumeExists = DB::table('resume')->where('id', 1)->exists();

        if ($resumeExists) {
            DB::table('resume')->where('id', 1)->update($resumeData);
        } else {
            $resumeData['id'] = 1;
            DB::table('resume')->insert($resumeData);
        }

        return redirect()->route('resume.edit')->with('success', 'Resume updated successfully! All changes have been saved.');
    }
}