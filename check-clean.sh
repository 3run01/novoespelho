#!/bin/bash
# check-clean.sh

BRANCH_NAME=$1

echo "🔍 Verificando limpeza da branch: $BRANCH_NAME"

# Muda para a branch
git checkout $BRANCH_NAME

# Conta commits desta branch
BRANCH_COMMITS=$(git rev-list --count HEAD)

# Conta commits da main
git checkout main
MAIN_COMMITS=$(git rev-list --count HEAD)

# Calcula diferença
DIFF=$((BRANCH_COMMITS - MAIN_COMMITS))

echo "📊 Main tem: $MAIN_COMMITS commits"
echo "📊 $BRANCH_NAME tem: $BRANCH_COMMITS commits"
echo "📊 Diferença: $DIFF commits"

if [ $DIFF -eq 1 ]; then
    echo "✅ PERFEITO: Apenas 1 commit adicionado!"
    git log main..$BRANCH_NAME --oneline
else
    echo "⚠️  ATENÇÃO: $DIFF commits foram adicionados"
    git log main..$BRANCH_NAME --oneline
fi