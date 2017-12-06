<!-- The following blade to check whether or not a message is available to display in the view
        and if yes, then display each message-->
@if (Session::has('messages'))
    <div class="alert alert-success">
        <ul>
            <li>{{ Session::get('messages')}}</li>
        </ul>
    </div>
@endif