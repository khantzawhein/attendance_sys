@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Student Register</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="eg. Kyaw Kyaw">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="eg. kyawkyaw@example.com">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="father_name" class="col-md-4 col-form-label text-md-right">Father Name</label>

                            <div class="col-md-6">
                                <input id="father_name" type="text" class="form-control @error('father_name') is-invalid @enderror" name="father_name" value="{{ old('father_name') }}" required placeholder="eg. U Mg Mg">

                                @error('father_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="batch" class="col-md-4 col-form-label text-md-right">Batch</label>

                            <div class="col-md-6">
                                <input id="batch" type="number" class="form-control @error('batch') is-invalid @enderror" name="batch" value="{{ old('batch') }}" required placeholder="eg. 2020">

                                @error('batch')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nrc" class="col-md-4 col-form-label text-md-right">NRC (in burmese)</label>

                            <div class="col-md-6">
                                <div>
                                    <select name="nrc_region" id="nrc_region" class="form-control d-inline-block" style="width: 20%" required>
                                        <option value="၁" selected>၁</option>
                                        <option value="၂">၂</option>
                                        <option value="၃">၃</option>
                                        <option value="၄">၄</option>
                                        <option value="၅">၅</option>
                                        <option value="၆">၆</option>
                                        <option value="၇">၇</option>
                                        <option value="၈">၈</option>
                                        <option value="၉">၉</option>
                                        <option value="၁၁">၁၀</option>
                                        <option value="၁၂">၁၂</option>
                                        <option value="၁၃">၁၃</option>
                                        <option value="၁၄">၁၄</option>
                                        <option value="၁၅">၁၅</option>
                                    </select>
                                    /
                                    <input type="text" name="nrc_township" class="form-control d-inline-block" minlength="3" maxlength="3" pattern=".*[က-အ]" title="က မှ အ အတွင်းအက္ခရာသာရိုက်ပါ" required style="width: 22%">
                                    <select name="nrc_type" id="nrc_type" class="form-control w-25 d-inline-block" required>
                                        <option value="နိုင်" selected>(နိုင်)</option>
                                        <option value="ဧည့်">(ဧည့်)</option>
                                        <option value="ပြု">(ပြု)</option>
                                        <option value="ယာယီ">(ယာယီ)</option>
                                        <option value="စီ">(စီ)</option>
                                        <option value="သ">(သ)</option>
                                    </select>
                                    <input type="text" class="form-control d-inline-block w-25" minlength="6" maxlength="6" pattern=".*[၀-၉]" required title="၀ မှ ၉ အတွင်းသာရိုက်ထည့်ပါ">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="haveID" class="col-md-4 col-form-label text-md-right">Do you have student ID?</label>

                            <div class="col-md-6">
                                <div class="form-check form-check-inline" onclick="validateID()">
                                    <input class="form-check-input" type="radio" name="haveID" id="haveIDYes" value="1" checked required>
                                    <label class="form-check-label" for="haveIDYes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline" onclick="validateID()">
                                    <input  class="form-check-input" type="radio" name="haveID" id="haveIDNo" value="0" required>
                                    <label class="form-check-label" for="haveIDNo">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row urn">
                            <label for="urn" class="col-md-4 col-form-label text-md-right">University Registration No</label>

                            <div class="col-md-6">
                                <input id="urn" pattern=".*[0-9]" minlength="6" maxlength="6" type="text" class="form-control @error('urn') is-invalid @enderror" name="urn" value="{{ old('urn') }}" placeholder="eg. 123456" required>

                                @error('urn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function validateID() {
        $haveID = $('input[type=radio][name=haveID]:checked').val();
        if($haveID == 0) {
            $('.urn').hide();
            $('#urn').attr('required', false);
        }
        else {
            $('.urn').show();
            $('#urn').attr('required', true);
        }
    }
</script>
@endsection
