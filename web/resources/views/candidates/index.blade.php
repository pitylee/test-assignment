<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <title>MZT test assignment</title>

   @include('partials/head')
</head>

<body>
   <div id="app">
      <div class="w-full p-6 bg-teal-100 text-right font-bold">Your wallet has: {{$coins ?? '?' }} coins</div>
      <candidates :candidates="{{ json_encode($candidates) }}">
      </candidates>
   </div>

   @include('partials/js')
</body>

</html>