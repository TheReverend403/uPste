<p>{{ $name }} ({{ $email }}) just registered at {{ env('DOMAIN') }}</p>
<a href="{{ route('admin.requests') }}">Click here to check pending requests.</a>