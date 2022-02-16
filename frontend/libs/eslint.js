const { ESLint } = require('eslint');
const { globalSettings } = require('../settings');
const commandLineParser = require('commander');
const eslintConfig = require('@spryker/frontend-config.eslint/.eslintrc.js');

commandLineParser
    .option('-f, --fix', 'execute eslint in the fix mode.')
    .option('-p, --file-path <path>', 'execute eslint only for this file.')
    .parse(process.argv);

const isFixMode = !!commandLineParser.fix;
const defaultFilePaths = [
    `${globalSettings.paths.project}/**/*.js`,
    `${globalSettings.paths.project}/**/*.ts`,
];
const filePaths = commandLineParser.filePath ? [commandLineParser.filePath] : defaultFilePaths;

(async () => {
    const options = {
        fix: isFixMode,
        baseConfig: eslintConfig,
    };
    const eslint = new ESLint(options);
    const results = await eslint.lintFiles(filePaths);

    if (options.fix) {
        await ESLint.outputFixes(results);
    }

    const formatter = await eslint.loadFormatter('json');
    const resultText = formatter.format(results);

    process.stdout.write(resultText);
    process.exit(0);
})().catch((error) => {
    console.error(JSON.stringify({message: error.message, messageData: error.messageData}));
    process.exit(1);
});
