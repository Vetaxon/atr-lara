@extends('layout.main')
@section('title', 'Welcome to the task')
@section('content')
    <div class="instructions">
        <img src="/images/Logo.png" alt="Logo" title="logo">

        <h1>Welcome to the export challenge.</h1>

        <p>What we have here is an incomplete export system based on a small set of student data. </p>

        <p>The mission, if you choose to accept it, is to fill in the missing parts and finish the application to the best
            of your ability.</p>

        <p>How you do this is completely down to you and we have only provided the bare bones.</p>

        <p>There is no right or wrong however we would prefer you didn't rely on third party packages, don't be afraid to
            impress us.</p>

        <p>Although this is a relatively small task we believe there is enough here for you to be able to demonstrate your
            ability to follow best practices and show your understanding of the Laravel framework.</p>

        <p>Oh and there may be some 'deliberate' errors in the code... Enjoy.</p>

        <p><a href="{{url('view')}}" title="task">Click here to continue to the challenge</a></p>

    </div>
@endsection
