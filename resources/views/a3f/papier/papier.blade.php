@extends('layouts.dash')

@section('title', 'Mes papiers')

@section('content')
    <br>
    <div class="container">
        <h5>Mes papiers</h5>
            <div id="data" class="col s12">
                <div class="row">
                    <div class="col s12 l6">
                        <p>Informations</p>
                        <div class="card-panel">
                            <p>
                            <small>Ma Nationalité:</small>
                            <br><span>
                            @if(!is_null($user->country))
                                    @php
                                        $country = Countries::where('cca2', $user->country)->first();
                                        $countryName = "?";
                                        try {
                                            $countryName = $country->translations->fra->common;
                                        } catch(\Exception $e) {
                                            // :)
                                        }
                                    @endphp
                                    {!! $country->flag['flag-icon'] !!}
                                    {{ $countryName }}
                                @else
                                    Non Indiqué
                                @endif
                        		</span>
                        	</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
