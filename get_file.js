const fs = require('fs');
const path = require('path');

/**
 * Recursively retrieves all PHP files from a directory.
 * @param {string} dir - The directory to search.
 * @param {string[]} fileList - The array to store file paths.
 * @returns {string[]} - List of PHP file paths.
 */
function getAllPHPFiles(dir, fileList = []) {
    const files = fs.readdirSync(dir);

    files.forEach(file => {
        const filePath = path.join(dir, file);
        const stat = fs.statSync(filePath);

        if (stat.isDirectory()) {
            getAllPHPFiles(filePath, fileList);
        } else if (filePath.endsWith('.php')) {
            fileList.push(filePath);
        }
    });

    return fileList;
}

/**
 * Combines content of all PHP files into a single text file.
 * @param {string} projectDir - The project directory to search.
 * @param {string} outputFile - The output text file path.
 */
function combinePHPFiles(projectDir, outputFile) {
    const phpFiles = getAllPHPFiles(projectDir);

    let combinedContent = '';

    phpFiles.forEach(filePath => {
        const fileContent = fs.readFileSync(filePath, 'utf-8');
        combinedContent += `Path: ${filePath}\n`;
        combinedContent += `Content:\n${fileContent}\n`;
        combinedContent += '\n-------------------------------\n';
    });

    fs.writeFileSync(outputFile, combinedContent, 'utf-8');
    console.log(`Combined PHP files written to ${outputFile}`);
}

// Specify the project directory and output file
const projectDir = '/var/www/html/online_flight_reservation_system'; // Replace with your project directory
const outputFile = '/var/www/html/online_flight_reservation_system/output.txt'; // Replace with your desired output file path

combinePHPFiles(projectDir, outputFile);
