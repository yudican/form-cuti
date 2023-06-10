<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3 text-capitalize"></i>data karyawan</span>
                        </a>
                        <div class="pull-right">
                            @if (!$form && !$modal)
                            <button class="btn btn-danger btn-sm" wire:click="toggleForm(false)"><i class="fas fa-times"></i> Cancel</button>
                            @else
                            <button class="btn btn-primary btn-sm" wire:click="{{$modal ? 'showModal' : 'toggleForm(true)'}}"><i class="fas fa-plus"></i> Add
                                New</button>
                            @endif
                        </div>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <livewire:table.data-karyawan-table params="{{$route_name}}" />
        </div>

        {{-- Modal form --}}
        <div id="form-modal" wire:ignore.self class="modal fade" tabindex="-1" permission="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize" id="my-modal-title">{{$update_mode ? 'Update' : 'Tambah'}} data karyawan</h5>
                    </div>
                    <div class="modal-body">
                        <x-text-field type="text" name="name" label="Name" />
                        <x-text-field type="text" name="username" label="NRP" />
                        <x-text-field type="text" name="pangkat" label="Pangkat" />
                        <select wire:model="satker" class="form-control">
                            <option value="">Select Satker</option>
                            <option value="Sahli">Sahli</option>
                            <option value="Srena">Srena</option>
                            <option value="Sintel">Sintel</option>
                            <option value="Inspektorat">Inspektorat</option>
                            <option value="Sops">Sops</option>
                            <option value="Satkar">Satkar</option>

                            <option value="Spers">Spers</option>
                            <option value="Slog">Slog</option>
                            <option value="Spotmar">Spotmar</option>
                            <option value="Skomlek">Skomlek</option>
                            <option value="Smin">Smin</option>
                            <option value="Denma">Denma</option>

                            <option value="Setum">Setum</option>
                            <option value="Puskodal">Puskodal</option>
                            <option value="Dispen">Dispen</option>
                            <option value="Diskomlek">Diskomlek</option>
                            <option value="Dispotmar">Dispotmar</option>
                            <option value="Diskum">Diskum</option>

                            <option value="Disminpers">Disminpers</option>
                            <option value="Diskes">Diskes</option>
                            <option value="POM">POM</option>
                            <option value="Dismatbek">Dismatbek</option>
                            <option value="Disharkap">Disharkap</option>
                            <option value="Kuwil">Kuwil</option>

                            <option value="Akun">Akun</option>
                            <option value="Disinfolahta">Disinfolahta</option>
                            <option value="Dislambair">Dislambair</option>
                            <option value="Denintel">Denintel</option>
                            <option value="Satkor">Satkor</option>
                            <option value="Satfib">Satfib</option>

                            <option value="Satram">Satram</option>
                            <option value="Satban">Satban</option>
                            <option value="Satkopaska">Satkopaska</option>
                            <option value="Kolat">Kolat</option>
                            <option value="Satur">Satur</option>
                            <option value="Satmar">Satmar</option>
                        </select>
                        <x-text-field type="date" name="tahun_masuk" label="Tahun Masuk" />
                    </div>
                    <div class="modal-footer">

                        <button type="button" wire:click={{$update_mode ? 'update' : 'store' }} class="btn btn-primary btn-sm"><i class="fa fa-check pr-2"></i>Simpan</button>

                        <button class="btn btn-danger btn-sm" wire:click='_reset'><i class="fa fa-times pr-2"></i>Batal</a>

                    </div>
                </div>
            </div>
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
    </div>
    @push('scripts')



    <script>
        document.addEventListener('livewire:load', function(e) {
             window.livewire.on('loadForm', (data) => {
                
                
            });
            window.livewire.on('showModal', (data) => {
                $('#form-modal').modal('show')
            });

            window.livewire.on('closeModal', (data) => {
                $('#confirm-modal').modal('hide')
                $('#form-modal').modal('hide')
            });

            window.livewire.on('showModalConfirm', (data) => {
                $('#confirm-modal').modal(data)
            });
        })
    </script>
    @endpush
</div>