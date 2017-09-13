 <script>
/* original project: https://bl.ocks.org/kerryrodden/766f8f6d31f645c39f488a0befa1e3c8
this file has been modified:
    removed function to parse csv sin the data will be inserted as json
    removed legend functionality
    changed colors
    removed breadcrumb
    
*/

// Dimensions of sunburst.
var width = 10;
winWidth = Math.max(
    document.body.scrollWidth,
    document.documentElement.scrollWidth,
    document.body.offsetWidth,
    document.documentElement.offsetWidth,
    document.documentElement.clientWidth
);
if (winWidth < 360) {
    width = 300;
}
else if (winWidth < 400) {
    width = 360;
}
else if (winWidth < 576) {
    width = 500;
}
else if (winWidth < 768) {
    width = 650;
}
else {
    width = 750;
}
var height = width * (4/5);
document.getElementById("chart").style.width = width+"px";
document.getElementById("explanation").style.left = ((width/2)-60)+"px";
document.getElementById("explanation").style.top = ((height/2)-60)+"px";
var radius = Math.min(width, height) / 2;


//scale colors
colorHigh = d3.scaleLinear()
    .domain([85,100])
    .interpolate(d3.interpolateHcl)
    .range([d3.rgb("#aee5ae"), d3.rgb("#32CD32")]);
colorMedium = d3.scaleLinear()
    .domain([60,85])
    .interpolate(d3.interpolateHcl)
    .range([d3.rgb("#FFBF71"), d3.rgb("#FF8C00")]);
colorLow = d3.scaleLinear()
    .domain([1,59])
    .interpolate(d3.interpolateHcl)
    .range([d3.rgb("#D99292"), d3.rgb("#B22222")]);
    
    
// Total size of all segments; we set this later, after loading the data.
var totalSize = 0; 

var vis = d3.select("#chart").append("svg:svg")
    .attr("width", width)
    .attr("height", height)
    .append("svg:g")
    .attr("id", "container")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

var partition = d3.partition()
    .size([2 * Math.PI, radius * radius]);

var arc = d3.arc()
    .startAngle(function(d) { return d.x0; })
    .endAngle(function(d) { return d.x1; })
    .innerRadius(function(d) { return Math.sqrt(d.y0); })
    .outerRadius(function(d) { return Math.sqrt(d.y1); });

createVisualization(json);


// Main function to draw and set up the visualization, once we have the data.
function createVisualization(json) {

  // Bounding circle underneath the sunburst, to make it easier to detect
  // when the mouse leaves the parent g.
  vis.append("svg:circle")
      .attr("r", radius)
      .style("opacity", 0);

  // Turn the data into a d3 hierarchy and calculate the sums.
  var root = d3.hierarchy(json)
      .sum(function(d) { return d.size; });
      //.sort(function(a, b) { return b.value - a.value; });
  // For efficiency, filter nodes to keep only those large enough to see.
  var nodes = partition(root).descendants()
      .filter(function(d) {
          return (d.x1 - d.x0 > 0.005); // 0.005 radians = 0.29 degrees
      });
  console.log(nodes);
  var path = vis.data([json]).selectAll("path")
      .data(nodes)
      .enter().append("svg:path")
      .attr("display", function(d) { return d.depth ? null : "none"; })
      .attr("d", arc)
      .attr("fill-rule", "evenodd")
      .style("fill", function(d) { 
            if(d.data.color) {
                return d.data.color;
            }
            else {
                if (d.data.score >= 85) {
                    return colorHigh(d.data.score);
                }
                else if (d.data.score >= 60) {
                    return colorMedium(d.data.score);
                }
                else {
                    return colorLow(d.data.score);
                }
            }
            return "#fff";
        })
      .style("opacity", 1)
      .on("mouseover", mouseover)
      .on("touchstart", mouseover)
      .on("click", visitnode);

  // Add the mouseleave handler to the bounding circle.
  d3.select("#container")
    .on("mouseleave", mouseleave)
    .on("touchend", mouseleave);

  // Get total size of the tree = value of root node from partition.
  totalSize = path.datum().value;
 };

function visitnode(d){
    if (d.data.id)
        window.open("{{ url('/sujeto') }}"+"/"+d.data.id, "_self");
}
 
// Fade all but the current sequence
function mouseover(d) {

  var percentage = (100 * d.value / totalSize).toPrecision(3);
  var percentageString = d.data.score + "%";
  if (percentage < 0.1) {
    percentageString = "< 0.1%";
  }

  d3.select("#percentage")
      .text(percentageString);

  d3.select("#explanation")
      .style("visibility", "");
      
  d3.select("#sname")
      .text(d.data.name);

  var sequenceArray = d.ancestors().reverse();
  sequenceArray.shift(); // remove root node from the array

  // Fade all the segments.
  d3.selectAll("path")
      .style("opacity", function(){
          return 0.3;
      });

  // Then highlight only those that are an ancestor of the current segment.
  vis.selectAll("path")
      .filter(function(node) {
                return (sequenceArray.indexOf(node) >= 0);
              })
      .style("opacity", 1);
}

// Restore everything to full opacity when moving off the visualization.
function mouseleave(d) {

  // Deactivate all segments during transition.
  d3.selectAll("path").on("mouseover", null);

  // Transition each segment to full opacity and then reactivate it.
  d3.selectAll("path")
      .transition()
      .duration(1000)
      .style("opacity", 1)
      .on("end", function() {
              d3.select(this).on("mouseover", mouseover);
            });

  d3.select("#explanation")
      .style("visibility", "hidden");
}
 </script>
