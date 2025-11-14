# Guide: Syncing Production Files to Git Repository

If your production server has files in the `app/` directory that aren't tracked by git, follow these steps:

## Option 1: Manual Steps (Recommended for first time)

### Step 1: SSH into Production Server
```bash
ssh user@your-production-server
```

### Step 2: Navigate to CodeIgniter Directory
```bash
cd /path/to/your/codeigniter454
# or wherever your codeigniter454 directory is located
```

### Step 3: Check Git Status
```bash
git status
```

This will show you:
- Untracked files (files that exist but aren't in git)
- Modified files (files that differ from git)
- Deleted files (files in git but not on server)

### Step 4: Review What Will Be Added
```bash
# See untracked files
git status --porcelain app/ | grep "^??"

# See differences in modified files (if any)
git diff app/
```

### Step 5: Add Files to Git
```bash
# Add all files in app/ directory
git add app/

# Or add specific files/directories
git add app/Controllers/
git add app/Models/
# etc.
```

### Step 6: Review Staged Changes
```bash
git status
git diff --cached --stat  # Summary of what will be committed
```

### Step 7: Commit Changes
```bash
git commit -m "Sync production files to repository"
```

### Step 8: Push to GitHub
```bash
git push origin main
```

## Option 2: Using the Script

1. Copy `sync_production_to_git.sh` to your production server
2. Make it executable:
   ```bash
   chmod +x sync_production_to_git.sh
   ```
3. Edit the script to set the correct path to your codeigniter454 directory
4. Run the script:
   ```bash
   ./sync_production_to_git.sh
   ```

## Important Notes

### Before Syncing:
1. **Backup First**: Make sure you have a backup of your production files
2. **Check for Conflicts**: If files exist in both git and production with different content, you'll need to resolve conflicts
3. **Environment Files**: Don't commit `.env` files or sensitive configuration
4. **Review Changes**: Always review what you're committing before pushing

### Handling Conflicts:
If a file exists in both git and production with different content:
```bash
# See the differences
git diff app/path/to/file.php

# Choose one of these options:
# Option A: Keep production version
git checkout --theirs app/path/to/file.php
git add app/path/to/file.php

# Option B: Keep git version
git checkout --ours app/path/to/file.php
git add app/path/to/file.php

# Option C: Manually merge
# Edit the file to combine both versions, then:
git add app/path/to/file.php
```

### Excluding Files:
If there are files you don't want to commit, add them to `.gitignore`:
```bash
echo "app/path/to/file.php" >> .gitignore
git add .gitignore
git commit -m "Update .gitignore"
```

## Quick Command Summary

```bash
# On production server:
cd /path/to/codeigniter454
git status                    # Check what needs syncing
git add app/                  # Add all app files
git commit -m "Sync production files"
git push origin main          # Push to GitHub
```

