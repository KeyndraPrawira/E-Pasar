@extends ('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                  <h4 class="card-title mb-3">Edit Data Pengguna</h4>
                  @if ($errors->any())
                        <div style="background:#fee; padding:10px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                <form action="{{ route('pengguna.update', $user->id) }}" method="POST">
                    @csrf
                   @method('PUT')
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" value="{{ $user->name }}" name="name" id="tb-fname" />
                         <label for="tb-fname" class="text-dark">Nama Pengguna</label>
                        </div>
                      </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input type="email" class="form-control" value="{{ $user->email }}" name="email" id="tb-email" />
                            <label for="tb-email" class="text-dark">Email</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input type="password" class="form-control" value="{{ $user->password }}" name="password" id="tb-password" />
                            <label for="tb-password" class="text-dark">Password</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <select name="role" id="role" class="form-control">
                                <option disabled selected>Pilih Role</option>
                                <option value="pedagang"
                                    {{ old('role', $user->role) === 'pedagang' ? 'selected' : '' }}>
                                    Pedagang
                                </option>
                                <option value="driver"
                                    {{ old('role', $user->role) === 'driver' ? 'selected' : '' }}>
                                    Driver
                                </option>
                            </select>
                            <label for="tb-role" class="text-dark">Role</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input type="number" class="form-control" value="{{ $user->nomor_telepon }}" name="nomor_telepon" id="tb-nomor_telepon" />
                            <label for="tb-nomor_telepon" class="text-dark">Nomor telepon</label>
                            </div>
                        </div>
                      </div>
                      
                    <div class="row d-flex justify-content-between">
                        <div class="col text-start">
                        <button type="submit" class="btn btn-success">Edit Pengguna</button>
                      </div>
                      <div class="col text-end">
                        <a href="{{ route('pengguna.index') }}" style="background-color: grey;color:white;" class="btn">Batal</a>
                      </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
@endsection