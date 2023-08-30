<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover" />
    <title>ModelDiver</title>

    {{ Vite::useBuildDirectory('vendor/modeldiver')->useHotFile('vendor/modeldiver/hot')->withEntryPoints(['resources/js/main.js', 'resources/css/main.css']) }}

</head>

<body class="tracking-wide bg-gray-800 p-10 ">
    <div id="erd" class="container relative">
        @foreach ($nodes as $item)
            @php
                $top = '1px';
                $left = '1px';

                if (isset($settings->{$item['key']})) {
                    $top = $settings->{$item['key']}->y . 'px';
                    $left = $settings->{$item['key']}->x . 'px';
                }
            @endphp
            <div class="table rounded bg-gray-100 border-solid border-1 border-gray-500 absolute cursor-move px-2 py-1 shadow-xl opacity-90"
                style="{{ 'top: ' . $top . '; left: ' . $left }}" id="m_{{ $item['key'] }}">
                <div class="text-sm border-b-1 border-b-gray-700 border-solid text-center text-orange-700">
                    {{ $item['key'] }}</div>
                <table class="text-xs">
                    @foreach ($item['schema'] as $column)
                        <tr id="m_{{ $item['key'] . $column['name'] }}">
                            <td class="pr-2">{{ $column['attributes'] }}</td>
                            <td class="pr-2">{{ $column['name'] }}</td>
                            <td>{{ $column['type'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    </div>

    <footer class="absolute bottom-0 right-0 text-xs text-slate-400 px-4 py-3">
        Powered by Vasyl Halushchak
    </footer>

    <script>

        window.linksprop = {!! json_encode($links) !!}
        window.basesettings = {!! json_encode($settings) !!}
    </script>

</body>

</html>
