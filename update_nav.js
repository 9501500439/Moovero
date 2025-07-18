const fs = require('fs');
const path = require('path');

// List of HTML files to update
const htmlFiles = [
    'about.html',
    'household.html',
    'office.html',
    'pet.html',
    'privacy.html',
    'services-details.html',
    'terms.html',
    'vehicle.html'
];

// The navigation pattern to find and update
const navPattern = /(<ul\s+class=["']nav-links["'][^>]*>.*?<li><a\s+[^>]*>About Us<\/a><\/li>\s*)(<li><a\s+[^>]*>Contact<\/a><\/li>)/gis;

// The replacement pattern with the new Process link
const replacement = '$1    <li><a href="process.html">Process</a></li>\n$2';

// Function to update a single file
function updateFile(fileName) {
    const filePath = path.join(__dirname, fileName);
    
    fs.readFile(filePath, 'utf8', (err, data) => {
        if (err) {
            console.error(`Error reading file ${fileName}:`, err);
            return;
        }

        // Check if the navigation already contains the Process link
        if (data.includes('href="process.html"')) {
            console.log(`Skipping ${fileName} - already contains Process link`);
            return;
        }

        // Replace the navigation pattern
        const updatedContent = data.replace(navPattern, replacement);

        // Write the updated content back to the file
        fs.writeFile(filePath, updatedContent, 'utf8', (err) => {
            if (err) {
                console.error(`Error writing file ${fileName}:`, err);
                return;
            }
            console.log(`Successfully updated ${fileName}`);
        });
    });
}

// Update all HTML files
htmlFiles.forEach(fileName => {
    updateFile(fileName);
});
