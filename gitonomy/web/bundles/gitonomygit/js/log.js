/**
 * Script to display git log in trees.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */

$(document).ready(function () {
    $("table.git-log").each(function (i, e) {
        gitonomyLog.refreshTable(e);
    });
})

gitonomyLog = {

    /**
     */
    refreshTable: function(table)
    {
        var
            $table    = $(table),
            data      = gitonomyLog._readLog($table),
            width     = $table.width(),
            height    = $table.height(),
            cellSize  = (height / (data.length - 1)) * 0.8,
            cellWidth = Math.ceil(width/cellSize),
            graph     = gitonomyLog.convertToSchema(data),
            xAxis     = gitonomyLog._linearScale(0, cellWidth, cellSize/2, width - cellSize/2),
            yAxis     = gitonomyLog._commitScale($table),
            makeD     = gitonomyLog._makeD(xAxis, yAxis)
        ;

        var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', width);
        svg.setAttribute('height', height);

        var tds = $table.find("td.message");
        for (i in graph.heights) {
            $(tds.eq(i)).css('text-indent', graph.heights[i] * cellSize);
        }

        for (i in graph.links) {
            var link = graph.links[i];
            var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('radius', 0.1);
            path.setAttribute('stroke-linecap', 'round');
            path.setAttribute('stroke-width', 6);
            path.setAttribute('fill', 'none')
            path.setAttribute('stroke', gitonomyLog._familyColor(link[0].family));
            path.setAttribute('d', makeD(link));
            svg.appendChild(path);
        }

        for (i in graph.nodes) {
            var node = graph.nodes[i];
            var circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            circle.setAttribute('cx', xAxis(node.x));
            circle.setAttribute('cy', yAxis(node.y));
            circle.setAttribute('r', 4);
            circle.setAttribute('fill', 'white');
            circle.setAttribute('stroke', 'black');
            circle.setAttribute('stroke-width', '2');
            svg.appendChild(circle);
        }

        $table.find("th").each(function (i, e) {
            var $cell = $(e);
            var b = $cell.parent().prev().find("td.message").css('textIndent');
            var a = $cell.parent().next().find("td.message").css('textIndent');
            var indent;

            if (a && b) {
                indent = Math.max(parseInt(a), parseInt(b));
            } else if (a) {
                indent = a;
            } else if (b) {
                indent = b;
            } else {
                indent = 0;
            }

            $cell.css('text-indent', indent);
        });

        if ($table.data('svg')) {
            var oldSvg = $table.data('svg');
            $(oldSvg).replaceWith(svg);
        } else {
            $table.wrap('<div class="git-log-wrapper" />');
            $($table.parent()[0]).append(svg);
            gitonomyLog.addPreviousNextListeners($table);
        }
        $table.data('svg', svg);
    },


    /**
     * Converts to a schema containing nodes and links.
     */
    convertToSchema: function(commits) {
        var positions = {},
            matrix    = [],
            position,
            drawn     = [],
            link,
            links = [],
            heights = []
        ;

        // Preparation
        commits.forEach(function (commit, i) {
            commits[i].position = i;
            commits[i].x = -1;
            commits[i].y = i;
            commits[i].children = [];

            positions[commit.hash] = i;
            matrix[i] = [];

            commits[i].family = null;
        });

        // Children computing
        commits.forEach(function (commit, i) {
            compute_children(i);
        });

        // Compute family
        commits.forEach(function (commit, i) {
            compute_family(commit, i);
        });

        // Draw
        commits.forEach(function (commit, i) {
            matrix_draw(i);
        });

        // Heights
        commits.forEach(function (commit, i) {
            heights[i] = matrix_hashHeight(i, "text");
        })

        function compute_children(i) {
            if (commits[i].children_spread !== undefined) {
                return;
            }
            commits[i].parents.forEach(function (parent) {
                if (positions[parent] === undefined) {
                    return;
                }

                position = positions[parent];
                commits[position].children.push(commits[i].hash);

                compute_children(position);
            });

            commits[i].children_spread = true;
        }

        function compute_family(commit, i) {
            // No parent (initial commit)
            if (commit.parents.length === 0) {
                commits[i].family = commit.hash;

                return;
            }

            var firstParent = commit.parents[0];
            var firstPos = positions[firstParent];

            if (firstPos === undefined) {
                commits[i].family = commit.hash;
            } else {
                compute_family(commits[firstPos], firstPos);
                if (commits[firstPos].children.length === 1) {
                    commits[i].family = commits[firstPos].family;
                } else if (commits[firstPos].children[0] == commit.hash) {
                    commits[i].family = commits[firstPos].family;
                } else {
                    commits[i].family = commit.hash;
                }
            }
        }

        function matrix_hashHeight(position, hash) {
            var i = 0;
            while (matrix[position][i] != hash && matrix[position][i] !== undefined) {
                i++;
            }

            matrix[position][i] = hash;

            return i;
        }

        function matrix_draw(position) {
            drawn[position] = true;
            var commit = commits[position];
            var x = matrix_hashHeight(position, commit.hash);
            var family;
            commit.x = x;

            var parents = commit.parents;

            parents.forEach(function (parent) {
                if (positions[parent] !== undefined) {

                    if (drawn[positions[parent]] === undefined) {
                        matrix_draw(positions[parent]);
                    }

                    if (commits[positions[parent]].children.length > 1) {
                        family = commits[position].family;
                    } else {
                        family = commits[positions[parent]].family;
                    }
                } else {
                    family = commits[position].family;
                }

                matrix_line(commit.hash, parent, family);
            });
        }

        function matrix_line(from, to, family) {

            var fromX = commits[positions[from]].x;
            var fromY = commits[positions[from]].y;
            var toX, toY;
            if (positions[to] !== undefined) {
                toX   = commits[positions[to]].x;
                toY   = commits[positions[to]].y;
            } else {
                toX   = null;
                toY   = fromY;
            }

            var x,y;

            for (y = fromY; y < toY - 1; y++) {
                x = matrix_hashHeight(y + 1, to);
                links.push([
                    {x: fromX, y: y, family: family},
                    {x: x, y: y + 1}
                ]);
                fromX = x;
            }

            if (null !== toX) {
                links.push([
                    {x: fromX, y: y, family: family},
                    {x: toX,   y: y + 1}
                ]);
            }
        }

        return {
            nodes: commits,
            links: links,
            heights: heights
        };
    },

    addPreviousNextListeners: function ($table)
    {
        $table.find("a.load-previous").click(function () {
            if ($table.data('loading')) {
                return;
            }
            $table.data('loading', true);
            var
                queryUrl    = $table.attr('data-query-url'),
                perPage     = 1 * $table.attr('data-per-page'),
                offset      = 1 * $table.attr('data-offset'),
                limit       = 1 * $table.attr('data-limit'),
                queryOffset = Math.max(0, offset - perPage),
                queryLimit  = Math.min(offset, perPage);
            ;

            if (-1 == queryUrl.indexOf("?")) {
                queryUrl += "?";
            } else {
                queryUrl += "&";
            }

            $.ajax({
                url: queryUrl + "offset=" + queryOffset + "&limit=" + queryLimit,
                success: function (i, e) {
                    $table.attr('data-offset', queryOffset);
                    $table.attr('data-limit', limit + queryLimit);

                    if (queryOffset == 0) {
                        $table.find('thead').remove();
                    }

                    $table.find('tbody').prepend(i);
                    gitonomyLog.refreshTable($table);
                    $table.data('loading', false);
                }
            });
        });

        $table.find("a.load-next").click(function () {
            if ($table.data('loading')) {
                return;
            }
            $table.data('loading', true);

            var
                queryUrl    = $table.attr('data-query-url'),
                perPage     = 1 * $table.attr('data-per-page'),
                offset      = 1 * $table.attr('data-offset'),
                limit       = 1 * $table.attr('data-limit'),
                total       = 1 * $table.attr('data-total'),
                queryOffset = Math.min(offset + limit, total),
                queryLimit  = Math.min(perPage, total - queryOffset)
            ;

            if (-1 == queryUrl.indexOf("?")) {
                queryUrl += "?";
            } else {
                queryUrl += "&";
            }

            $.ajax({
                url: queryUrl + "offset=" + queryOffset + "&limit=" + queryLimit,
                success: function (i, e) {
                    $table.attr('data-limit', limit + queryLimit);

                    if (queryOffset + queryLimit == total) {
                        $table.find('tfoot').remove();
                    }

                    $table.find('tbody').append(i);
                    gitonomyLog.refreshTable($table);
                    $table.data('loading', false);
                }
            });
        });
    },

    /**
     * Converts a table element to an array of data for log graph.
     */
    _readLog: function ($table)
    {
        var $tr, result = [];

        $table.find("tr[data-hash]").each(function (i, tr) {
            $tr = $(tr);
            result.push({
                hash: $tr.attr('data-hash'),
                parents: $tr.attr('data-parents').split(' ')
            });
        });

        return result;
    },

    /**
     * Helper function to generate unique color from a hash.
     */
    _familyColor: function (hash)
    {
        return '#' + hash.substr(0, 6);
    },

    /**
     * Helper function to generate a linear scale function.
     */
     _linearScale: function (a, b, x, y)
     {
        return function (i) {
            return Math.round((x - y) * i / (a - b) + (a * y - b * x) / (a - b));
        }
     },

     _commitScale: function ($table)
     {
        var pos, $td, extra, tds = $table.find("td.message"), yComputed = [];

        return function (i) {
            if (yComputed[i] !== undefined) {
                return yComputed[i];
            }

            extra = 0;
            if (i >= tds.length) {
                i = tds.length - 1;
                extra = 100;
            }

            $td = $(tds[i]);
            pos = $td.position();
            yComputed[i] = pos.top + $td.outerHeight()/2;

            return yComputed[i] + extra;
        };
     },

     _makeD: function (xAxis, yAxis)
     {
        return function makeD(link) {
            var res = 'M ';
            var i, l;
            for (i in link) {
                l = link[i];
                res += xAxis(l.x) + " " + yAxis(l.y);
                if (i < link.length - 1) {
                    res += " L ";
                }
            }

            res += " z";

            return res;
        }
     }
};
