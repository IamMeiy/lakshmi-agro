@extends('layouts.master')

@section('title', 'Profile')
    
@section('content')
    <div class="py-4">
        <div class="container">
            <div class="row g-4">

                <!-- Update Profile Information Form -->
                <div class="col-12 mb-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Password Form -->
                <div class="col-12 mb-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete User Form -->
                <div class="col-12 mb-5">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
