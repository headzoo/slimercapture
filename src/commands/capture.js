const page   = require('webpage').create();
const system = require('system');
const arg    = require('arg');

page.onAlert = function(msg) {
    console.log(msg);
};

const args = arg({
    '--url':    String,
    '--image':  String,
    '--width':  Number
}, { argv: system.args });

const url   = args['--url'].replace(/#@#/g, '=');
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
            const components = page.evaluate(() => {
                const components = [];
                const variables  = [];

                $('body').find('.block-component').each((i, item) => {
                    const el = $(item);

                    const html = el.prop('outerHTML');
                    if (variables.indexOf(html) === -1) {
                        variables.push(html);

                        const offset = el.offset();
                        components.push({
                            width:  el.width(),
                            height: el.height(),
                            left:   parseInt(offset.left, 10),
                            top:    parseInt(offset.top, 10),
                            style:  el.data('style'),
                            block:  el.data('block'),
                            html
                        });
                    }
                });

                return components;
            });

            const sections = page.evaluate(() => {
                const sections  = [];
                const variables = [];

                $('body').find('.block-section').each((i, item) => {
                    const el = $(item);

                    const html = el.prop('outerHTML');
                    if (variables.indexOf(html) === -1) {
                        variables.push(html);

                        const components = el.find('.block-component');
                        if (components.length) {
                            const styles = [];
                            components.each((y, c) => {
                                const cel   = $(c);
                                const style = cel.data('style');
                                if (style) {
                                    if (styles.includes(style)) {
                                        cel.hide();
                                    }
                                    styles.push(style);
                                }
                            });
                        }

                        const offset = el.offset();
                        sections.push({
                            width:  el.width(),
                            height: el.height(),
                            left:   parseInt(offset.left, 10),
                            top:    parseInt(offset.top, 10),
                            style:  el.data('style'),
                            block:  el.data('block'),
                            html
                        });
                    }
                });

                return sections;
            });

            console.log(JSON.stringify({
                components,
                sections
            }));

            page.render(file);
            slimer.exit();
        });
    });
}
