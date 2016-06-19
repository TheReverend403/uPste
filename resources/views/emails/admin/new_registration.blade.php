{{ $name }} ({{ $email }}) has just registered at {{ config('upste.site_name') }}
@if (config('upste.require_user_approval'))

Check pending requests at {{ route('admin.requests') }}
@endif