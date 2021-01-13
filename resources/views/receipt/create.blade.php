<div class="card">
    <div class="card-body">

        <h5 class="card-title">{{ __('Create Receipt') }}</h5>
        <hr>

        @if (session('message'))
            <div class="alert alert-{{ session('success') ? 'success' : 'danger' }}" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('receipts.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Receipt') }}</label>

                <div class="col-md-6">
                    <input
                        id="image"
                        type="file"
                        class="form-control-file @error('image') is-invalid @enderror"
                        name="image"
                        value="{{ old('image') }}"
                        required
                        autofocus
                    >

                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Datum') }}</label>

                <div class="col-md-6">

                    <b-form-datepicker
                        id="date"
                        class="mb-2 @error('date') is-invalid @enderror"
                        name="date"
                        value="{{ (old('date')) }}"
                        required
                    ></b-form-datepicker>

                    @error('date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>

                <div class="col-md-6">
                    <input
                        id="amount"
                        type="text"
                        class="form-control @error('amount') is-invalid @enderror"
                        name="amount"
                        value="{{ (old('amount')) }}"
                        required
                    >

                    @error('amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
