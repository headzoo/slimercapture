var page   = require('webpage').create();
var system = require('system');

var url   = system.args[1];
var file  = system.args[2];
var width = system.args[3];
if (!url || !file) {
    console.log('Missing file or url');
    slimer.exit(1);
}

page.open(url, function(status) {
    if (status !== 'success') {
        slimer.exit(65);
    }
    if (width) {
        page.viewportSize = {
            width: width
        };
    }

    page.includeJs('https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', function() {
        var sections = page.evaluate(function() {
            var sections  = [];
            // var variables = [];

            $('body').find('.block-section').each(function() {
                var el = $(this);

                var variable = el.attr('data-style');
                // if (variable && variables.indexOf(variable) === -1) {
                    // variables.push(variable);
                    sections.push({
                        width:  el.width(),
                        height: el.height(),
                        left:   parseInt(el.offset().left, 10),
                        top:    parseInt(el.offset().top, 10),
                        html:   el.prop('outerHTML')
                    });
                // }
            });

            return sections;
        });

        var components = page.evaluate(function() {
            var components = [];
            // var variables  = [];

            $('body').find('.block-component').each(function() {
                var el = $(this);

                var variable = el.attr('data-style');
                // if (variable && variables.indexOf(variable) === -1) {
                    // variables.push(variable);
                    components.push({
                        width:  el.width(),
                        height: el.height(),
                        left:   parseInt(el.offset().left, 10),
                        top:    parseInt(el.offset().top, 10),
                        html:   el.prop('outerHTML')
                    });
                // }
            });

            return components;
        });

        console.log(JSON.stringify({
            components: components,
            sections:   sections
        }));

        page.render(file);
        slimer.exit();
    });
});
