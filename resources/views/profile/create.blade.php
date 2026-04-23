<!DOCTYPE html>
<html>
<head>
    <title>Alumni Registration</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-blue-700 to-blue-500 min-h-screen flex items-center justify-center">

<div class="bg-white w-full max-w-xl p-8 rounded-2xl shadow-xl">

    <h2 class="text-2xl font-semibold text-center text-blue-700 mb-1">
        Alumni Registration
    </h2>
    <p class="text-center text-gray-500 mb-6">
        Connect with your alumni network
    </p>

    <form id="form" method="POST" action="/profile/store">
        @csrf

        <!-- ERROR -->
        <div id="errorMessage" class="text-red-600 text-sm mb-3 hidden font-medium"></div>

        <!-- STEP 1 -->
        <div class="step">
            <h3 class="text-lg font-semibold mb-3 text-blue-600">Basic Information</h3>

            <input name="full_name" class="input" placeholder="Full Name" required>
            <input name="mobile" class="input" placeholder="Mobile Number" required>

            <input name="city" class="input" placeholder="City" required>
            <input name="country" class="input" placeholder="Country" required>

            <input name="linkedin" class="input" placeholder="LinkedIn Profile">
            <input name="facebook" class="input" placeholder="Facebook Profile">
            <input name="instagram" class="input" placeholder="Instagram Profile">
            <input name="twitter" class="input" placeholder="X (Twitter) Profile">
        </div>

        <!-- STEP 2 -->
        <div class="step hidden">
            <h3 class="text-lg font-semibold mb-3 text-blue-600">Academic Details</h3>

            <select name="degree" id="degree" class="input" required onchange="updateBranches()">
                <option value="" disabled selected>Select Degree</option>
                <option value="btech">B.Tech</option>
                <option value="business">Business</option>
                <option value="agriculture">Agriculture</option>
                <option value="bsc">B.Sc</option>
                <option value="bcom">B.Com</option>
                <option value="bca">BCA</option>
            </select>

            <select name="branch" id="branch" class="input" required>
                <option value="" disabled selected>Select Branch / Specialization</option>
            </select>

            <input name="passing_year" class="input" type="number" placeholder="Year of Passing" required>
        </div>

        <!-- STEP 3 -->
        <div class="step hidden">
            <h3 class="text-lg font-semibold mb-3 text-blue-600">Professional Details</h3>

            <input name="current_status" class="input" placeholder="Current Role / Status">
            <input name="company" class="input" placeholder="Current Company">

            <div id="experienceContainer"></div>

            <button type="button" onclick="addExperience()" 
                class="text-blue-600 text-sm font-medium mb-4">
                + Add Experience
            </button>
        </div>

        <!-- BUTTONS -->
        <div class="flex justify-between mt-6">

            <button type="button" onclick="prevStep()" 
                class="px-5 py-2 rounded-full bg-gray-300 text-gray-700 hover:bg-gray-400">
                Back
            </button>

            <button type="button" onclick="nextStep()" 
                class="px-6 py-2 rounded-full bg-blue-600 text-white hover:bg-blue-700">
                Next
            </button>

        </div>

    </form>
</div>

<!-- STYLES -->
<style>
.input {
    width: 100%;
    margin-bottom: 14px;
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
}

.input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
}

.input.error {
    border-color: red;
}

.hidden {
    display: none;
}

.card {
    border: 1px solid #e5e7eb;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 15px;
    background: #f9fafb;
}
</style>

<!-- SCRIPT -->
<script>

let step = 0;
const steps = document.querySelectorAll(".step");

function showStep() {
    steps.forEach((s, i) => s.classList.toggle("hidden", i !== step));

    const btn = document.querySelector('[onclick="nextStep()"]');
    btn.innerText = (step === steps.length - 1) ? "Submit" : "Next";
}

function validateStep() {
    const inputs = steps[step].querySelectorAll("input, select");
    const errorBox = document.getElementById("errorMessage");

    errorBox.classList.add("hidden");

    for (let input of inputs) {
        input.classList.remove("error");

        if (input.hasAttribute("required") && (!input.value || input.value.trim() === "")) {
            input.classList.add("error");
            input.focus();

            errorBox.innerText = "Please fill all required fields before proceeding.";
            errorBox.classList.remove("hidden");
            return false;
        }

        if (input.name === "mobile") {
            if (!/^[0-9]{10}$/.test(input.value)) {
                input.classList.add("error");
                input.focus();

                errorBox.innerText = "Enter a valid 10-digit mobile number.";
                errorBox.classList.remove("hidden");
                return false;
            }
        }
    }

    // experience required
    if (step === 2) {
        const cards = document.querySelectorAll(".card");
        if (cards.length === 0) {
            errorBox.innerText = "Please add at least one professional experience.";
            errorBox.classList.remove("hidden");
            return false;
        }
    }

    return true;
}

function nextStep() {
    if (!validateStep()) return;

    if (step < steps.length - 1) {
        step++;
        showStep();
    } else {
        document.getElementById("form").submit();
    }
}

function prevStep() {
    if (step > 0) {
        step--;
        showStep();
    }
}

// DYNAMIC BRANCHES
function updateBranches() {
    const degree = document.getElementById("degree").value;
    const branch = document.getElementById("branch");

    const data = {
        btech: [
            "CSE (AI & ML)", "CSE (Cybersecurity)", "CSE (Data Science)",
            "CSE (Gaming & ARVR)", "CSE (Robotics & Automation)",
            "ECE (VLSI)", "ECE (Robotics & Automation)",
            "EEE (Renewable Energy)", "EEE (Robotics & Automation)",
            "EEE (Electric Vehicles)",
            "ME (Smart Manufacturing)", "ME (Robotics & Automation)",
            "CE (Robotics & Automation)"
        ],
        business: [
            "BBA (Marketing)", "BBA (Finance)", "BBA (Operations)",
            "BBA (International Business)", "BBA (Business Analytics)"
        ],
        agriculture: ["B.Sc (Hons.) Agriculture"],
        bsc: ["Computer Science", "Physics", "Chemistry", "Forensic Science", "Mathematics"],
        bcom: ["Computer Applications"],
        bca: ["General", "Cloud Computing"]
    };

    branch.innerHTML = '<option disabled selected>Select Branch / Specialization</option>';

    data[degree].forEach(item => {
        const option = document.createElement("option");
        option.value = item;
        option.text = item;
        branch.appendChild(option);
    });
}

// EXPERIENCE BUILDER
function addExperience() {
    const container = document.getElementById("experienceContainer");

    const card = document.createElement("div");
    card.classList.add("card");

    card.innerHTML = `
        <input name="organization[]" class="input" placeholder="Organization" required>
        
        <select name="industry[]" class="input" required>
            <option value="" disabled selected>Select Industry</option>
            <option>IT</option>
            <option>Finance</option>
            <option>Education</option>
            <option>Healthcare</option>
        </select>

        <input name="role[]" class="input" placeholder="Role / Designation" required>

        <div style="display:flex; gap:10px;">
            <input name="from[]" class="input" type="date" required>
            <input name="to[]" class="input" type="date">
        </div>

        <input name="location_exp[]" class="input" placeholder="Location" required>
    `;

    container.prepend(card);
}

showStep();

</script>

</body>
</html>