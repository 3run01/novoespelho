#!/bin/bash
# deploy-single-mr.sh
# Uso: ./deploy-single-mr.sh <commit-hash> <feature-name>

set -euo pipefail

COMMIT_HASH=$1
FEATURE_NAME=$2
BRANCH_NAME="deploy/$FEATURE_NAME"

echo "🎯 Deploying commit: $COMMIT_HASH → branch $BRANCH_NAME (base: producao)"

# Verifica se commit existe
if ! git cat-file -e "$COMMIT_HASH"^{commit} 2>/dev/null; then
    echo "❌ ERRO: Commit $COMMIT_HASH não encontrado!"
    exit 1
fi

# Atualiza producao
git fetch origin producao
git checkout producao
git reset --hard origin/producao

# Cria branch baseada em producao
if git rev-parse --verify "$BRANCH_NAME" >/dev/null 2>&1; then
    echo "⚠️ Branch $BRANCH_NAME já existe, removendo..."
    git branch -D "$BRANCH_NAME"
fi
git checkout -b "$BRANCH_NAME"

# Cherry-pick do commit escolhido
echo "🍒 Fazendo cherry-pick..."
if git cherry-pick "$COMMIT_HASH"; then
    echo "✅ Cherry-pick aplicado com sucesso"
else
    echo "❌ Cherry-pick falhou"
    git cherry-pick --abort || true
    git checkout producao
    git branch -D "$BRANCH_NAME"
    exit 1
fi

# Push para o remoto
git push -u origin "$BRANCH_NAME" --force
echo "🚀 Branch publicada: origin/$BRANCH_NAME"
echo "✅ Agora abra um Merge Request no GitLab para a branch producao."
