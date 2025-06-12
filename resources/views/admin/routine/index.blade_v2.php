@extends('layouts.app')

@section('title', 'Routine')

@section('stylesheets')
<!-- DataTables -->
<link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .sticky {
        position: fixed;
        top: 121px;
        width: 100%;
        z-index: 1;
        transition: 0.5s ease;
        left: 0;
    }

    @media screen and (max-width: 768px) {
        .sticky {
            position: inherit !important;
        }
    }
</style>
@endsection

@section('content')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<div class="page-content-wrapper">
    <div class="container-fluid">
        <!-- end row -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-0 header-title mb-4">
                            <!-- Assign Routine -->

                            @if(Auth::user()->role == 'admin')
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target=".routine_reset_{{$yearly_session}}">Full Routine Reset</button>

                            <div class="modal fade routine_reset_{{$yearly_session}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5> Are you sure? You want to reset full routine? </h5>
                                        </div>
                                        <div class="modal-body">
                                            {!! Form::open(['route' => ['routine_reset'], 'method' => 'post', 'style' => 'display:inline']) !!}
                                            {!! Form::hidden('yearly_session_id', $yearly_session, ['class'=> 'form-control']) !!}
                                            {!! Form::submit('Yes', ['class' => 'btn btn-lg btn-danger']) !!}
                                            {!! Form::close() !!}
                                            <button type="button" class="btn btn-lg btn-primary waves-effect waves-light" data-toggle="modal" data-target=".routine_reset_{{$yearly_session}}"> No </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endif
                            <!--<div class="float-right font-14">
                                @if($last_created_by)
                                <span> Last Data Input by <strong>
                                        {{ ucwords($last_created_by->firstname." ".$last_created_by->lastname) }}
                                    </strong> at <span class="font-12">{{ date('d-m-Y h:i a', strtotime($last_created_by->created_at)) }}</span>
                                </span>@endif
                                <br>
                                @if($last_edited_by)
                                <span>
                                    Last Edited by <strong>
                                        {{ ucwords($last_edited_by->firstname." ".$last_edited_by->lastname) }}
                                    </strong> at <span class="font-12">{{ date('d-m-Y h:i a', strtotime($last_edited_by->updated_at)) }}</span>
                                </span>
                                @endif
                            </div>-->


                        </div>

                        <div>
                            @if($request_check && ($request_check->request_status == "active" && $request_check->expired_date >= now()))
                            <h5>Insert your data before
                                <span class="bg bg-danger text-light p-2"> {{ date('d-m-Y h:i a', strtotime($request_check->expired_date)) }} </span>
                            </h5>
                            @endif

                        </div>
                        @if (Session::has('message'))
                        <div class="alert-dismissable alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x
                            </button>
                            {{ Session('message') }}
                        </div>
                        @endif
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (Session::has('delete-message'))
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x
                            </button>
                            {{ Session('delete-message') }}
                        </div>
                        @endif

                        @foreach($slots as $slot)
                        <h3 class="text-uppercase bg-dark p-2 text-light float-left">
                            <strong>
                                {{ $slot->day_title }}
                            </strong>
                        </h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                @php
                                $period = 1;
                                @endphp

                                <tr>
                                    <th class="p-0" style="overflow: hidden">
                                        <span class="px-3 py-2 d-block border-bottom">Time</span>
                                        <span class="px-3 py-2 d-block">Class</span>
                                    </th>

                                    @foreach($day_wise_slots as $timeslot)
                                    @if ($slot->id == $timeslot->day_id)
                                    <th class="p-0 text-center" style="overflow: hidden">
                                        <span class="px-3 py-2 d-block">
                                            {{ date('g:i a', strtotime($timeslot->time_slot->from)) }} - {{ date('g:i a', strtotime($timeslot->time_slot->to)) }}
                                        </span>
                                        <span class="bg-success px-3 py-2 d-block text-light">
                                            @php
                                            $suffix = 'th';
                                            if (!in_array($period % 100, [11, 12, 13])) {
                                            $suffix = match($period % 10) {
                                            1 => 'st',
                                            2 => 'nd',
                                            3 => 'rd',
                                            default => 'th',
                                            };
                                            }
                                            @endphp
                                            {{ $period . $suffix . ' Period' }}
                                        </span>
                                    </th>
                                    @php $period++; @endphp
                                    @endif
                                    @endforeach
                                </tr>

                            </thead>
                            <tbody>

                                @foreach($sections as $section)
                                <tr>
                                    <td class="bg-info text-white" style="width: 140px; overflow: hidden; white-space: normal; word-wrap: break-word;">
                                        {{ $section->department_name }}<br>{{ $section->section_name }}
                                    </td>
                                    @foreach($day_wise_slots as $timeslot)
                                    @if ($slot->id == $timeslot->day_id)
                                    @php $flag = 1 @endphp
                                    @else @php $flag = 0 @endphp
                                    @endif

                                    @if($flag == 1)
                                    <td class="p-0">
                                        @php
                                        $day_id = $time_slot_id = $batch_id = $section_id = $room_id = $course_id = $yearly_session_id = $teacher_id = $routine_id = '' ;
                                        @endphp

                                        @foreach($slot->routine as $routine)


                                        @if($timeslot->day_id == $routine->day_id && $timeslot->time_slot_id == $routine->time_slot_id && $routine->batch_id == $section->batch_id && $section->section_id == $routine->section_id && $routine->yearly_session_id == $yearly_session)


                                        @php
                                        $todayDay = \Carbon\Carbon::now()->format('d');
                                        $proxy = \App\Models\ProxyRoutine::where('full_routine_id', $routine->id)
                                        ->whereRaw('DAY(proxy_date) = ?', [$todayDay])
                                        ->first();

                                        $isProxy = $proxy && $proxy->proxyTeacher;
                                        @endphp



                                        <span class="position-relative p-2 text-center d-block
    @if($isProxy)
        text-white
    @else
        {{ ($routine->course->course_type == '0') ? 'bg-warning text-dark' : 'bg-danger text-light' }}
    @endif"
                                            @if($isProxy)
                                            style="background-color: #dc3545;" {{-- Bootstrap red --}}
                                            @endif>

                                            {{ $routine->course->course_name }}<br>
                                            {{ $routine->room->building . '-' . $routine->room->room_no }}<br>

                                            @if($isProxy)
                                            {{ $routine->teacher->user->firstname . ' ' . $routine->teacher->user->lastname }}<br>
                                            (Proxy:{{ $proxy->proxyTeacher->user->firstname . ' ' . $proxy->proxyTeacher->user->lastname }} )
                                            @else
                                            {{ $routine->teacher->user->firstname . ' ' . $routine->teacher->user->lastname }}
                                            @endif
                                        </span>



                                        @php
                                        $routine_id = $routine->id;
                                        $day_id = $routine->day_id;
                                        $time_slot_id = $routine->time_slot_id;
                                        $batch_id = $routine->batch_id;
                                        $section_id = $routine->section_id;
                                        $yearly_session_id = $routine->yearly_session_id;
                                        $room_id = $routine->room_id;
                                        $course_id = $routine->course_id;
                                        $teacher_id = $routine->teacher_id;
                                        @endphp

                                        @if(($request_check && ($request_check->request_status == " active" && $request_check->expired_date >= now())) || Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin' || Auth::user()->in_committee == 'yes')

                                        <!-- <button style="right: 0;top: 3px" class="position-absolute btn btn-sm btn-dark" data-toggle="modal" data-target=".data_delete_{{ $routine_id }}">X</button> -->


                                        <div class="modal fade data_delete_{{ $routine_id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header text-dark">
                                                        <h5>Do you want to delete this?!</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! Form::open(['route' => ['routine_cell_delete'],'style' => 'display:inline']) !!}
                                                        {!! Form::hidden('id',$routine_id) !!}

                                                        {!! Form::submit('Yes', ['class' => 'btn btn-lg btn-danger']) !!}

                                                        <button type="button" class="btn btn-lg btn-primary waves-effect waves-light" data-toggle="modal" data-target=".data_delete_{{ $routine_id }}"> Cancel </button>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        @endif
                                        </span>
                                        @endif

                                        @endforeach



                                        @if(($request_check && ($request_check->request_status == "active" && $request_check->expired_date >= now())) || Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin' || Auth::user()->in_committee == 'yes')
                                        <span class="d-block text-center">
                                            <!-- Always show Assign button -->
                                            <button type="button" class="m-2 btn btn-sm btn-primary data_modal" data-toggle="modal" data-target=".bs-example-modal-center{{ 'batch'.$section->batch_id.'_section'.$section->section_id.'_day'.$slot->day_title.'_time'.$timeslot->time_slot->id  }}">
                                                Assign
                                            </button>

                                            <!-- Show Proxy button only if routine (assigned teacher) exists -->
                                            @if(isset($routine) && $routine_id)
                                            <button type="button" class="m-2 btn btn-sm btn-info" data-toggle="modal" data-target=".proxy_modal{{ 'batch'.$section->batch_id.'_section'.$section->section_id.'_day'.$slot->day_title.'_time'.$timeslot->time_slot->id }}">
                                                Proxy
                                            </button>
                                            @endif



                                            @if(isset($routine_id) && $routine_id)
                                            <!-- Delete Button -->
                                            <button type="button" class="m-2 btn btn-sm btn-danger" data-toggle="modal" data-target=".data_delete_{{ $routine_id }}">
                                                Delete
                                            </button>
                                            @endif
                                        </span>

                                        @endif


                                        <div class="modal fade bs-example-modal-center{{ 'batch'.$section->batch_id.'_section'.$section->section_id.'_day'.$slot->day_title.'_time'.$timeslot->time_slot->id  }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">

                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <span class="d-block font-weight-bold font-14">
                                                            Day - {{ $slot->day_title }} |
                                                            Batch - {{ $section->department_name.($section->section_name) }}
                                                            |
                                                            Time Range - {{ date('g:i a', strtotime($timeslot->time_slot->from)).'-'.date('g:i a', strtotime($timeslot->time_slot->to)) }}
                                                        </span>
                                                    </div>

                                                    <div class="modal-body">
                                                        {!! Form::open(['route' => ['routine_create'],'style' => 'display:inline', 'class'=> 'form']) !!}
                                                        {{-- {{ $routine_id }}--}}
                                                        <input type="hidden" name="yearly_session_id" value="{{ $yearly_session }}">
                                                        <input type="hidden" name="batch_id" value="{{ $section->batch_id }}">
                                                        <input type="hidden" name="section_id" value="{{ $section->section_id }}">
                                                        <input type="hidden" name="day_id" value="{{ $timeslot->day_id }}">
                                                        <input type="hidden" name="time_slot_id" value="{{ $timeslot->time_slot_id }}">
                                                        <input type="hidden" name="routine_id" value="{{ $routine_id }}">
                                                        <div class="alert_box"></div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="">Teacher</label>
                                                                    <select name="teacher_id" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        @foreach($teachers as $teacher)
                                                                        <option value="{{$teacher->id}}" {{ $teacher_id ==  $teacher->id ? 'selected' : '' }}>{{ $teacher->user->firstname.'-'.$teacher->user->lastname}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">

                                                                    <label for="">Course</label>
                                                                    <div class="course_alert"></div>
                                                                    <select data-batch="{{ $section->batch_id }}" data-section="{{$section->section_id}}" data-time="{{ $timeslot->time_slot_id }}" data-day="{{ $timeslot->day_id }}" name="course_id" class="course form-control" required>
                                                                        <option value="">Select</option>
                                                                        @foreach($courses as $course)
                                                                        <option value="{{$course->id}}" {{ $course_id ==  $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="additional_slot"></div>
                                                                </div>

                                                            </div>


                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="">Room</label>
                                                                    <select name="room_id" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        @foreach($rooms as $room)
                                                                        <option value="{{$room->id}}" {{ $room_id ==  $room->id ? 'selected' : '' }}>{{ $room->building.'-'.$room->room_no }} {{ $room->room_type == 0 ? '' : '- Lab' }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        {!! Form::submit('Assign', ['class' => 'btn btn-lg btn-danger']) !!}
                                                        {!! Form::close() !!}
                                                        <button type="button" class="btn btn-lg btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-center{{ 'batch'.$section->batch_id.'_section'.$section->section_id.'_day'.$slot->day_title.'_time'.$timeslot->time_slot->id  }}"> Cancel </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Proxy Modal -->
                                        <div class="modal fade proxy_modal{{ 'batch'.$section->batch_id.'_section'.$section->section_id.'_day'.$slot->day_title.'_time'.$timeslot->time_slot->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Assign Proxy Teacher</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('proxy.store') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="full_routine_id" value="{{ $routine_id }}">
                                                            <!-- <input type="hidden" name="proxy_date" value="{{ now()->format('Y-m-d') }}"> -->
                                                            <div class="form-group">
                                                                <label for="proxy_date">Proxy Date</label>
                                                                <input type="date" name="proxy_date" class="form-control" required value="{{ old('proxy_date', now()->format('Y-m-d')) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="proxy_teacher_id">Select Teacher</label>
                                                                <select name="proxy_teacher_id" class="form-control" required>
                                                                    <option value="">Select</option>
                                                                    @foreach($teachers as $teacher)

                                                                    <option value="{{ $teacher->id }}"
                                                                        {{ old('proxy_teacher_id', $proxy->proxy_teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                                                        {{ $teacher->user->firstname.' '.$teacher->user->lastname }}
                                                                    </option>

                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <button type="submit" class="btn btn-success">Save Proxy</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if(!isset($routine_id) || !$routine_id)
                                        @if(!(($request_check && ($request_check->request_status == "active" && $request_check->expired_date >= now())) || Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin' || Auth::user()->in_committee == 'yes'))
                                        <span class="d-block text-center py-2" style="background-color: #e6f7ff; color: #007bff; border-radius: 4px; font-weight: 600;">
                                            ---
                                        </span>
                                        @endif
                                        @endif



                                    </td>
                                    @endif
                                    @endforeach

                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                        @endforeach

                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
    </div>
    <!-- end container-fluid -->
</div>
<!-- end page content-->

</div>
<!-- page wrapper end -->
@endsection


@push('script')
<script>
    let class_slots = document.querySelectorAll('.class_slot');
    class_slots.forEach((class_slot) => {
        class_slot.addEventListener('blur', function(e) {
            let total_slot = e.target.value;
            let id = e.target.dataset.id;
            let csrf = "{{ csrf_token() }}";
            let data = {
                'total_slot': total_slot,
                'id': id,
                "_token": csrf
            }
            $.ajax({
                type: "post",
                url: '{{route("class_slot_update")}}',
                data: data,
                dataType: "json",
                success: function(data) {
                    if (data.type == 'success') {
                        console.log('success');
                    }
                }
            });
        })
    })
</script>

<script>
    let forms = document.querySelectorAll('.form');
    forms.forEach((form) => {
        $(form).on('submit', function(e) {

            e.preventDefault();
            let alertBox = e.target.querySelector('.alert_box');
            let data = $(this).serialize();
            $.ajax({
                type: "post",
                url: '{{route("routine_create")}}',
                data: data,
                dataType: "json",
                success: function(data) {
                    if (data.type == 'error') {
                        // alertBox.innerHTML = `<div class="alert-dismissable alert alert-danger">
                        //         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x
                        //         </button><strong>${data.text}</strong></div>`;
                        alert(data.text);
                    } else {
                        alert(data.text);
                        document.location.reload(true)
                    }
                }
                // error: function(error) {
                //     alert('error');
                // }
            });
        });
    })
</script>

<script>
    let courses = document.querySelectorAll('.course');
    // console.log(courses);
    courses.forEach((course) => {
        course.addEventListener('change', function(e) {

            //Check if course type is lab or theory
            let additional_slot = e.target.parentNode.querySelector('.additional_slot');
            additional_slot.innerHTML = '';
            let course_alert = e.target.parentNode.querySelector('.course_alert');
            course_alert.innerHTML = '';
            let id = e.target.value;
            let submitBtn = $(this).closest('form').find(':input[type=submit]');
            let currentForm = $(this).closest('form');

            let time_slot_id = e.target.dataset.time;
            let day_id = e.target.dataset.day;
            let batch = e.target.dataset.batch;
            let section = e.target.dataset.section;
            let csrf = "{{ csrf_token() }}";
            let data = {
                'batch_id': batch,
                'section_id': section,
                'time_slot_id': time_slot_id,
                'id': id,
                'day_id': day_id,
                "_token": csrf
            }
            $.ajax({
                type: "post",
                url: '{{route("course_check")}}',
                data: data,
                dataType: "json",
                success: function(data) {
                    if (data) {
                        if (data.msg) {
                            course_alert.innerHTML = `<div class="alert-dismissable alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x
                                    </button><strong>${data.msg}</strong></div>`;
                            submitBtn.prop('disabled', true);
                        } else {
                            submitBtn.prop('disabled', false);
                            let html = '<div class="mt-3"><label for="">Additional Slot</label><select name="additional_time_slot"  class="form-control">';
                            html += `<option value="">Select Additional Slot</option>`
                            Object.keys(data).forEach(function(key) {
                                html += `<option value="${data[key].id}">${data[key].from}-${data[key].to}</option>`
                            });
                            html += '</select><div>';
                            additional_slot.innerHTML = html;
                        }
                    }
                }
            });

        })
    })
</script>


<script>
    window.onscroll = function() {
        myFunction()
    };

    let header = document.getElementById("teacher_day_count");
    let sticky = header.offsetTop;

    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }
</script>

@endpush