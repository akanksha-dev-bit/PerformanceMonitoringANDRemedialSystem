const fs = require("fs");
const path = require("path");

const fixFile = (filePath) => {
  const fullPath = path.resolve(filePath);
  let content = fs.readFileSync(fullPath, "utf-8");
  
  // â€” (E2 80 94 interpreted as windows-1252, resulting in C3 A2 E2 82 AC E2 80 9D)
  // Let's just match the exact bytes.
  content = content.replace(/â€”/g, "—");
  content = content.replace(/â€“/g, "–");
  content = content.replace(/â€™/g, "'");
  content = content.replace(/â€œ/g, "\"");
  content = content.replace(/â€\u009d/g, "\"");
  content = content.replace(/â€\u0098/g, "'");
  content = content.replace(/\?"/g, "—");

  fs.writeFileSync(fullPath, content, "utf-8");
  console.log("Fixed", filePath);
}

fixFile("frontend/src/pages/LandingPage.jsx");
fixFile("frontend/src/router/index.jsx");