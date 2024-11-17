@extends('layouts.app')

@section('content')
<head>
    <!-- Other head content -->
    <link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
    <div class="container mt-4">
        <h1 class="my-4">
            Hello {{auth()->user()->name}}
        </h1>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Calendar
                    </div>
                    <div class="card-body">

                    <div class="d-flex gap-2 align-items-center mb-2">
                        <div style="width:10px; height:10px; background: red;"></div> Fully Booked
                    </div>
                        <v-calendar :min-date='new Date()' :attributes="attributes" is-expanded @dayclick="dayClickHandler"></v-calendar>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        Appointment Logs
                    </div>
                    <div class="card-body" style="max-height:300px; overflow-y:auto;">
                        @forelse (auth()->user()->appointments()->latest()->get() as $item)
                            <div class="card mt-4">
                                <div class="card-header d-flex justify-content-between">
                                    <div>{{$item->date->format('m/d/y')}}</div>
                                    <div class="badge {{$item->status == 'For Approval' ? 'bg-secondary': ($item->status == 'Approved' ? 'bg-success': 'bg-warning')}}">
                                        {{$item->status}}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h4>{{$item->service}}</h4>
                                    <p>{{$item->slot}}</p>
                                </div>
                                @if ($item->status == 'Approved')
                                    <div class="card-footer">
                                        <form action="/cancel" method="POST" class="d-flex justify-content-end">
                                            @csrf
                                            <input type="hidden" name="appointment_id" value="{{$item->id}}" />
                                            <button class="btn btn-danger">CANCEL</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                        <div class="alert alert-info">
                            No record found.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Appointment form
                    </div>
                    <div class="card-body">

                        <form id="appointmentForm" action="/reserve" method="POST" class="text-left">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{auth()->id()}}">
                            <div class="form-group">
                            <label for="service">Service</label>
                            <div class="dropdown-wrapper">
                                <select class="form-control" name="service" required>
                                    <option value="" disabled selected>Select a Service</option>
                                    @foreach (\App\Models\Service::get() as $item)
                                        <option {{ request()->service == $item->name ? 'selected' : '' }} value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                            </select>
                            </div>
                            <div class="form-group mt-4">
                                <label for="">Date </label>
                                <input type="date" v-model="date" name="date"  @change="loadSlots" min="{{date('Y-m-d')}}" class="form-control" required>
                            </div>
                            <div class="form-group mt-4">
                                <label for="">TimeSlot </label>
                                <select class="form-control" name="slot">
                                    <option :value="slot" v-for="slot in slots" :key="slot">
                                        @{{slot}}
                                    </option>
                                </select>
                                <span style="font-size: 12px;">Please Choose date first, to load the available slots.</span>
                            </div>
                            <div class="form-group mt-4">
                                <label for="">Notes</label>
                                <textarea name="remarks" id="" class="form-control"></textarea>
                            </div>
                            <button type="button" class="btn btn-primary mt-4" onclick="validateAndConfirm()">Send Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateAndConfirm() {
            const form = document.getElementById("appointmentForm");
            // Check if all required fields are filled
            const isFormValid = form.checkValidity();
            if (isFormValid) {
                // Use SweetAlert2 for a styled confirmation popup
                Swal.fire({
                    title: 'Confirm Appointment',
                    text: "Are you sure with your appointment details?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#7d5fff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Confirm',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();  // Submit the form if the user confirms
                    }
                });
            } else {
                // If not all fields are filled, show validation feedback
                form.reportValidity();  // Shows default validation messages for required fields
            }
        }
    </script>

    <script>
       new Vue({
            el: '#app',
            data() {
                return {
                    date: null,
                    slots: [],
                    noSlots: [],
                }
            },
            computed: {
                disabledDates() {
                    return this.noSlots.map( e => ({dates: e, bar: 'red'}))
                },
                attributes () {
                    return [...this.disabledDates, ]
                }
            },
            methods: {
                dayClickHandler(day) {
                    console.log('day -> ', day)
                    this.date = day.id;
                    this.loadSlots()
                },
                async loadSlots () {
                    try {
                       let response = await axios.get(`/api/slots?date=${this.date}`)
                        console.log('response -> ', response.data)
                        this.slots = response.data || []
                    } catch (error) {
                        console.log('error -> ', error)
                    }
                },
            },
            async mounted() {
                let response = await fetch('/api/fully-booked');
                let data = await response.json()
                this.noSlots = data;
            },
       })
    </script>
@endsection
