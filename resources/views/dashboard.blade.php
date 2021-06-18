<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{--    <link rel="shortcut icon" href="{{ asset('/vendor/telescope/favicon.ico') }}">--}}

    <meta name="robots" content="noindex, nofollow">

    <title>ModelStats {{ config('app.name') ? ' - ' . config('app.name') : '' }}</title>

    <!-- Style sheets-->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset(mix('app.css', 'vendor/model-stats')) }}" rel="stylesheet" type="text/css">
</head>
<body>
<div id="model-stats" v-cloak>
{{--    <alert :message="alert.message"--}}
{{--           :type="alert.type"--}}
{{--           :auto-close="alert.autoClose"--}}
{{--           :confirmation-proceed="alert.confirmationProceed"--}}
{{--           :confirmation-cancel="alert.confirmationCancel"--}}
{{--           v-if="alert.type"></alert>--}}

    <div class="max-w-2xl mx-auto mb-5">
        <div class="flex items-center py-4">
            <h1 class="mb-0 ml-3 font-medium text-2xl">ModelStats{{ config('app.name') ? ' - ' . config('app.name') : '' }}</h1>
        </div>

        <div class="flex w-full mt-4">
            <div class="col-10">
                <router-view></router-view>
            </div>
        </div>
    </div>
</div>
<!-- Global ModelStats Object -->
<script>
    window.ModelStats = {
        config: @json($config),
        models: @json($models)
    }
</script>

<script src="{{ asset(mix('app.js', 'vendor/model-stats')) }}"></script>
</body>
</html>
