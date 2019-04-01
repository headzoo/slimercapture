const page   = require('webpage').create();
const system = require('system');
const arg    = require('arg');

const args = arg({
    '--url':    String,
    '--image':  String,
    '--width':  Number
}, { argv: system.args });

const url   = args['--url'];
const file  = args['--image'];
const width = args['--width'];
if (!url || !file) {
    console.error('Missing file or url');
    slimer.exit(1);
}

if (!slimer.isExiting()) {
    page.open(url, (status) => {
        if (status !== 'success') {
            console.error(status);
            slimer.exit(65);
            return;
        }

        if (width) {
            page.viewportSize = {
                width: width
            };
        }

        page.includeJs('https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', () => {
            const sections = page.evaluate(function() {
                const sections  = [];
                const variables = [];

                $('body').find('.block-section').each(() => {
                    const el = $(this);

                    const html = el.prop('outerHTML');
                    if (variables.indexOf(html) === -1) {
                        variables.push(html);

                        const offset = el.offset();
                        sections.push({
                            width:  el.width(),
                            height: el.height(),
                            left:   parseInt(offset.left, 10),
                            top:    parseInt(offset.top, 10),
                            html
                        });
                    }
                });

                return sections;
            });

            const components = page.evaluate(() => {
                const components = [];
                const variables  = [];

                $('body').find('.block-component').each(() => {
                    const el = $(this);

                    const html = el.prop('outerHTML');
                    if (variables.indexOf(html) === -1) {
                        variables.push(html);

                        const offset = el.offset();
                        components.push({
                            width:  el.width(),
                            height: el.height(),
                            left:   parseInt(offset.left, 10),
                            top:    parseInt(offset.top, 10),
                            html
                        });
                    }
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
}
