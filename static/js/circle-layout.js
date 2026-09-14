
document.addEventListener("DOMContentLoaded", ()=>{
    let layoutContainer = document.querySelector(".circle-layout");
    let rec = layoutContainer.getBoundingClientRect();
    let items = layoutContainer.children;
    const numPoints = 300;
    let points = evenlyDistributePointsOnCirlce(numPoints);
    let radius = rec.width / 2;
    console.log("Radius:" + radius + ", number of points: " + numPoints + ", minAngle: " + points[0] + ", maxAngle: " + points[numPoints - 1]);
    let center = [rec.top + radius, rec.top + radius];
    evenlyDistributeItemsOnCircle(items, points, radius, center);

});

const colors = [
    "blue",
    "red",
    "black",
    "green",
    "orange",
    "purple",
]

function getPosOnCircle(angle, radius, units='deg'){
    if(units === 'deg'){
        angle = angle / 180 * Math.PI;
    }
    return [radius * Math.cos(angle), radius * Math.sin(angle)];
}

function evenlyDistributeItemsOnCircle(items, positions, radius, center, end=-1){
    let allowedClasses = [
        HTMLCollection,
        NodeList,
        Array
    ];
    let targetCls = allowedClasses.find(cls => {
        return items instanceof cls;
    });

    if(!targetCls){
        return;
    }

    // safe to work with an array, in case of missing 'forEach'
    if(targetCls != Array){
        items = Array.from(items);
    }
    
    let numItems = items.length;
    end = end == -1 ? numItems - 1 : numItems;

    console.log("found " + numItems + " children");

    items.forEach((v, i) => {
        let pos = Math.floor((i / numItems) * positions.length);
        let [x, y] = getPosOnCircle(positions[pos], radius);
        let signs = [x / Math.abs(x), y / Math.abs(y)];
        let itemRec = v.getBoundingClientRect();

        console.log(`angle: ${positions[pos].toFixed(2)} deg, x: ${x}, y: ${y}`);
        
        x = (radius + x - (itemRec.width / 2)) //- (signs[0] * itemRec.width);
        y = (radius + y - (itemRec.width / 2)); // position to the center

        v.style.setProperty('top', `${Number(y.toFixed(2))}px`); // normalise relative to the containe
        v.style.setProperty('left', `${Number(x.toFixed(2))}px`);// positioning starts from top-left, so we need to consider the offset
        v.style.background = colors[i] ?? `rgb(${i / numItems * 255}, ${(i/numItems) * (360/255)},${i/numItems * 360})`;
    });
}


function evenlyDistributePointsOnCirlce(numPoints){
    const maxAngle = 360;
    const step = maxAngle / numPoints;
    let points = Array(numPoints);
    points[0] = 0.0;

    for(let i = 0; i < numPoints - 1; i++){
        points[i + 1] = Number((points[i] + step).toFixed(2));
    }

    return points;
}