<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3"></i>data form pengajuan</span>
                        </a>
                        <div class="pull-right">
                            @if (in_array(auth()->user()->role->role_type,['member']))
                            @if ($form_active)
                            <button class="btn btn-danger btn-sm" wire:click="toggleForm(false)"><i class="fas fa-times"></i> Cancel</button>
                            @else
                            <button class="btn btn-primary btn-sm" wire:click="{{$modal ? 'showModal' : 'toggleForm(true)'}}"><i class="fas fa-plus"></i> Add
                                New</button>
                            @endif
                            @endif

                        </div>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if ($form_active)
            <div class="card">
                <div class="card-body row">
                    <div class="col-md-6">
                        {{--
                        <x-text-field type="text" name="name" label="Nama" readonly /> --}}
                        {{--
                        <x-text-field type="text" name="username" label="NRP" readonly /> --}}
                        {{--
                        <x-text-field type="text" name="pangkat" label="Pangkat" readonly /> --}}
                        <x-select name="jenis" label="Jenis Pengajuan" class="form-control">
                            <option value="">Jenis Pengajuan</option>
                            <option value="CUTI">CUTI</option>
                            <option value="IZIN">IZIN</option>
                        </x-select>
                        <x-textarea type="text" name="pengikut" label="Pengikut" />
                        <x-text-field type="date" name="tanggal_berangkat" label="Tanggal Berangkat" />
                    </div>
                    <div class="col-md-6">
                        <x-text-field type="date" name="tanggal_kembali" label="Tanggal Kembali" />
                        <x-text-field type="text" name="tujuan" label="Tempat Tujuan" />
                        <x-text-field type="text" name="transportasi" label="Transportasi" />
                    </div>

                    @if ($jenis == 'CUTI')
                    <div class="col-md-12">
                        <x-text-field type="text" name="keperluan" label="Keperluan" />
                        <x-input-file file="{{$file_jasmani}}" path="{{optional($file_jasmani_path)->getClientOriginalName()}}" name="file_jasmani_path" label="File Jasmani" />
                        <x-input-file file="{{$file_kesehatan}}" path="{{optional($file_kesehatan_path)->getClientOriginalName()}}" name="file_kesehatan_path" label="File Kesehatan" />
                    </div>
                    @endif



                    <div class="form-group">
                        <button class="btn btn-primary pull-right" wire:click="{{$update_mode ? 'update' : 'store'}}">Simpan</button>
                    </div>
                </div>
            </div>
            @else
            <livewire:table.data-form-pengajuan-table params="{{$route_name}}" />
            @endif

        </div>

        {{-- Modal confirm --}}
        <div id="confirm-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Konfirmasi Hapus</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin hapus data ini.?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" wire:click='delete' class="btn btn-danger btn-sm"><i class="fa fa-check pr-2"></i>Ya, Hapus</button>
                        <button class="btn btn-primary btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- modal detail --}}
        <div id="detail-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Detail Pengajuan</h5>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Status
                                <span>{{$status}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Nama Lengkap
                                <span>{{$name}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                NRP
                                <span>{{$username}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Pangkat
                                <span>{{$pangkat}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tanggal Berangkat
                                <span>{{$tanggal_berangkat}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tanggal Kembali
                                <span>{{$tanggal_kembali}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tujuan
                                <span>{{$tujuan}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Pengikut
                                <span>{{$pengikut ?? '-'}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Transportasi
                                <span>{{$transportasi ?? '-'}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Keperluan
                                <span>{{$keperluan ?? '-'}}</span>
                            </li>

                            @if ($jenis == 'CUTI')
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                File Jasmani
                                <a href={{$file_jasmani}} target="_blank">Lihat File</a>
                            </li>
                            @endif
                            @if ($jenis == 'CUTI')
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                File Kesehatan
                                <a href={{$file_kesehatan}} target="_blank">Lihat File</a>
                            </li>
                            @endif

                            @if ($status == 'diusulkan')
                            @if (in_array(auth()->user()->role->role_type,['admin','superadmin']))
                            <li class="list-group-item">
                                <x-text-field type="text" name="keterangan" label="Keterangan" />
                            </li>
                            @endif
                            @endif
                        </ul>
                    </div>
                    <div class="modal-footer">
                        @if ($status == 'draft')
                        @if (in_array(auth()->user()->role->role_type,['admin','superadmin']))
                        <div>
                            <button type="submit" wire:click="updateStatus('diusulkan')" class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Diusulkan</button>
                        </div>
                        @endif
                        @endif
                        @if ($status == 'diusulkan')
                        @if (in_array(auth()->user()->role->role_type,['admin','superadmin']))
                        <div>
                            <button type="submit" wire:click="updateStatus('disetujui')" class="btn btn-success btn-sm"><i class="fa fa-check pr-2"></i>Terima</button>
                            <button class="btn btn-warning btn-sm" wire:click="updateStatus('ditolak')"><i class="fa fa-times pr-2"></i>Tolak</button>
                        </div>
                        @endif
                        @endif
                        <button class="btn btn-danger btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')



    <script>
        document.addEventListener('livewire:load', function(e) {
            window.livewire.on('loadForm', (data) => {
                
                
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
                $('#detail-modal').modal('hide')
            });

            window.livewire.on('showModalConfirm', (data) => {
                $('#confirm-modal').modal(data)
            });

            window.livewire.on('showModalDetail', (data) => {
                $('#detail-modal').modal(data)
            });
        })
    </script>
    @endpush
</div>