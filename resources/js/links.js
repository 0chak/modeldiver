
import LeaderLine from "./leader-line";
import "@codehardt/leader-line/leader-line.min.css";

export default {
    refresh: function(id) {
        window.links.forEach(element => {
            if (element.ids.includes(id)) {

                element.link.position();
            }
        });
    },
    render: function() {
        for (const link of window.linksprop) {
            this.renderlink(link)
        }
    },
    renderlink: function(link){
        // console.log(link.from + ' to ' + link.to);
        let fromitem = document.getElementById("m_" + link.from);
        let toitem = document.getElementById("m_" + link.to);

        if (!fromitem) {
            this.makemissingitem(link.from)
            this.renderlink(link)
            return;
        }
        if (!toitem) {
            this.makemissingitem(link.to)
            this.renderlink(link)
            return;
        }

        window.links.push({
            id: this.genUniqueLinkId(link),
            ids: [
                link.from,
                link.to
            ],
            link: new LeaderLine(
                fromitem,
                toitem,
                {
                    startPlug: 'disc',
                    endPlug: 'arrow2',
                    startPlugSize: 2,
                    endPlugSize: 2,
                    size: 2,
                    opacity: 0.66,
                    // path: 'grid'
                }
            )
        })

        // console.log();

        // this.drawArrow(
        //     fromitem,
        //     toitem,
        //     this.genUniqueLinkId(link)
        // )
    },
    makemissingitem: function(id){
        const newitem = document.createElement('div')
        newitem.setAttribute('id', 'm_' + id)
        newitem.setAttribute('class', 'table rounded bg-gray-100 border-solid border-1 border-gray-500 absolute cursor-move px-2 py-1 shadow-xl opacity-90')

        if (window.basesettings.hasOwnProperty(id)) {
            newitem.setAttribute('style', 'top: '+window.basesettings[id].y+'px; left: '+ window.basesettings[id].x+'px')
        }

        const itemchild = document.createElement('div')
        itemchild.setAttribute('class', 'text-sm border-b-1 border-b-gray-700 border-solid text-center text-orange-700')
        itemchild.innerHTML = id;
        newitem.appendChild(itemchild)
        document.getElementById('erd').appendChild(newitem);
    },
    genUniqueLinkId: function(link){
        return link.from + '.' + link.fromPort + link.type + '.' + link.to + '.' + link.toPort;
    },
    // arrow: function(id){
    //     // Create SVG
    //     var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    //     svg.setAttribute('width', window.innerWidth);
    //     svg.setAttribute('height', window.innerHeight);
    //     svg.setAttribute('class', 'absolute')
    //     svg.setAttribute('id', id);

    //     // Add arrowhead marker
    //     var defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
    //     var marker = document.createElementNS('http://www.w3.org/2000/svg', 'marker');
    //     marker.setAttribute('id', 'arrowhead');
    //     marker.setAttribute('markerWidth', '7');
    //     marker.setAttribute('markerHeight', '7');
    //     marker.setAttribute('refX', '7');
    //     marker.setAttribute('refY', '3.5');
    //     marker.setAttribute('orient', 'auto');
    //     marker.setAttribute('fill', '#2cc666');
    //     var polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
    //     polygon.setAttribute('points', '0 0, 10 3.5, 0 7');
    //     marker.appendChild(polygon);
    //     defs.appendChild(marker);
    //     svg.appendChild(defs);
    //     return svg;
    // },

    // getrect: function(a){
    //     return {
    //         a: [a.offsetLeft, a.offsetTop],
    //         b: [a.offsetLeft+a.offsetWidth, a.offsetTop],
    //         c: [a.offsetLeft+a.offsetWidth, a.offsetTop+a.offsetHeight],
    //         d: [a.offsetLeft, a.offsetTop+a.offsetHeight]
    //     }
    // },

    // bestPoints: function(a, b){
    //     const arect = this.getrect(a);
    //     const brect = this.getrect(b);

    //     // console.log('cp');
    //     // console.log(arect);
    //     // console.log(brect);

    //     let aedge = null;
    //     let bedge = null;
    //     // find relative position
    //     if (arect.b[0] < brect.a[0]) { // a is on the left of b
    //         // console.log('case 1');
    //         aedge = [arect.b,arect.c];
    //         bedge = [brect.a,brect.d];
    //     } else if (arect.a[0] > brect.b[0]) { // a is on the right of b
    //         // console.log('case 2');
    //         aedge = [arect.a,arect.d];
    //         bedge = [brect.b,brect.c];
    //     } else if (arect.d[1] < brect.a[1]) { // a is on top of b
    //         // console.log('case 3');
    //         aedge = [arect.d,arect.c];
    //         bedge = [brect.a,brect.b];
    //     } else if (arect.a[1] > brect.d[1]) { // a is under b
    //         // console.log('case 4');
    //         aedge = [arect.a,arect.b];
    //         bedge = [brect.d,brect.c];
    //     }

    //     // console.log('edges');
    //     // console.log(aedge);
    //     // console.log(bedge);

    //     // find common points
    //     if (aedge && bedge) {
    //         const commony = this.findCommonRange([aedge[0][1],aedge[1][1]], [bedge[0][1],bedge[1][1]]);
    //         const commonx = this.findCommonRange([aedge[0][0],aedge[1][0]], [bedge[0][0],bedge[1][0]]);

    //         // console.log('common');
    //         // console.log(commony);
    //         // console.log(commonx);

    //         if (commonx === null && commony) {
    //             return {
    //                 ax: aedge[0][0],
    //                 ay: this.average(commony[0],commony[1]),
    //                 bx: bedge[0][0],
    //                 by: this.average(commony[0],commony[1])
    //             }
    //         }
    //         if (commony === null && commonx) {
    //             return {
    //                 ax: this.average(commonx[0],commonx[1]),
    //                 ay: aedge[0][1],
    //                 bx: this.average(commonx[0],commonx[1]),
    //                 by: bedge[0][1]
    //             }
    //         }
    //     }
    //     return null
    // },
    // average: function(a, b) {

    //     return ((a*1 + b*1) /2);
    // },

    // randomIntFromInterval: function (min, max) {
    //     return Math.floor(Math.random() * (max - min + 1) + min)
    // },
    // findCommonRange: function(a, b){
    //     let commonRange = [];

    //     commonRange[0] = Math.max(a[0], b[0]);
    //     commonRange[1] = Math.min(a[1], b[1]);

    //     // If the ranges do not overlap, return null
    //     if (commonRange[0] > commonRange[1]) {
    //         return null;
    //     }

    //     return commonRange;
    // },

    // distance: function(x, y){
    //     return Math.sqrt( Math.pow((x[0]-y[0]), 2) + Math.pow((x[1]-y[1]), 2) );
    // },

    // closestCorners: function(a, b){
    //     var arect = this.getrect(a);
    //     var brect = this.getrect(b);

    //     var distances = [
    //         { distance: this.distance(arect.a,brect.c), points: {
    //             ax: arect.a[0],
    //             ay: arect.a[1],
    //             bx: brect.c[0],
    //             by: brect.c[1]
    //         }},
    //         { distance: this.distance(arect.d, brect.b), points: {
    //             ax: arect.d[0],
    //             ay: arect.d[1],
    //             bx: brect.b[0],
    //             by: brect.b[1]
    //         }},
    //         { distance: this.distance(arect.b, brect.d), points: {
    //             ax: arect.b[0],
    //             ay: arect.b[1],
    //             bx: brect.d[0],
    //             by: brect.d[1]
    //         }},
    //         { distance: this.distance(arect.c, brect.a), points: {
    //             ax: arect.c[0],
    //             ay: arect.c[1],
    //             bx: brect.a[0],
    //             by: brect.a[1]
    //         }},
    //     ];

    //     distances.sort((a, b) => a.distance - b.distance);

    //     return distances[0].points;
    // },

    // drawArrow: function(start, end, id) {

    //     let p = this.bestPoints(start, end);

    //     // console.log('bestpoints');
    //     // console.log(p);

    //     let d = 'M ';
    //     if (p) {
    //         d += `${p.ax} ${p.ay} ${p.bx} ${p.by}`;
    //     } else {
    //         // console.log('closestEdges');
    //         p = this.closestCorners(start,end);
    //         if (p) {
    //             const midPointX = (p.ax + p.bx) / 2;
    //             // const midPointY = (p.ay + p.by) / 2;

    //             // console.log(p);
    //             // console.log(midPointX);

    //             d += `${p.ax} ${p.ay} L ${midPointX} ${p.ay} L ${midPointX} ${p.by} L ${p.bx} ${p.by}`;
    //         }
    //     }

    //     // var arrow = document.createElementNS("http://www.w3.org/2000/svg", 'line');
    //     // arrow.setAttribute('x1', start.offsetLeft);
    //     // arrow.setAttribute('y1', start.offsetTop);
    //     // arrow.setAttribute('x2', end.offsetLeft);
    //     // arrow.setAttribute('y2', end.offsetTop);
    //     // arrow.setAttribute('stroke', '#2cc666');
    //     // arrow.setAttribute('stroke-width', '2');
    //     // arrow.setAttribute('marker-end', 'url(#arrowhead)');
    //     // return arrow;


    //     var path = document.createElementNS("http://www.w3.org/2000/svg", 'path');
    //     // var d = `M ${p.ax} ${p.ay} L ${midPointY} ${p.ax} L ${midPointX} ${p.by} L ${p.bx} ${p.by}`;
    //     // var d = `M ${start.offsetLeft} ${start.offsetTop} L ${midPointX} ${start.offsetTop} L ${midPointX} ${end.offsetTop} L ${end.offsetLeft} ${end.offsetTop}`;


    //     path.setAttribute('d', d);
    //     path.setAttribute('fill', 'none');
    //     path.setAttribute('stroke', '#2cc666');
    //     path.setAttribute('stroke-width', '1');
    //     path.setAttribute('marker-end', 'url(#arrowhead)');

    //     const svg = this.arrow(id);
    //     svg.appendChild(path)
    //     document.body.appendChild(svg);
    // }
}
