@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Contacts</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('contacts.form') }}" class="btn btn-primary mb-3">Add Contact</a>

    <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="xml_file" required>
            @error('xml_file')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-secondary">Import XML</button>
    </form>

    <table class="table mt-3">
        <thead>
            <tr><th>Name</th><th>Phone</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>
                        <a href="{{ route('contacts.form', encrypt($contact->id)) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this contact?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger">Delete</button>
                      </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $contacts->links() }}
</div>
@endsection
