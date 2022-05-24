const stylelint = require('stylelint');
const { globalSettings } = require('../settings');
const commandLineParser = require('commander');

commandLineParser
    .option('-f, --fix', 'execute stylelint in the fix mode.')
    .option('-p, --file-path <path>', 'execute stylelint only for this file.')
    .option('-c, --config-path <path>', 'use this stylelint config instead of default.')
    .parse(process.argv);

const defaultFilePaths = [`${globalSettings.paths.project}/**/*.scss`];
const defaultConfigFile = `${__dirname}/../../node_modules/@spryker/frontend-config.stylelint/.stylelintrc.json`;

const isFixMode = !!commandLineParser.fix;
const filePaths = commandLineParser.filePath ? [commandLineParser.filePath] : defaultFilePaths;
const configFile = commandLineParser.configPath ? commandLineParser.configPath : defaultConfigFile;

stylelint.lint({
    configFile: configFile,
    files: filePaths,
    syntax: "scss",
    formatter: "json",
    fix: isFixMode,
}).then(function(data) {
    if (data.errored) {
        const messages = JSON.parse(JSON.stringify(data.output));
        process.stdout.write(messages);
        process.exit(1);
    }
}).catch(function(error) {
    console.error(error.stack);
    process.exit(1);
});
