@extends('backEnd.layouts.master')
@section('title', 'ফিচার বার ব্যবস্থাপনা')

@section('css')
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />

<style>
    .sm-manage { --sm-border:#e5e7eb; --sm-bg:#f8fafc; --sm-text:#111827; --sm-muted:#6b7280; --sm-accent:#2563eb; font-size:14px; color:var(--sm-text); padding-bottom:2rem; }
    .sm-manage .sm-shell { background:var(--sm-bg); margin:-0.75rem -12px 0; padding:1.25rem 1rem 2rem; min-height:calc(100vh - 110px); }
    .sm-manage .sm-head { display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:1rem; margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--sm-border); }
    .sm-manage .sm-head h1 { font-size:1.25rem; font-weight:700; margin:0 0 0.25rem; letter-spacing:-0.02em; }
    .sm-manage .sm-head p { margin:0; font-size:13px; color:var(--sm-muted); }
    .sm-manage .btn-sm-add { font-size:13px; font-weight:600; padding:0.5rem 1rem; border-radius:8px; background:var(--sm-accent); border:1px solid var(--sm-accent); color:#fff; display:inline-flex; align-items:center; gap:0.4rem; }
    .sm-manage .btn-sm-add:hover { background:#1d4ed8; border-color:#1d4ed8; color:#fff; }
    .sm-manage .sm-card { background:#fff; border:1px solid var(--sm-border); border-radius:12px; box-shadow:0 1px 2px rgba(15,23,42,0.04); overflow:hidden; }
    .sm-manage .sm-card-inner { padding:1rem 1.25rem 1.25rem; }
    .sm-manage .sm-icon-cell { width:42px; height:42px; background:#f3f4f6; border:1px solid var(--sm-border); border-radius:10px; display:inline-flex; align-items:center; justify-content:center; font-size:18px; color:#1f2937; }
    .sm-manage #datatable-buttons thead th { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; color:var(--sm-muted); border-bottom:1px solid var(--sm-border); padding:12px 10px; background:#fff; white-space:nowrap; vertical-align:middle; }
    .sm-manage #datatable-buttons tbody td { padding:12px 10px; vertical-align:middle; border-color:#f3f4f6; font-size:13px; }
    .sm-manage #datatable-buttons tbody tr:hover { background:#fafafa; }
    .sm-manage .sm-badge-on { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; padding:4px 10px; border-radius:6px; background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; }
    .sm-manage .sm-badge-off { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; padding:4px 10px; border-radius:6px; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
    .sm-manage .btn-sm-act { width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; border:1px solid var(--sm-border); background:#fff; color:#4b5563; transition:background 0.15s,color 0.15s,border-color 0.15s; }
    .sm-manage .btn-sm-act.toggle-off:hover { background:#fef2f2; border-color:#fecaca; color:#dc2626; }
    .sm-manage .btn-sm-act.toggle-on:hover { background:#ecfdf5; border-color:#a7f3d0; color:#047857; }
    .sm-manage .btn-sm-act.edit { background:#eff6ff; border-color:#bfdbfe; color:var(--sm-accent); }
    .sm-manage .btn-sm-act.edit:hover { background:var(--sm-accent); border-color:var(--sm-accent); color:#fff; }
    .sm-manage .btn-sm-act.del { background:#fef2f2; border-color:#fecaca; color:#dc2626; }
    .sm-manage .btn-sm-act.del:hover { background:#dc2626; border-color:#dc2626; color:#fff; }
</style>
@endsection

@section('content')
<div class="container-fluid sm-manage">
<div class="sm-shell">

    <header class="sm-head">
        <div>
            <h1>ফিচার বার</h1>
            <p>হোমপেজের ফিচার বার আইটেম (আইকন, টাইটেল, বিবরণ) — সক্রিয়/নিষ্ক্রিয়, এডিট ও ডিলিট।</p>
        </div>
        <div>
            <a href="{{ route('features.create') }}" class="btn btn-sm-add text-decoration-none">
                <i class="fe-plus"></i> নতুন ফিচার
            </a>
        </div>
    </header>

    <div class="sm-card">
        <div class="sm-card-inner">
            <div class="table-responsive">
                <table id="datatable-buttons" class="table align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th style="width:5%;">ক্রম</th>
                            <th style="width:10%;">আইকন</th>
                            <th style="width:30%;">টাইটেল</th>
                            <th style="width:25%;">বিবরণ</th>
                            <th style="width:10%;">অর্ডার</th>
                            <th style="width:10%;">স্ট্যাটাস</th>
                            <th style="width:15%;" class="text-end">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($show_data as $value)
                        <tr>
                            <td><span class="text-muted fw-semibold">#{{ $loop->iteration }}</span></td>
                            <td><span class="sm-icon-cell" aria-hidden="true"><i class="{{ $value->icon }}"></i></span></td>
                            <td><span class="fw-semibold text-dark d-block">{{ $value->title }}</span></td>
                            <td><span class="text-muted">{{ $value->description }}</span></td>
                            <td><span class="text-muted">{{ $value->sort_order }}</span></td>
                            <td>
                                @if($value->status == 1)
                                    <span class="sm-badge-on"><i class="fe-check-circle"></i> সক্রিয়</span>
                                @else
                                    <span class="sm-badge-off"><i class="fe-slash"></i> নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end align-items-center gap-1 flex-wrap">
                                    @if($value->status == 1)
                                        <form method="post" action="{{ route('features.inactive') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" value="{{ $value->id }}" name="hidden_id">
                                            <button type="submit" class="btn-sm-act toggle-off shadow-none" title="নিষ্ক্রিয় করুন"><i class="fe-x-circle"></i></button>
                                        </form>
                                    @else
                                        <form method="post" action="{{ route('features.active') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" value="{{ $value->id }}" name="hidden_id">
                                            <button type="submit" class="btn-sm-act toggle-on shadow-none text-success border" title="সক্রিয় করুন"><i class="fe-check-circle"></i></button>
                                        </form>
                                    @endif

                                    <a href="{{ route('features.edit', $value->id) }}" class="btn-sm-act edit shadow-none text-decoration-none" title="এডিট"><i class="fe-edit-2"></i></a>

                                    <form method="post" action="{{ route('features.destroy') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" value="{{ $value->id }}" name="hidden_id">
                                        <button type="submit" class="btn-sm-act del delete-confirm border-0 shadow-none" title="মুছুন"><i class="fe-trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fe-grid d-block mb-2" style="font-size:1.75rem;opacity:.45;"></i>
                                কোনো ফিচার নেই। <a href="{{ route('features.create') }}">একটি যোগ করুন</a>।
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>
@endsection

@section('script')
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="{{asset('/public/backEnd/')}}/assets/js/pages/datatables.init.js"></script>
@endsection
