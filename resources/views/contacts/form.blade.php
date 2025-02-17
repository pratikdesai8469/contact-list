@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($contact) ? 'Edit Contact' : 'Add Contact' }}</h2>

    <form action="{{ route('contacts.save') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $contact->id ?? '' }}">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $contact->name ?? '') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $contact->phone ?? '') }}" required>
            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">
            {{ isset($contact) ? 'Update' : 'Save' }}
        </button>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
