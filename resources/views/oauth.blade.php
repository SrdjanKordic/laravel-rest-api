<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    window.opener.postMessage({ 
        user: { 
            id: {{ $user->id }},
            name: '{{ $user->name }}',
            email: '{{ $user->email }}',
            email_verified_at: '{{ $user->email_verified_at }}',
            created_at: '{{ $user->created_at }}',
            updated_at: '{{ $user->updated_at }}',
            avatar: '{{ $avatar }}'
        },
        token:"{{ $token }}" },
        "http://localhost:8081/login"
    )
    window.close()
  </script>
</head>
<body>
    {{ $user->providers }}
</body>
</html>