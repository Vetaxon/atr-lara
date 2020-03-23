@extends('layout.main')
@section('title', 'Welcome to the task')
@section('content')
    <div class="header">
        <div><img src="/images/logo_sm.jpg" alt="Logo" title="logo"></div>
        <div  style='margin: 10px;  text-align: left'>
            <input type="button" id="select-all" value="Select All" />
            <input type="button" class="js-export" data-href="{{ route('export') }}" value="Export"/>
            <input type="button" class="js-export" data-href="{{ route('exportAttendance') }}" value="Export Course Attendance"/>
        </div>
    </div>
    <form>
        <div style='margin: 10px; text-align: center;'>
            <table class="student-table">
                <tr>
                    <th></th>
                @foreach(array_keys($map) as $name)
                        <th>{{$name}}</th>
                @endforeach
                </tr>

                @if(  count($students) > 0 )
                    @foreach($students as $student)
                        <tr>
                            <td><input type="checkbox" name="studentId" value="{{$student['id']}}"></td>
                            @foreach($map as $action)
                                <td style=' text-align: left;'>{{$action($student)}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" style="text-align: center">Oh dear, no data found.</td>
                    </tr>
                @endif
            </table>
        </div>
    </form>
@endsection
