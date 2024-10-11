<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>SB Admin 2 - Dashboard</title>

@if(isset($config['css'])&& is_array($config['css']))
    @foreach($config['css'] as $key => $val)
        <link href="{{ asset($val) }}" rel="stylesheet">
    @endforeach
@endif