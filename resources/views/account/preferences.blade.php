@extends('layouts.account')

@section('title', 'My Preferences')

@section('content')
    <div class="container-sm">
        <form action="{{ route('account.preferences') }}" method="POST" class="form-horizontal">
            <div class="form-group">
                <label for="pref-timezone">Timezone</label>
                <select class="form-control" name="timezone" id="pref-timezone">
                    @foreach(timezone_identifiers_list() as $timezone)
                        @if ($timezone == $user_preferences->timezone)
                            <option selected="selected">{{ $timezone }}</option>
                        @else
                            <option>{{ $timezone }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="pref-pagination-items">Uploads Per Page</label>
                <input class="form-control" type="text" name="pagination-items" id="pref-pagination-items" placeholder="Enter a number between 3 and 26" value="{{ $user_preferences->pagination_items }}">
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-success">Save</button>
            </div>

            {{ csrf_field() }}
        </form>
    </div>
@endsection