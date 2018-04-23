# graphar
Tool for visualization HAR-files into graphs

# Usage:
graphar < map.har > map.dot | zgrviewer -f map.dot

# For generating SVG directly
graphar < map.har | dot -Tsvg > hello.svg