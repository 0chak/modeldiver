<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@codehardt/leader-line@1.0.9/leader-line.min.css" />
    <title>ModelDiver</title>
</head>

<body class="tracking-wide bg-gray-800 p-10 ">
    <div class="container relative">
        {{-- @dump($links) --}}
        @foreach ($nodes as $item)
            @php
                $top = '1px';
                $left = '1px';

                if (isset($settings->{$item['key']})) {
                    $top = $settings->{$item['key']}->y . 'px';
                    $left = $settings->{$item['key']}->x . 'px';
                }
            @endphp
            <div class="table rounded bg-gray-100 border-solid border-1 border-gray-500 absolute cursor-move px-2 py-1 shadow-xl"
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

    <script src=" https://cdn.jsdelivr.net/npm/@codehardt/leader-line@1.0.9/leader-line.min.js "></script>
    <script>
        els = document.querySelectorAll('.table');
        els.forEach(el => {
            let newPosX = 0,
                newPosY = 0,
                startPosX = 0,
                startPosY = 0;

            // when the user clicks down on the element
            el.addEventListener('mousedown', function(e) {
                e.preventDefault();

                // get the starting position of the cursor
                startPosX = e.clientX;
                startPosY = e.clientY;

                document.addEventListener('mousemove', mouseMove);
                document.addEventListener('mouseup', mouseUp);

            });

            function mouseUp() {
                document.removeEventListener('mousemove', mouseMove);
                document.removeEventListener('mouseup', mouseUp);
                savePosition(el.offsetLeft, el.offsetTop, el.id.substr(2));
            }

            function mouseMove(e) {
                // calculate the new position
                newPosX = startPosX - e.clientX;
                newPosY = startPosY - e.clientY;

                // with each move we also want to update the start X and Y
                startPosX = e.clientX;
                startPosY = e.clientY;

                // set the element's new position:
                el.style.top = (el.offsetTop - newPosY) + "px";
                el.style.left = (el.offsetLeft - newPosX) + "px";

                refreshLinks(el.id.substr(2));
            }
        });

        function savePosition(x, y, id) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/modeldiver/saveposition", true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({
                x: x,
                y: y,
                id: id
            }));
        }

        window.links = [];

        function renderLinks() {
            @foreach ($links as $link)
                try {
                    window.links.push({
                        ids: [
                            "{{ $link['from'] }}",
                            "{{ $link['to'] }}",
                        ],
                        link: new LeaderLine(
                            document.getElementById("m_{{ $link['from'] . $link['fromPort'] }}", ),
                            document.getElementById("m_{{ $link['to'] . $link['toPort'] }}"), {
                                startPlug: 'disc',
                                endPlug: 'arrow2',
                                startPlugSize: 2,
                                endPlugSize: 2,
                                size: 2,
                                opacity: 0.66,
                                // path: 'grid'
                            }
                        )
                    });
                } catch (e) {

                }
            @endforeach
        }

        function refreshLinks(id) {
            window.links.forEach(element => {
                if (element.ids.includes(id)) {

                    element.link.position();
                }
            });

        }

        document.addEventListener("DOMContentLoaded", () => {
            renderLinks();
        });
    </script>
</body>

</html>
