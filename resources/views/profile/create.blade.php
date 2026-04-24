@extends('layouts.app')

@section('title', 'Create Profile')

@section('content')

<div class="max-w-5xl mx-auto mt-10 px-6">

    <h2 class="text-3xl font-bold mb-6 text-gray-800">Create Profile</h2>

    <form method="POST" action="/profile/store" enctype="multipart/form-data">
        @csrf

        <!-- BASIC DETAILS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            <!-- Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Full Name
                </label>

                    
                <input type="text" name="full_name"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your name">
            </div>

            <!-- Mobile -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Mobile Number
                </label>
                <input type="text" name="mobile"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter mobile number">
            </div>

            <!-- City -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    City
                </label>
                <input type="text" name="city"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter city">
            </div>

            <!-- Country -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Country
                </label>
                <input type="text" name="country"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter country">
            </div>

        </div>

        <!-- DEGREE + SPECIALIZATION -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            <!-- Degree -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Degree
                </label>

                <select name="degree" id="degree"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">

                    <option value="">Select Degree</option>
                    @foreach($selectDegree as $degree => $branches)
                        <option value="{{ $degree }}">{{ $degree }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Branch -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Specialization / Branch
                </label>

                <select name="branch" id="branch"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">

                    <option value="">Select Specialization</option>
                </select>
            </div>

        </div>

        <!-- PROFILE PHOTO -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Profile Photo
            </label>
            <input type="file" name="profile_photo"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- PASSING YEAR -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Passing Year
            </label>

            <select name="passing_year"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">

                @for($year = date('Y'); $year >= 2000; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor

            </select>
        </div>

        <!-- SUBMIT -->
        <button type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
            Save Profile
        </button>

    </form>

</div>

<!-- PASS DATA TO JS -->
<script>
    let degreeData = @json($selectDegree);
</script>

<!-- DYNAMIC DROPDOWN -->
<script>
    const degreeSelect = document.getElementById("degree");
    const branchSelect = document.getElementById("branch");

    degreeSelect.addEventListener("change", function () {

        let selectedDegree = this.value;

        branchSelect.innerHTML = '<option value="">Select Specialization</option>';

        if (degreeData[selectedDegree]) {
            degreeData[selectedDegree].forEach(function (branch) {

                let option = document.createElement("option");
                option.value = branch;
                option.textContent = branch;

                branchSelect.appendChild(option);
            });
        }
    });
</script>

@endsection