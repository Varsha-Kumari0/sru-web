@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto mt-8">
    <div class="bg-white shadow rounded-lg p-8">

        <h2 class="text-2xl font-semibold mb-6">Create Profile</h2>

        <form method="POST" action="/profile/store">
            @csrf

            <!-- STEP 1 -->
            <div id="step1">

                <h3 class="text-lg font-semibold mb-4">Basic & Education Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <input name="full_name" placeholder="Full Name" class="input">
                    <input name="mobile" placeholder="Mobile" class="input">

                    <input name="city" placeholder="City" class="input">
                    <input name="country" placeholder="Country" class="input">

                </div>

                <!-- DEGREE + SPECIALIZATION -->
                <div class="mt-6">

                    <label class="label mb-3">Select Degree & Specialization</label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- LEFT -->
                        <div class="bg-blue-900 text-white rounded p-4 space-y-3">

                            <div onclick="selectDegree('btech')" class="degree-item">B.Tech</div>
                            <div onclick="selectDegree('business')" class="degree-item">Business</div>
                            <div onclick="selectDegree('agriculture')" class="degree-item">Agriculture</div>
                            <div onclick="selectDegree('bsc')" class="degree-item">B.Sc</div>
                            <div onclick="selectDegree('bcom')" class="degree-item">B.Com</div>
                            <div onclick="selectDegree('bca')" class="degree-item">BCA</div>

                        </div>

                        <!-- RIGHT -->
                        <div id="specializationBox" class="bg-blue-900 text-white rounded p-4">
                            <p class="text-gray-300">Select a degree to see options</p>
                        </div>

                    </div>

                    <input type="hidden" name="degree" id="degreeInput">
                    <input type="hidden" name="branch" id="branchInput">

                </div>

                <!-- PASSING YEAR -->
                <div class="mt-6">
                    <label class="label">Passing Year</label>
                    <select name="passing_year" class="input">
                        @for($year = date('Y'); $year >= 2000; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" onclick="nextStep()" class="btn">
                        Next →
                    </button>
                </div>

            </div>

            <!-- STEP 2 -->
            <div id="step2" style="display:none">

                <h3 class="text-lg font-semibold mb-4">Professional Experience</h3>

                <div id="experienceContainer"></div>

                <button type="button" onclick="addExperience()" class="text-blue-600 mt-2">
                    + Add Experience
                </button>

                <div class="mt-6 flex justify-between">
                    <button type="button" onclick="prevStep()" class="btn-gray">
                        ← Back
                    </button>

                    <button class="btn">
                        Create Profile
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>

<!-- STYLE -->
<style>
.input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.label {
    font-size: 13px;
    font-weight: 600;
}
.btn {
    background: #2563eb;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
}
.btn-gray {
    background: #ccc;
    padding: 8px 16px;
    border-radius: 6px;
}
.degree-item {
    padding: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    cursor: pointer;
}
.degree-item:hover {
    color: #facc15;
}
.specialization-item {
    padding: 6px 0;
    cursor: pointer;
}
.specialization-item:hover {
    color: #facc15;
}
</style>

<!-- JS -->
<script>

function nextStep() {
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
}

function prevStep() {
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
}

function selectDegree(degree) {

    document.getElementById('degreeInput').value = degree;

    let box = document.getElementById('specializationBox');

    let data = {

        btech: [
            "CSE (AI & ML)",
            "CSE (Cybersecurity)",
            "CSE (Data Science)",
            "ECE (VLSI)",
            "EEE (Renewable Energy)",
            "Mechanical (Smart Manufacturing)",
            "Civil (Robotics and Automation)"
        ],

        business: [
            "BBA (Marketing)",
            "BBA (Finance)",
            "BBA (Operations)",
            "BBA (International Business)",
            "BBA (Business Analytics)"
        ],

        agriculture: [
            "B.Sc (Hons) Agriculture"
        ],

        bsc: [
            "B.Sc (Computer Science)",
            "B.Sc (Physics)",
            "B.Sc (Chemistry)",
            "B.Sc (Mathematics)",
            "B.Sc (Forensic Science)"
        ],

        bcom: [
            "B.Com (Computer Applications)"
        ],

        bca: [
            "BCA General",
            "BCA (Cloud Computing)"
        ]
    };

    let html = "";

    data[degree].forEach(item => {
        html += `<div class="specialization-item" onclick="selectBranch('${item}')">• ${item}</div>`;
    });

    box.innerHTML = html;
}

function selectBranch(branch) {
    document.getElementById('branchInput').value = branch;
}

function addExperience() {
    let container = document.getElementById('experienceContainer');

    let div = document.createElement('div');
    div.classList.add('border','p-4','mb-4','rounded');

    div.innerHTML = `
        <input name="organization[]" class="input mb-2" placeholder="Organization">
        <input name="role[]" class="input mb-2" placeholder="Role">
        <input name="industry[]" class="input mb-2" placeholder="Industry">
        <input name="location_exp[]" class="input mb-2" placeholder="Location">
        <input type="date" name="from[]" class="input mb-2">
        <input type="date" name="to[]" class="input mb-2">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-500">Remove</button>
    `;

    container.appendChild(div);
}

</script>

@endsection