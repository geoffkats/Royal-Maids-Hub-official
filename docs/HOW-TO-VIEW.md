# RoyalMaidsHub V3.0 Documentation - Quick Start

## Viewing the Documentation

### Option 1: MkDocs (Recommended - Beautiful UI)

1. **Install MkDocs** (requires Python):
   ```bash
   pip install mkdocs mkdocs-material
   ```

2. **Serve the docs locally**:
   ```bash
   mkdocs serve
   ```

3. **Open in browser**:
   Navigate to `http://127.0.0.1:8000`

4. **Build static site** (optional):
   ```bash
   mkdocs build
   ```
   This creates a `site/` folder with static HTML you can deploy anywhere.

### Option 2: GitHub/GitLab (Online Viewing)

1. Push to GitHub/GitLab
2. Navigate to the `docs/` folder in the web interface
3. GitHub automatically renders markdown files
4. Use the folder structure to navigate between pages

### Option 3: VS Code (Local Editing & Preview)

1. Open `docs/README.md` in VS Code
2. Press `Ctrl+Shift+V` (Windows) or `Cmd+Shift+V` (Mac) for preview
3. Click links in the preview to navigate between docs
4. Install "Markdown All in One" extension for better navigation

### Option 4: Simple Web Server

1. Navigate to docs folder:
   ```bash
   cd docs
   ```

2. Start a simple HTTP server:
   **Python 3:**
   ```bash
   python -m http.server 8000
   ```
   
   **PHP:**
   ```bash
   php -S localhost:8000
   ```

3. Open `http://localhost:8000/README.md` in your browser

### Option 5: Direct File Reading

Simply navigate to `c:\wamp64\www\RoyalMH-V5.0\royalMaids-v5\docs\` and open any `.md` file in:
- VS Code
- Notepad++
- Any markdown-compatible editor

Start with `docs/README.md` for the table of contents.

## Recommended Viewing Order

1. `docs/README.md` - Table of contents
2. `docs/Overview.md` - System overview
3. `docs/Setup.md` - Installation guide
4. `docs/Architecture.md` - How the system is structured
5. `docs/Modules/CRM/Overview.md` - CRM feature set
6. Explore other modules as needed

## Tips

- Use Ctrl+F / Cmd+F to search within documents
- The MkDocs search feature (Option 1) is the most powerful for finding specific topics
- All internal links use relative paths, so navigation works in any viewer
