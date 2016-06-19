<div class="jumbotron">
    <p>Your API key is:</p>
    <pre class="apikey">{{ Auth::user()->apikey }}</pre>
    <p>This key allows anyone to upload to {{ config('upste.domain') }} as you. Do not let anyone else see it.</p>
    <form action="{{ route('account.resetkey') }}" method="POST">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-danger">Reset Key</button>
        </div>
        {!! csrf_field() !!}
    </form>
</div>
<h3>API Methods</h3>
<p>All API methods require your API key as a parameter named <code>key</code>, either as a form field for POSTs or a GET parameter for GET requests.</p>
<hr>
<h4 class="text-info">POST {{ route('api.upload') }}</h4>
<p class="text-muted">Upload a file to your account and get a link to the file for sharing.</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Parameter</th>
        <th>Description</th>
        <th>Required</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>file</td>
        <td>The file you wish to upload.</td>
        <td>Yes</td>
    </tr>
    </tbody>
</table>
<p>Example</p>
<pre>curl \
-F key={{ Auth::user()->apikey }} \
-F file=@example.png \
{{ route('api.upload') }}</pre>
<hr>
<h4 class="text-info">GET {{ route('api.upload') }}</h4>
<p class="text-muted">Returns a JSON array of your uploads.</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Parameter</th>
        <th>Description</th>
        <th>Required</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>limit</td>
        <td>Limit the amount of uploads returned by the API.</td>
        <td>No</td>
    </tr>
    </tbody>
</table>
<p>Example</p>
<pre>curl {{ route('api.upload', ['key' => Auth::user()->apikey, 'limit' => 15]) }}</pre>
