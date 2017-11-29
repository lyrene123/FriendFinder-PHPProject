@if (Session::has('messages'))
    <div class="alert alert-success">
        <ul>
            <li>{{ Session::get('messages')}}</li>
        </ul>
    </div>
@endif