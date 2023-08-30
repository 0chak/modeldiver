
// alert('worka')

import Links from './links'

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

document.addEventListener("DOMContentLoaded", () => {
    Links.render();
    trackMotion();
});


function trackMotion(){

    document.querySelectorAll('.table').forEach(el => {
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

            Links.refresh(el.id.substr(2));
        }
    });

}
