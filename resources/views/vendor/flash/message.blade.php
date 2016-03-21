@if (Session::has('flash_notification.message'))
    @if (Session::has('flash_notification.overlay'))
        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
    @else
        <div class="alert alert-{{ Session::get('flash_notification.level') }} {{ Session::has('flash_notification.important') ? 'alert-important' : '' }}">
            {{ Session::get('flash_notification.message') }}
        </div>
    @endif
@endif
@if (Session::has('status'))
    <div class="alert alert-success alert-important">
        {{ Session::get('status') }}
    </div>
@endif
@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        @if (!str_contains($error, 'g-recaptcha-response'))
            <div class="alert alert-danger alert-important">{{ $error }}</div>
        @endif
    @endforeach
@endif