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

        page.render(file);
        slimer.exit();
    });
}
