@if (Session::has('flash_notification.message'))
    <div class="alert alert-{{ Session::get('flash_notification.level') }} {{ Session::has('flash_notification.important') ? 'alert-important' : '' }}">
        {{ Session::get('flash_notification.message') }}
    </div>
@endif
@if (Session::has('status'))
    <div class="alert alert-success alert-important">
        {{ Session::get('status') }}
    </div>
@endif
@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-important">{{ $error }}</div>
    @endforeach
@endif