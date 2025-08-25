#!/bin/bash
# deploy-single-debug.sh

COMMIT_HASH=$1
FEATURE_NAME=$2

echo "🎯 Deploying commit: $COMMIT_HASH"

# Verifica se commit existe
if ! git rev-parse --verify $COMMIT_HASH >/dev/null 2>&1; then
    echo "❌ ERRO: Commit $COMMIT_HASH não encontrado!"
    exit 1
fi

# Verifica se commit já está na main
if git branch --contains $COMMIT_HASH | grep -q "main"; then
    echo "⚠️  AVISO: Commit já existe na main!"
    echo "🔄 Aplicando mesmo assim..."
fi

# Cria branch
git checkout main
git checkout -b "single-$FEATURE_NAME"

# Cherry-pick com debug
echo "🍒 Fazendo cherry-pick..."
if git cherry-pick $COMMIT_HASH --no-commit; then
    echo "✅ Cherry-pick bem-sucedido"
    git status
    git commit -m "$(git log --format=%s -1 $COMMIT_HASH)"
else
    echo "❌ Cherry-pick falhou"
    git status
    exit 1
fi

echo "🚀 Finalizando..."
git push -u origin "single-$FEATURE_NAME"