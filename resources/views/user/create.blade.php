@extends('layout.master')
@section('title', 'Create User')
@section('header-title', 'Create User')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- Name --}}
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="name"><b>Name</b><span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" placeholder="Enter Name"
                                        value="{{ old('name') }}" class="form-control">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="email"><b>Email</b></label>
                                    <input type="email" id="email" name="email" placeholder="Enter Email"
                                        value="{{ old('email') }}" class="form-control">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="password"><b>Password</b></label>
                                    <input type="password" id="password" name="password" placeholder="Enter Password"
                                        value="{{ old('password') }}" class="form-control">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="status"><b>Status</b></label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Roles --}}
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="roles"><b>Assign Role(s)</b></label>
                                    <select name="roles[]" id="roles" class="form-control select2" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ collect(old('roles'))->contains($role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Department --}}
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="department"><b>Assign Department</b></label>
                                    <select name="department" id="department" class="form-control">
                                        <option value="" selected disabled>Select Department</option>
                                        <option value="Project-Manager">Project Manager</option>
                                        <option value="Store-Manager">Store Manager</option>
                                        <option value="Manager-of-Procurement">Manager of Procurement</option>
                                        <option value="CEO">CEO</option>
                                    </select>
                                    @error('department')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Is Contractor --}}
                            {{-- <div class="col-12 col-sm-6">
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="is_contractor" name="is_contractor"
                                        value="1" {{ old('is_contractor') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_contractor"><b>Is Contractor?</b></label>
                                </div>
                            </div> --}}

                            {{-- Contractor Dropdown --}}
                            <div class="col-12 col-sm-6" id="contractor_section" style="display:none;">
                                <div class="mb-3">
                                    <label for="contractor_id"><b>Select Contractor</b></label>
                                    <select name="contractor_id" id="contractor_id" class="form-control">
                                        <option value="" disabled selected>Select Contractor</option>
                                        @foreach ($contractors as $contractor)
                                            <option value="{{ $contractor->id }}"
                                                {{ old('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                                {{ $contractor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contractor_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary"><b>Save User</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const contractorCheckbox = document.getElementById("is_contractor");
            const contractorSection = document.getElementById("contractor_section");
            const contractorSelect = document.getElementById("contractor_id");

            function toggleContractorSection() {
                if (contractorCheckbox.checked) {
                    contractorSection.style.display = "block";
                    contractorSelect.setAttribute("required", "required");
                } else {
                    contractorSection.style.display = "none";
                    contractorSelect.removeAttribute("required");
                    contractorSelect.value = "";
                }
            }

            toggleContractorSection();

            contractorCheckbox.addEventListener("change", toggleContractorSection);
        });
    </script>
@endsection
