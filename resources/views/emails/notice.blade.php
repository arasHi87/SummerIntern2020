<table class="table">
    <thead>
        <tr>
            <th scope="col">Title</th>
            <th scope="col">Start time</th>
            <th scope="col">End time</th>
        </tr>
    </thead>
    <tbody>
        @foreach($events as $event)
            <tr>
                <th scope="row">{{ $event['title'] }}</th>
                <td>{{ $event['start_time'] }}</td>
                <td>{{ $event['end_time'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
