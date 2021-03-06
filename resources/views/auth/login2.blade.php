<div class="container-fluid">
    <div class="row justify-content-center" >
        <div class="col-md-4">
                <h1 class="text-center mb-4" style="color:black"><b>FlokI</b></h1>
                <!-- <h1 class="text-center mb-4" style="color:black"><b>{{config('app.name')}}</b></h1> -->
            <div class="card">
                <div class="card-header">{{('Login') }}</div>
                <!-- <div class="card-header">Login</div> -->

                <div class="card-body px-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12" >
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="USUARIO">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="CONTRASEÑA">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <!-- <div class="col-md-6 offset-md-4"> -->
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <!-- {{ __('Login') }} -->
                                    {{('INGRESAR') }}
                                </button>
 
                               @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-12 text-right">
                                Sos nuevo en SisGNF?
                                <a class="btn btn-link" href="#" wire:model="vista" wire:click="doAction(2)">
                                {{('Registrate!') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
