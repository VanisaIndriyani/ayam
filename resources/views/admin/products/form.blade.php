<div class="row g-4">

    {{-- KATEGORI --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Kategori</label>
        <select name="category_id" class="form-select">
            <option value="">-- Tanpa Kategori --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- NAMA --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nama Produk</label>
        <input type="text" name="name"
               class="form-control"
               value="{{ old('name', $product->name ?? '') }}">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- HARGA --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Harga (Rp)</label>
        <input type="number" name="price" class="form-control"
               value="{{ old('price', $product->price ?? '') }}">
        @error('price')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- STOK --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Stok</label>
        <input type="number" name="stock" class="form-control"
               value="{{ old('stock', $product->stock ?? '') }}">
        @error('stock')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- BERAT --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Berat (gram)</label>
        <input type="number" name="weight" class="form-control"
               value="{{ old('weight', $product->weight ?? '') }}">
        @error('weight')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- STATUS --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">Status Produk</label>
        <select name="is_active" class="form-select">
            <option value="1">Aktif</option>
            <option value="0"
                {{ old('is_active', $product->is_active ?? 1) == 0 ? 'selected' : '' }}>
                Nonaktif
            </option>
        </select>
        @error('is_active')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- DESKRIPSI --}}
    <div class="col-12">
        <label class="form-label fw-semibold">Deskripsi</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- GAMBAR --}}
    <div class="col-12">
        <label class="form-label fw-semibold">Gambar Produk (Multiple)</label>
        <input type="file" name="images[]" multiple class="form-control">
        
        @error('images')
            <small class="text-danger">{{ $message }}</small>
        @enderror

        @error('images.*')
            <small class="text-danger d-block">{{ $message }}</small>
        @enderror
    </div>

</div>
