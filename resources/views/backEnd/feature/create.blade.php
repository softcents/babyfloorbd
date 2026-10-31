@extends('backEnd.layouts.master')
@section('title','Add Feature')

@section('css')
<style>
    .studio-card { background:#fff; border-radius:15px; box-shadow:0 5px 25px rgba(0,0,0,0.05); border:1px solid #e2e8f0; overflow:hidden; }
    .form-label-custom { font-size:13px; font-weight:700; color:#475569; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px; }
    .input-clean { background:#f8fafc; border:1px solid #e2e8f0; padding:12px 15px; border-radius:10px; font-size:14px; color:#334155; transition:all 0.2s ease-in-out; }
    .input-clean:focus { background:#fff; border-color:#2563eb; box-shadow:0 0 0 4px rgba(37,99,235,0.1); outline:none; }
    .input-group-text { background-color:#f1f5f9; border:1px solid #e2e8f0; border-radius:10px 0 0 10px; color:#64748b; min-width:45px; justify-content:center; }
    .input-group .input-clean { border-radius:0 10px 10px 0; }
    .status-toggle-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:18px; display:flex; justify-content:space-between; align-items:center; }
    .status-text h6 { font-size:14px; font-weight:700; color:#1e293b; margin:0; }
    .status-text small { font-size:12px; color:#64748b; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark">নতুন ফিচার যোগ করুন</h4>
            <span class="text-muted small">হোমপেজ ফিচার বারের জন্য একটি আইটেম যোগ করুন</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{route('features.index')}}" class="btn btn-light border fw-bold text-secondary px-3 rounded-pill">Cancel</a>
            <button type="submit" form="featureCreateForm" class="btn btn-primary fw-bold px-4 shadow-sm rounded-pill">
                <i class="mdi mdi-content-save-outline me-1"></i> Save Feature
            </button>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-11">
            <div class="studio-card p-4 p-md-5">
                <form action="{{route('features.store')}}" method="POST" id="featureCreateForm" data-parsley-validate="">
                    @csrf
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label-custom">টাইটেল *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-label-outline"></i></span>
                                <input type="text" class="form-control input-clean @error('title') is-invalid @enderror"
                                       name="title" value="{{ old('title') }}" placeholder="যেমন: দ্রুত ডেলিভারি" required>
                            </div>
                            @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">আইকন ক্লাস (FontAwesome) *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-emoticon-happy-outline"></i></span>
                                <input type="text" class="form-control input-clean @error('icon') is-invalid @enderror"
                                       name="icon" value="{{ old('icon') }}" placeholder="যেমন: fas fa-truck" required>
                            </div>
                            <small class="text-muted">উদাহরণ: <b>fas fa-leaf</b>, <b>fas fa-truck</b>, <b>fas fa-shield-alt</b>, <b>fas fa-headset</b></small>
                            @error('icon') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label-custom">বিবরণ</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-text"></i></span>
                                <input type="text" class="form-control input-clean @error('description') is-invalid @enderror"
                                       name="description" value="{{ old('description') }}" placeholder="যেমন: সারা দেশে দ্রুত পৌঁছে যায়।">
                            </div>
                            @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">অর্ডার (ক্রম)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="mdi mdi-sort-numeric-ascending"></i></span>
                                <input type="number" class="form-control input-clean @error('sort_order') is-invalid @enderror"
                                       name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                            </div>
                            @error('sort_order') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="status-toggle-box">
                                <div class="status-text">
                                    <h6 class="d-flex align-items-center"><i class="mdi mdi-eye-outline me-2 text-primary"></i> Publication Status</h6>
                                    <small>ফিচারটি ওয়েবসাইটে দেখানোর জন্য সক্রিয় রাখুন</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" checked
                                           style="width: 3.5em; height: 1.8em; cursor:pointer;">
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
