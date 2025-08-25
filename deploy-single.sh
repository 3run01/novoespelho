#!/bin/bash
# deploy-single.sh

COMMIT_HASH=$1
FEATURE_NAME=$2

echo "🎯 Deploying single commit: $COMMIT_HASH"

# Cria branch com apenas o commit específico
git checkout main
git checkout -b "single-$FEATURE_NAME" $COMMIT_HASH^
git cherry-pick $COMMIT_HASH

# Deploy para dev
git checkout main  
git merge "single-$FEATURE_NAME"

echo "✅ Single commit deployed to DEV"
echo "🧪 Test and run: ./promote-single.sh $FEATURE_NAME"