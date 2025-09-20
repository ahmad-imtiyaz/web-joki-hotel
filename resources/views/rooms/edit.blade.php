{{-- File: resources/views/rooms/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-edit me-2"></i>Edit Kamar: {{ $room->room_number }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="room_number" class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                       id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga per Jam <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price', $room->price) }}" min="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="room_name" class="form-label">Nama Kamar <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('room_name') is-invalid @enderror" 
                               id="room_name" name="room_name" value="{{ old('room_name', $room->room_name) }}" required>
                        @error('room_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="available" {{ old('status', $room->status) === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="occupied" {{ old('status', $room->status) === 'occupied' ? 'selected' : '' }}>Terisi</option>
                            <option value="cleaning" {{ old('status', $room->status) === 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                            <option value="maintenance" {{ old('status', $room->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Kamar</label>
                        @if($room->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $room->image) }}" class="img-thumbnail" style="max-width: 200px;" alt="Current Image">
                                <div class="form-text">Gambar saat ini</div>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('rooms.manage') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Kamar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection