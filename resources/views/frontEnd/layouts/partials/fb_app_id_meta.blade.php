@php $fbAppId = \App\Support\FacebookMeta::appId($generalsetting ?? null); @endphp
@if($fbAppId)
<meta property="fb:app_id" content="{{ $fbAppId }}" />
@endif
