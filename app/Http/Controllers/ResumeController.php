<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    // Get resume data from database or blank template
    private function getResumeData($userId)
    {
        $resume = DB::table('resume')->where('user_id', $userId)->first();

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
                'phones' => json_decode($resume->phones, true) ?? [],
                'address' => json_decode($resume->address, true) ?? [],
            ];
        }

        // Return blank template if no resume exists
        return $this->getBlankTemplate();
    }

    private function getBlankTemplate()
    {
        return [
            'name' => "",
            'nickname' => "",
            'title' => "",
            'university' => "",
            'description' => "",
            'personalInfo' => [
                "Date of Birth" => "",
                "Place of Birth" => "",
                "Civil Status" => "",
                "Citizenship" => ""
            ],
            'education' => [
                ["", ""]
            ],
            'leadership' => [],
            'interests' => [""],
            'awards' => [
                ["", "", ""]
            ],
            'projects' => [
                ["", ""]
            ],
            'email' => "",
            'phones' => [""],
            'address' => [
                'house' => '',
                'barangay' => '',
                'city' => '',
                'province' => '',
                'zip' => '',
                'country' => ''
            ]
        ];
    }

    // Public view - shows the most recently edited resume by default
    public function showPublic(Request $request)
    {
        // Get id from query parameter, or fetch the most recently updated resume
        $id = $request->query('id');
        
        if ($id) {
            $resume = DB::table('resume')->where('id', $id)->first();
        } else {
            // Get the most recently updated resume
            $resume = DB::table('resume')
                ->orderBy('updated_at', 'desc')
                ->first();
        }

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
                'resumeId' => $resume->id,
            ];
        } else {
            // No resumes exist yet - show placeholder
            $data = [
                'name' => 'No Resume Available',
                'nickname' => 'N/A',
                'title' => 'No resumes have been created yet',
                'university' => '',
                'description' => 'Please login and create your resume.',
                'personalInfo' => [],
                'education' => [],
                'leadership' => [],
                'interests' => [],
                'awards' => [],
                'projects' => [],
                'email' => '',
                'phone' => '',
                'address' => '',
                'resumeId' => null,
            ];
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

    // Edit view (for authenticated users - only their own resume)
    public function edit()
    {
        $userId = Auth::id();
        $data = $this->getResumeData($userId);
        $data['userId'] = $userId;
        
        return view('resume.edit', $data);
    }

    // Update resume (for authenticated users - only their own resume)
    public function update(Request $request)
    {
        $userId = Auth::id();
        
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
            'user_id' => $userId,
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
            'updated_at' => now(),
        ];

        // Check if resume exists for this user
        $resumeExists = DB::table('resume')->where('user_id', $userId)->exists();

        if ($resumeExists) {
            DB::table('resume')->where('user_id', $userId)->update($resumeData);
        } else {
            $resumeData['created_at'] = now();
            DB::table('resume')->insert($resumeData);
        }

        return redirect()->route('resume.edit')->with('success', 'Resume updated successfully! Your changes are now visible on the public page.');
    }
}