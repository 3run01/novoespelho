#!/bin/bash
# deploy-single.sh

COMMIT_HASH=$1
FEATURE_NAME=$2

echo "🎯 Deploying single commit: $COMMIT_HASH"

# Cria branch órfã (sem histórico)
git checkout --orphan "single-$FEATURE_NAME"

# Remove tudo do staging
git rm -rf .

# Aplica APENAS o commit específico
git cherry-pick $COMMIT_HASH --no-commit
git add .
git commit -m "$(git log --format=%s -1 $COMMIT_HASH)"

# Push da branch limpa
git push -u origin "single-$FEATURE_NAME"

echo "✅ Single commit deployed (clean history)"
echo "🧪 Branch: single-$FEATURE_NAME"