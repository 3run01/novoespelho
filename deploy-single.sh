#!/bin/bash
# deploy-single-preservando.sh

COMMIT_HASH=$1
FEATURE_NAME=$2

echo "🎯 Deploying single commit preserving existing code: $COMMIT_HASH"

# Cria branch a partir da main (preserva código existente)
git checkout main
git checkout -b "single-$FEATURE_NAME"

# Aplica APENAS as mudanças do commit específico
git cherry-pick $COMMIT_HASH --no-commit

# Se der conflito, resolve automaticamente
if [ $? -ne 0 ]; then
    echo "⚠️  Resolvendo conflitos automaticamente..."
    git add .
fi

# Commita apenas as mudanças do commit específico
git commit -m "$(git log --format=%s -1 $COMMIT_HASH)"

# Push
git push -u origin "single-$FEATURE_NAME"

echo "✅ Single commit aplicado preservando código existente"