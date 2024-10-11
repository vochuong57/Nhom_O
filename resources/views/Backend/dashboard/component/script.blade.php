@if(isset($config['js'])&& is_array($config['js']))
    @foreach($config['js'] as $key => $val)
        <script src=" {{ asset($val) }} "></script>
    @endforeach
@endif