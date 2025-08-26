#!/bin/bash
# deploy-single-mr.sh
# Uso: ./deploy-single-mr.sh <commit-hash> <feature-name>
# 
# Script seguro para criar branch limpa para MR baseada em produção

set -e

COMMIT_HASH=$1
FEATURE_NAME=$2
BRANCH_NAME="deploy/$FEATURE_NAME"
BASE_BRANCH="producao"

# Casos especiais: continuar cherry-pick ou mostrar ajuda
if [ "$1" = "continuar" ] && [ -n "$2" ]; then
    FEATURE_NAME=$2
    BRANCH_NAME="deploy/$FEATURE_NAME"
    
    echo "🔄 Continuando processo para $BRANCH_NAME..."
    
    if git cherry-pick --continue; then
        echo "✅ Cherry-pick finalizado!"
        git push -u origin "$BRANCH_NAME"
        echo "🎉 Branch $BRANCH_NAME enviada com sucesso!"
    else
        echo "❌ Ainda há conflitos para resolver"
    fi
    exit 0
fi

if [ "$1" = "help" ] || [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
    echo "📖 AJUDA - deploy-single-mr.sh"
    echo ""
    echo "USO:"
    echo "  $0 <commit-hash> <feature-name>"
    echo "  $0 continuar <feature-name>  # Para continuar após resolver conflitos"
    echo ""
    echo "EXEMPLOS:"
    echo "  $0 a1b2c3d fix-login-bug"
    echo "  $0 continuar fix-login-bug"
    echo ""
    echo "FLUXO:"
    echo "  1. Script cria branch limpa baseada em 'producao'"
    echo "  2. Aplica apenas o commit específico via cherry-pick"
    echo "  3. Push da branch para abrir MR sem histórico"
    exit 0
fi

# Validação de parâmetros
if [ -z "$COMMIT_HASH" ] || [ -z "$FEATURE_NAME" ]; then
    echo "❌ ERRO: Uso correto: $0 <commit-hash> <feature-name>"
    echo "   Exemplo: $0 abc123 fix-login-bug"
    exit 1
fi

echo "🎯 Iniciando deploy do commit: $COMMIT_HASH → branch $BRANCH_NAME"
echo "📋 Base: $BASE_BRANCH"

# Salva contexto atual (branch e stash se necessário)
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
STASH_CREATED=false

echo "📍 Branch atual: $CURRENT_BRANCH"

# Verifica se há mudanças não commitadas
if ! git diff-index --quiet HEAD -- 2>/dev/null; then
    echo "💾 Detectadas mudanças não commitadas, criando stash temporário..."
    git stash push -m "deploy-script-auto-stash-$(date +%s)" --include-untracked
    STASH_CREATED=true
fi

# Função de limpeza segura
cleanup() {
    echo "🧹 Executando limpeza..."
    
    # Aborta cherry-pick se estiver em andamento
    if [ -f .git/CHERRY_PICK_HEAD ]; then
        git cherry-pick --abort 2>/dev/null || true
    fi
    
    # Volta para branch original
    if [ "$CURRENT_BRANCH" != "$(git rev-parse --abbrev-ref HEAD 2>/dev/null)" ]; then
        git checkout "$CURRENT_BRANCH" 2>/dev/null || true
    fi
    
    # Remove branch criada se falhou
    if git show-ref --verify --quiet refs/heads/"$BRANCH_NAME" 2>/dev/null; then
        echo "🗑️  Removendo branch criada: $BRANCH_NAME"
        git branch -D "$BRANCH_NAME" 2>/dev/null || true
    fi
    
    # Restaura stash se foi criado
    if [ "$STASH_CREATED" = true ]; then
        echo "🔄 Restaurando mudanças não commitadas..."
        git stash pop --quiet 2>/dev/null || echo "⚠️  Não foi possível restaurar stash automaticamente"
    fi
}

trap cleanup ERR

# Verifica se o commit existe
echo "🔍 Verificando se o commit existe..."
if ! git cat-file -e "$COMMIT_HASH"^{commit} 2>/dev/null; then
    echo "❌ ERRO: Commit $COMMIT_HASH não encontrado!"
    echo "💡 Dica: Execute 'git log --oneline' para ver commits disponíveis"
    exit 1
fi

# Mostra informações do commit
echo "📝 Commit selecionado:"
git show --no-patch --format="   %h - %s (%an, %ar)" "$COMMIT_HASH"

# Atualiza referências remotas de forma segura
echo "🔄 Atualizando referências remotas..."
git fetch origin --prune

# Verifica se temos acesso ao remoto
if ! git ls-remote --exit-code origin >/dev/null 2>&1; then
    echo "❌ ERRO: Não é possível acessar o repositório remoto"
    exit 1
fi

# Verifica se a branch de produção existe no remoto
if ! git ls-remote --exit-code origin "refs/heads/$BASE_BRANCH" >/dev/null 2>&1; then
    echo "❌ ERRO: Branch '$BASE_BRANCH' não existe no remoto"
    echo "💡 Branches disponíveis:"
    git ls-remote --heads origin | sed 's/.*refs\/heads\//   /'
    exit 1
fi

# Remove branch de deploy local se já existir
if git show-ref --verify --quiet refs/heads/"$BRANCH_NAME"; then
    echo "⚠️  Removendo branch local existente: $BRANCH_NAME"
    git branch -D "$BRANCH_NAME"
fi

# Remove branch remota se existir (de forma segura)
if git ls-remote --exit-code origin "refs/heads/$BRANCH_NAME" >/dev/null 2>&1; then
    echo "🗑️  Removendo branch remota existente: $BRANCH_NAME"
    git push origin --delete "$BRANCH_NAME" || {
        echo "⚠️  Não foi possível remover branch remota automaticamente"
        echo "💡 Remova manualmente se necessário: git push origin --delete $BRANCH_NAME"
    }
fi

# Cria branch diretamente do commit remoto (método mais seguro)
echo "🌿 Criando branch $BRANCH_NAME baseada em origin/$BASE_BRANCH..."
git checkout -b "$BRANCH_NAME" "origin/$BASE_BRANCH"

# Verifica que estamos na base correta
BASE_COMMIT=$(git rev-parse HEAD)
REMOTE_BASE_COMMIT=$(git rev-parse "origin/$BASE_BRANCH")

if [ "$BASE_COMMIT" != "$REMOTE_BASE_COMMIT" ]; then
    echo "❌ ERRO: Branch não foi criada corretamente da base remota"
    exit 1
fi

echo "✅ Branch criada na base: $(git rev-parse --short HEAD)"

# Aplica cherry-pick do commit específico
echo "🍒 Aplicando cherry-pick do commit $COMMIT_HASH..."
if git cherry-pick "$COMMIT_HASH" --no-edit; then
    echo "✅ Cherry-pick aplicado com sucesso!"
    
    # Mostra resumo das alterações
    echo ""
    echo "📊 Resumo das alterações aplicadas:"
    git show --stat --format="" HEAD
    echo ""
    
else
    echo "❌ Cherry-pick falhou - há conflitos para resolver"
    echo ""
    echo "🔧 Para resolver:"
    echo "   1. Resolva os conflitos nos arquivos indicados acima"
    echo "   2. git add <arquivos-resolvidos>"
    echo "   3. $0 continuar $FEATURE_NAME"
    echo ""
    echo "🚫 Para cancelar:"
    echo "   git cherry-pick --abort"
    echo "   git checkout $CURRENT_BRANCH"
    echo "   git branch -D $BRANCH_NAME"
    
    # Não faz cleanup automático para permitir resolução manual
    trap - ERR
    exit 1
fi

# Validação final: verifica histórico limpo
COMMITS_AHEAD=$(git rev-list --count "origin/$BASE_BRANCH..HEAD")
if [ "$COMMITS_AHEAD" -eq 1 ]; then
    echo "✅ Histórico limpo: apenas 1 commit à frente de $BASE_BRANCH"
else
    echo "⚠️  ATENÇÃO: $COMMITS_AHEAD commits à frente (esperado: 1)"
    echo "💡 Verifique se o cherry-pick foi aplicado corretamente"
fi

# Push seguro para o remoto
echo "🚀 Enviando branch para o remoto..."
if git push -u origin "$BRANCH_NAME"; then
    echo "✅ Branch enviada com sucesso!"
else
    echo "❌ Falha no push - verifique conectividade e permissões"
    exit 1
fi

echo ""
echo "🎉 ===== DEPLOY CONCLUÍDO ====="
echo "✅ Branch: $BRANCH_NAME"
echo "📋 Base: $BASE_BRANCH ($(git rev-parse --short "origin/$BASE_BRANCH"))"
echo "📝 Commit: $(git rev-parse --short HEAD) - $(git log -1 --format="%s")"
echo "🌐 Remoto: origin/$BRANCH_NAME"
echo ""
echo "🔗 Próximos passos:"
echo "   1. Abrir Merge Request: $BRANCH_NAME → $BASE_BRANCH"
echo "   2. O MR terá histórico limpo (apenas 1 commit)"
echo "   3. Após merge, a branch será removida automaticamente"
echo ""

# Retorna ao contexto original de forma segura
git checkout "$CURRENT_BRANCH"

# Restaura stash se foi criado
if [ "$STASH_CREATED" = true ]; then
    echo "🔄 Restaurando mudanças não commitadas..."
    if git stash pop --quiet; then
        echo "✅ Mudanças restauradas"
    else
        echo "⚠️  Verifique o stash manualmente: git stash list"
    fi
fi

echo "🔙 Voltou para: $CURRENT_BRANCH"
echo "🎯 Processo finalizado com sucesso!"