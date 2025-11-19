# Guide: Pulling Latest Code from GitHub on Production Server

This guide explains how to pull the latest code changes from GitHub to your production server.

## Quick Steps

### Step 1: SSH into Production Server
```bash
ssh user@your-production-server
```

### Step 2: Navigate to CodeIgniter Directory
```bash
cd /path/to/your/codeigniter454
# Example: cd /var/www/html/cbi/codeigniter454
```

### Step 3: Check Current Status
```bash
git status
```

This will show you:
- If you have any uncommitted local changes
- If you're behind the remote repository
- Current branch

### Step 4: Stash Local Changes (if any)
If you have uncommitted local changes that you want to keep:
```bash
git stash
```

Or if you want to discard local changes:
```bash
git reset --hard HEAD
```

**⚠️ Warning:** `git reset --hard` will permanently delete any uncommitted local changes!

### Step 5: Fetch Latest Changes
```bash
git fetch origin
```

This downloads the latest changes without merging them.

### Step 6: Pull Latest Changes
```bash
git pull origin main
```

This will:
- Download the latest commits from GitHub
- Merge them into your local branch
- Update your working directory with the new files

### Step 7: Verify the Changes
```bash
# Check what was updated
git log --oneline -5

# Verify the files were updated
git status
```

### Step 8: Clear Cache (if needed)
If you're using CodeIgniter's cache, you may want to clear it:
```bash
# Clear CodeIgniter cache
rm -rf writable/cache/*

# Or if you have specific cache directories
rm -rf writable/cache/views/*
rm -rf writable/cache/pages/*
```

## Handling Merge Conflicts

If you get merge conflicts during `git pull`:

1. **See what files have conflicts:**
   ```bash
   git status
   ```

2. **Resolve conflicts manually:**
   - Open the conflicted files
   - Look for conflict markers: `<<<<<<<`, `=======`, `>>>>>>>`
   - Edit the files to resolve conflicts
   - Save the files

3. **Mark conflicts as resolved:**
   ```bash
   git add <resolved-file>
   ```

4. **Complete the merge:**
   ```bash
   git commit
   ```

## One-Line Command (if no local changes)

If you're sure there are no local changes to preserve:

```bash
cd /path/to/codeigniter454 && git fetch origin && git reset --hard origin/main
```

**⚠️ Warning:** This will discard ALL local uncommitted changes!

## Recommended Safe Workflow

```bash
# 1. Navigate to directory
cd /path/to/codeigniter454

# 2. Check status
git status

# 3. Stash any local changes (if needed)
git stash

# 4. Pull latest code
git pull origin main

# 5. Apply stashed changes back (if you stashed)
git stash pop

# 6. Clear cache
rm -rf writable/cache/*
```

## After Pulling

1. **Test the application** to ensure everything works
2. **Check logs** for any errors:
   ```bash
   tail -f writable/logs/log-*.php
   ```
3. **Verify the changes** are working as expected

## Troubleshooting

### "Your branch is behind 'origin/main'"
This means you need to pull. Run `git pull origin main`.

### "Your branch has diverged"
This means you have local commits that aren't on GitHub. You may need to:
- Merge: `git pull --rebase origin main`
- Or reset: `git reset --hard origin/main` (⚠️ loses local commits)

### "Permission denied"
Make sure the web server user has read permissions on the files:
```bash
chown -R www-data:www-data /path/to/codeigniter454
chmod -R 755 /path/to/codeigniter454
```

### "Could not resolve host: github.com"
Check your network connection and DNS settings.

## For the Current OAuth Session Changes

After pulling, the following files should be updated:
- `app/Config/Session.php` (60 days expiration)
- `app/Controllers/OAuth.php` (refresh token support)
- All Google OAuth login views

No additional configuration needed - the changes take effect immediately!


