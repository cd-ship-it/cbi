#!/bin/bash
# Script to sync production files to git repository
# Run this on the production server

echo "=== Syncing production files to git ==="
echo ""

# Navigate to codeigniter454 directory (adjust path as needed)
cd /path/to/codeigniter454 || cd ./codeigniter454

# Check current git status
echo "1. Checking git status..."
git status

echo ""
echo "2. Checking for untracked files in app/ directory..."
git status --porcelain app/ | head -20

echo ""
read -p "Do you want to proceed with adding all untracked/modified files? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    echo "Aborted."
    exit 1
fi

# Add all files in app directory
echo ""
echo "3. Adding all files in app/ directory..."
git add app/

# Show what will be committed
echo ""
echo "4. Files staged for commit:"
git status --short

echo ""
read -p "Do you want to commit these changes? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    echo "Aborted. Files are staged but not committed."
    exit 1
fi

# Commit
echo ""
echo "5. Committing changes..."
git commit -m "Sync production files to repository"

# Push
echo ""
echo "6. Pushing to remote repository..."
git push origin main

echo ""
echo "=== Sync complete ==="

