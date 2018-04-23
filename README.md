# Graphar
Tool for visualization HAR-files into graphs

## Usage:
Use in bash:</br>
`./graphar.php < map.har > map.dot | zgrviewer -f map.dot`<br/>
where *map.har* is a HAR file that you got from your browser (from Chrome, for example)

## For generating SVG directly
Use in bash:</br>
`./graphar.php < map.har | dot -Tsvg > hello.svg`