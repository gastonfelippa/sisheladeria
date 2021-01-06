<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h1 class="text-center mb-4" style="color:black"><b>SisGNF</b></h1>
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>
                <div class="card-body px-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control text-capitalize @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="NOMBRE">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control text-capitalize @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido" autofocus placeholder="APELLIDO">
                                @error('apellido')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control text-uppercase @error('nombreComercio') is-invalid @enderror" name="nombreComercio" value="{{ old('nombreComercio') }}" required autocomplete="nombreComercio" autofocus placeholder="NOMBRE DEL COMERCIO">
                                @error('nombreComercio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" required autocomplete="telefono" placeholder="TELEFONO">
                                @error('telefono')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="EMAIL">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class=" mt-4 text-left">
                            <button type="submit" class="btn btn-primary">
                            {{ ('REGISTRARSE') }}
                            </button>
                        </div>
                        <div class="col-md-12 text-right">
                            Ya estás registrado? 
                            <a class="btn btn-link" href="#" wire:model="vista" wire:click="doAction(1)">
                            {{ ('Hacé click acá') }}
                            </a>                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

