@if ($alerts = Session::get('alerts'))
    @foreach ($alerts as $alert)
        <div class="alert alert-{{$alert['level']}}">
            <p>{{ $alert['message'] }}</p>
        </div>
    @endforeach
@endif