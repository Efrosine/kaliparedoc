<x-layouts.app>
    <x-slot name="header">
        Document Preview
    </x-slot>

    <div class="py-4">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Document Preview</h2>
            <div>
                <a href="{{ route('admin.documents.show', $document) }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Back to Document Details
                </a>
                <a href="{{ route('admin.documents.approve', $document) }}"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition ml-2"
                    onclick="event.preventDefault(); document.getElementById('approve-form').submit();">
                    Approve Document
                </a>
                <form id="approve-form" action="{{ route('admin.documents.approve', $document) }}" method="POST"
                    class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        @include('components.alert')

        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h5 class="text-lg font-semibold">Edit Data Kartu Keluarga</h5>
            </div>
            <div class="p-4">
                @if($kk)
                    <form method="POST" action="{{ route('admin.documents.preview', $document) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Nama Kepala Keluarga</label>
                                <input type="text" name="kk[nama_kepala_keluarga]"
                                    value="{{ old('kk.nama_kepala_keluarga', $kk->nama_kepala_keluarga) }}"
                                    class="form-input w-full" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Alamat Jalan</label>
                                <input type="text" name="kk[alamat_jalan]"
                                    value="{{ old('kk.alamat_jalan', $kk->alamat_jalan) }}" class="form-input w-full"
                                    required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium">RT</label>
                                <input type="text" name="kk[rt]" value="{{ old('kk.rt', $kk->rt) }}"
                                    class="form-input w-full" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium">RW</label>
                                <input type="text" name="kk[rw]" value="{{ old('kk.rw', $kk->rw) }}"
                                    class="form-input w-full" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Kode Pos</label>
                                <input type="text" name="kk[kode_pos]" value="{{ old('kk.kode_pos', $kk->kode_pos) }}"
                                    class="form-input w-full" required />
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Update Data KK</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h5 class="text-lg font-semibold">Edit Data Anggota Keluarga</h5>
            </div>
            <div class="p-4">
                @if($anggota && $anggota->count())
                    <form method="POST" action="{{ route('admin.documents.preview', $document) }}">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-2 py-1">No</th>
                                        <th class="px-2 py-1">Nama</th>
                                        <th class="px-2 py-1">NIK</th>
                                        <th class="px-2 py-1">Jenis Kelamin</th>
                                        <th class="px-2 py-1">Tempat Lahir</th>
                                        <th class="px-2 py-1">Tanggal Lahir</th>
                                        <th class="px-2 py-1">Golongan Darah</th>
                                        <th class="px-2 py-1">Agama</th>
                                        <th class="px-2 py-1">Status Perkawinan</th>
                                        <th class="px-2 py-1">Status Hubungan</th>
                                        <th class="px-2 py-1">Pendidikan</th>
                                        <th class="px-2 py-1">Pekerjaan</th>
                                        <th class="px-2 py-1">Nama Ibu</th>
                                        <th class="px-2 py-1">Nama Ayah</th>
                                        <th class="px-2 py-1">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($anggota as $i => $agt)
                                        <tr>
                                            <td class="px-2 py-1">{{ $agt->no_urut }}</td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][nama]"
                                                    value="{{ old('anggota.' . $i . '.nama', $agt->nama) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][nik]"
                                                    value="{{ old('anggota.' . $i . '.nik', $agt->nik) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][jenis_kelamin]"
                                                    value="{{ old('anggota.' . $i . '.jenis_kelamin', $agt->jenis_kelamin) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][tempat_lahir]"
                                                    value="{{ old('anggota.' . $i . '.tempat_lahir', $agt->tempat_lahir) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="date" name="anggota[{{ $i }}][tanggal_lahir]"
                                                    value="{{ old('anggota.' . $i . '.tanggal_lahir', $agt->tanggal_lahir) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][golongan_darah]"
                                                    value="{{ old('anggota.' . $i . '.golongan_darah', $agt->golongan_darah) }}"
                                                    class="form-input w-full" /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][agama]"
                                                    value="{{ old('anggota.' . $i . '.agama', $agt->agama) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][status_perkawinan]"
                                                    value="{{ old('anggota.' . $i . '.status_perkawinan', $agt->status_perkawinan) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="text"
                                                    name="anggota[{{ $i }}][status_hubungan_dalam_keluarga]"
                                                    value="{{ old('anggota.' . $i . '.status_hubungan_dalam_keluarga', $agt->status_hubungan_dalam_keluarga) }}"
                                                    class="form-input w-full" required /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][pendidikan]"
                                                    value="{{ old('anggota.' . $i . '.pendidikan', $agt->pendidikan) }}"
                                                    class="form-input w-full" /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][pekerjaan]"
                                                    value="{{ old('anggota.' . $i . '.pekerjaan', $agt->pekerjaan) }}"
                                                    class="form-input w-full" /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][nama_ibu]"
                                                    value="{{ old('anggota.' . $i . '.nama_ibu', $agt->nama_ibu) }}"
                                                    class="form-input w-full" /></td>
                                            <td class="px-2 py-1"><input type="text" name="anggota[{{ $i }}][nama_ayah]"
                                                    value="{{ old('anggota.' . $i . '.nama_ayah', $agt->nama_ayah) }}"
                                                    class="form-input w-full" /></td>
                                            <td class="px-2 py-1 text-center">-</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Update Data
                                Anggota</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h5 class="text-lg font-semibold">Document Preview</h5>
                <p class="text-sm text-gray-500 mt-1">This is how the document will look after approval. Document number
                    will be
                    generated upon approval.</p>
            </div>
            <div class="p-4">
                <div class="border p-4 bg-white" style="min-height: 700px;">
                    {!! $preview !!}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>