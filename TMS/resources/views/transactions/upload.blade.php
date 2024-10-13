<x-app-layout>
<form action="{{ route('transactions.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="receipt" required>
    <button type="submit">Upload Receipt</button>
</form>

@if(session('extractedText'))
<div>
    <h2>Extracted Text from Receipt:</h2>
    <pre>{{ session('extractedText') }}</pre>
</div>
@endif
</x-app-layout>