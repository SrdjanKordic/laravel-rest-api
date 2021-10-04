<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    window.opener.postMessage({ 
        token:"{{ $token }}" },
        "http://localhost:8080/login"
    )
   window.close()
  </script>
</head>
<body>
   
</body>
</html>