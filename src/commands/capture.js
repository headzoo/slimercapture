var page = require('webpage').create();
var system = require('system');

var url   = system.args[1];
var file  = system.args[2];
var width = system.args[3];
if (!url || !file) {
    console.log('Missing file or url');
    slimer.exit();
}

page.open(url, function(status) {
    if (status !== 'success') {
        slimer.exit(1);
    }
    if (width) {
        page.viewportSize = {
            width: width
        };
    }
    page.render(file);
    slimer.exit();
});
