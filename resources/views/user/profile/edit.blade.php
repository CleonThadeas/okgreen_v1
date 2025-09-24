@extends('layouts.app')

@section('title','Edit Profil')

@section('content')
<div style="display:flex;">
    {{-- Sidebar --}}
    @include('user.profile.sidebar')

    {{-- Main Content --}}
    <div style="flex:1; padding:20px;">
        <h2>Informasi Pribadi</h2>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <p>
                <label>Nama Lengkap:</label><br>
                <input type="text" name="name" value="{{ old('name', $user->name) }}">
            </p>

            <p>
                <label>Email (tidak bisa diubah):</label><br>
                <input type="email" value="{{ $user->email }}" disabled>
            </p>

            <p>
                <label>Nomor Telepon:</label><br>
                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
            </p>

            <p>
                <label>Alamat:</label><br>
                <textarea name="address">{{ old('address', $user->address) }}</textarea>
            </p>

            <p>
                <label>Tanggal Lahir:</label><br>
                <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
            </p>

            <p>
                <label>Jenis Kelamin:</label><br>
                <select name="gender">
                    <option value="">-- Pilih --</option>
                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Tidak ingin memberitahu</option>
                </select>
            </p>

            <p>
                <label>Password Baru (opsional):</label><br>
                <input type="password" name="password">
            </p>

            <button type="submit" style="background:#4CAF50; color:white; border:none; padding:8px 15px;">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
