const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, 'resources', 'views');
const iconsDir = path.join(__dirname, 'node_modules', 'lucide-static', 'icons');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) {
            results = results.concat(walk(file));
        } else if (file.endsWith('.blade.php')) {
            results.push(file);
        }
    });
    return results;
}

const files = walk(viewsDir);
let totalReplaced = 0;

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    
    // Optional: Remove the lucide script from layouts/app.blade.php
    if (file.includes('app.blade.php')) {
        content = content.replace(/<script src="https:\/\/unpkg\.com\/lucide@latest"><\/script>\s*/g, '');
        content = content.replace(/lucide\.createIcons\(\);\s*/g, '');
    }

    if (file.includes('login.blade.php')) {
        content = content.replace(/<script src="https:\/\/unpkg\.com\/lucide@latest"><\/script>\s*/g, '');
        content = content.replace(/lucide\.createIcons\(\);\s*/g, '');
    }
    
    // Regex: <i ... data-lucide="NAME" ...></i>
    const regex = /<i\s+([^>]*data-lucide="([^"]+)"[^>]*)>\s*<\/i>/g;
    
    let result = content.replace(regex, (match, attrs, iconName) => {
        const svgPath = path.join(iconsDir, `${iconName}.svg`);
        if (fs.existsSync(svgPath)) {
            let svgContent = fs.readFileSync(svgPath, 'utf8');
            
            const classMatch = attrs.match(/class="([^"]*)"/);
            const styleMatch = attrs.match(/style="([^"]*)"/);
            
            const customClasses = classMatch ? classMatch[1] : '';
            const customStyles = styleMatch ? styleMatch[1] : '';
            
            // Remove existing class="..." inside SVG
            svgContent = svgContent.replace(/\s*class="[^"]*"/, '');
            
            // Insert custom classes and styles
            if (customStyles) {
                svgContent = svgContent.replace('<svg', `<svg style="${customStyles}" class="${customClasses}"`);
            } else if (customClasses) {
                svgContent = svgContent.replace('<svg', `<svg class="${customClasses}"`);
            }
            
            totalReplaced++;
            return svgContent;
        } else {
            console.log(`Warning: Icon ${iconName} not found in lucide-static`);
            return match;
        }
    });

    if (content !== result) {
        fs.writeFileSync(file, result, 'utf8');
        console.log(`Updated: ${file}`);
    }
});

console.log(`\nSuccess! Replaced ${totalReplaced} icons.`);
