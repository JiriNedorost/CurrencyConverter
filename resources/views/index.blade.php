<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/css/app.css" rel="stylesheet">
    <title>Currency Conversion Calculator</title>
</head>

<body class="bg-secondary text-white">
    <div class="bg-light text-dark">
    <h1>Currency Conversion Calculator</h1>
    <div>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('index') }}">
            @csrf
            <label for="amount">Amount:</label>
            <input name="amount" id="amount" type="number" step="0.01" value="{{ old('amount') }}">


            <label for="from">From:</label>
            <input name="from" list="from" placeholder="USD - United States Dollar"  value="{{ old('from') }}">
            <datalist id="from">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency }}">
                @endforeach
            </datalist>

            <label for="to">To:</label>
            <input name="to" list="to" placeholder="EUR - Euro" value="{{ old('to') }}">
            <datalist id="to">
                @foreach ($currencies as $currency)
                    <option value="{{ $currency }}">
                @endforeach
            </datalist>

            <input type="submit" id="submit" value="Convert">
        </form>
    </div>

    @isset($convertedAmount, $convertedTo)
    <h2 class="m-3 alert alert-success">
        {{ $originalAmount }} {{ $convertedFrom }}
        <br> = <br>
        {{ $convertedAmount }} {{ $convertedTo }}

    </h2>        
    @endisset
    
    <hr>
    </div>
    <ul>
        <li> Most popular currency: {{ $mostConvertedCurr }}</li>
        <li> Total converted: {{ $totalConverted }} USD</li>
        <li> Total conversion requests: {{ $totalConversions }}</li>
    </ul>


</body>

</html>