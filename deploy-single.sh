#!/bin/bash
# deploy-single-mr.sh
# Uso: ./deploy-single-mr.sh <commit-hash> <feature-name>

set -euo pipefail

COMMIT_HASH=$1
FEATURE_NAME=$2
BRANCH_NAME="deploy/$FEATURE_NAME"
BASE_BRANCH="producao"

# Casos especiais: continuar cherry-pick ou mostrar ajuda
if [ "$1" = "continuar" ] && [ -n "$2" ]; then
    FEATURE_NAME=$2
    BRANCH_NAME="deploy/$FEATURE_NAME"
    
    echo "[INFO] Continuando processo para branch: $BRANCH_NAME"
    
    if git cherry-pick --continue; then
        echo "[SUCCESS] Cherry-pick finalizado com sucesso"
        git push -u origin "$BRANCH_NAME"
        echo "[SUCCESS] Branch $BRANCH_NAME enviada para repositório remoto"
    else
        echo "[ERROR] Ainda existem conflitos não resolvidos"
    fi
    exit 0
fi

if [ "$1" = "help" ] || [ "$1" = "--help" ] || [ "$1" = "-h" ]; then
    echo "=================================="
    echo "  DEPLOY SINGLE MR - HELP"
    echo "=================================="
    echo ""
    echo "USAGE:"
    echo "  $0 <commit-hash> <feature-name>"
    echo "  $0 continuar <feature-name>  # Continue after resolving conflicts"
    echo ""
    echo "EXAMPLES:"
    echo "  $0 a1b2c3d fix-login-bug"
    echo "  $0 continuar fix-login-bug"
    echo ""
    echo "WORKFLOW:"
    echo "  1. Creates clean branch based on 'producao'"
    echo "  2. Applies only the specific commit via cherry-pick"
    echo "  3. Pushes branch ready for clean MR without history"
    echo ""
    echo "=================================="
    exit 0
fi

# Validação de parâmetros
if [ -z "$COMMIT_HASH" ] || [ -z "$FEATURE_NAME" ]; then
    echo "[ERROR] Invalid usage"
    echo "Usage: $0 <commit-hash> <feature-name>"
    echo "Example: $0 abc123 fix-login-bug"
    exit 1
fi

echo "=================================="
echo "  DEPLOY SINGLE MR"
echo "=================================="
echo "Target commit: $COMMIT_HASH"
echo "Feature branch: $BRANCH_NAME"
echo "Base branch: $BASE_BRANCH"
echo "----------------------------------"

# Salva contexto atual (branch e stash se necessário)
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
STASH_CREATED=false

echo "[INFO] Current branch: $CURRENT_BRANCH"

# Verifica se há mudanças não commitadas
if ! git diff-index --quiet HEAD -- 2>/dev/null; then
    echo "[WARN] Uncommitted changes detected, creating temporary stash"
    git stash push -m "deploy-script-auto-stash-$(date +%s)" --include-untracked
    STASH_CREATED=true
fi

# Função de limpeza segura
cleanup() {
    echo "[INFO] Executing cleanup procedure"
    
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
        echo "[CLEANUP] Removing created branch: $BRANCH_NAME"
        git branch -D "$BRANCH_NAME" 2>/dev/null || true
    fi
    
    # Restaura stash se foi criado
    if [ "$STASH_CREATED" = true ]; then
        echo "[CLEANUP] Restoring uncommitted changes"
        git stash pop --quiet 2>/dev/null || echo "[WARN] Could not restore stash automatically"
    fi
}

trap cleanup ERR

# Verifica se o commit existe
echo "[STEP 1/6] Validating target commit"
if ! git cat-file -e "$COMMIT_HASH"^{commit} 2>/dev/null; then
    echo "[ERROR] Commit $COMMIT_HASH not found"
    echo "Tip: Run 'git log --oneline' to see available commits"
    exit 1
fi

# Mostra informações do commit
echo "[SUCCESS] Target commit found:"
git show --no-patch --format="  %h - %s (%an, %ar)" "$COMMIT_HASH"

# Atualiza referências remotas de forma segura
echo ""
echo "[STEP 2/6] Updating remote references"
git fetch origin --prune

# Verifica se temos acesso ao remoto
if ! git ls-remote --exit-code origin >/dev/null 2>&1; then
    echo "[ERROR] Cannot access remote repository"
    exit 1
fi

# Verifica se a branch de produção existe no remoto
if ! git ls-remote --exit-code origin "refs/heads/$BASE_BRANCH" >/dev/null 2>&1; then
    echo "[ERROR] Base branch '$BASE_BRANCH' does not exist on remote"
    echo "Available branches:"
    git ls-remote --heads origin | sed 's/.*refs\/heads\//  /'
    exit 1
fi

echo "[SUCCESS] Remote validation completed"

# Remove branch de deploy local se já existir
echo ""
echo "[STEP 3/6] Preparing deployment branch"
if git show-ref --verify --quiet refs/heads/"$BRANCH_NAME"; then
    echo "[CLEANUP] Removing existing local branch: $BRANCH_NAME"
    git branch -D "$BRANCH_NAME"
fi

# Remove branch remota se existir (de forma segura)
if git ls-remote --exit-code origin "refs/heads/$BRANCH_NAME" >/dev/null 2>&1; then
    echo "[CLEANUP] Removing existing remote branch: $BRANCH_NAME"
    git push origin --delete "$BRANCH_NAME" || {
        echo "[WARN] Could not remove remote branch automatically"
        echo "Manual removal may be required: git push origin --delete $BRANCH_NAME"
    }
fi

# Cria branch diretamente do commit remoto (método mais seguro)
echo "[CREATE] Creating branch $BRANCH_NAME from origin/$BASE_BRANCH"
git checkout -b "$BRANCH_NAME" "origin/$BASE_BRANCH"

# Verifica que estamos na base correta
BASE_COMMIT=$(git rev-parse HEAD)
REMOTE_BASE_COMMIT=$(git rev-parse "origin/$BASE_BRANCH")

if [ "$BASE_COMMIT" != "$REMOTE_BASE_COMMIT" ]; then
    echo "[ERROR] Branch was not created correctly from remote base"
    exit 1
fi

echo "[SUCCESS] Branch created at: $(git rev-parse --short HEAD)"

# Aplica cherry-pick do commit específico
echo ""
echo "[STEP 4/6] Applying cherry-pick"
echo "Cherry-picking commit: $COMMIT_HASH"
if git cherry-pick "$COMMIT_HASH" --no-edit; then
    echo "[SUCCESS] Cherry-pick applied successfully"
    
    # Mostra resumo das alterações
    echo ""
    echo "Changes summary:"
    git show --stat --format="" HEAD | sed 's/^/  /'
    echo ""
    
else
    echo "[ERROR] Cherry-pick failed - conflicts detected"
    echo ""
    echo "CONFLICT RESOLUTION STEPS:"
    echo "  1. Resolve conflicts in the files indicated above"
    echo "  2. git add <resolved-files>"
    echo "  3. $0 continuar $FEATURE_NAME"
    echo ""
    echo "TO CANCEL:"
    echo "  git cherry-pick --abort"
    echo "  git checkout $CURRENT_BRANCH"
    echo "  git branch -D $BRANCH_NAME"
    
    # Não faz cleanup automático para permitir resolução manual
    trap - ERR
    exit 1
fi

# Validação final: verifica histórico limpo
echo "[STEP 5/6] Validating clean history"
COMMITS_AHEAD=$(git rev-list --count "origin/$BASE_BRANCH..HEAD")
if [ "$COMMITS_AHEAD" -eq 1 ]; then
    echo "[SUCCESS] Clean history confirmed: 1 commit ahead of $BASE_BRANCH"
else
    echo "[WARN] Found $COMMITS_AHEAD commits ahead (expected: 1)"
    echo "Please verify cherry-pick was applied correctly"
fi

# Push seguro para o remoto
echo ""
echo "[STEP 6/6] Publishing branch to remote"
if git push -u origin "$BRANCH_NAME"; then
    echo "[SUCCESS] Branch pushed successfully"
else
    echo "[ERROR] Push failed - check connectivity and permissions"
    exit 1
fi

echo ""
echo "=================================="
echo "  DEPLOYMENT COMPLETED"
echo "=================================="
echo "Branch: $BRANCH_NAME"
echo "Base: $BASE_BRANCH ($(git rev-parse --short "origin/$BASE_BRANCH"))"
echo "Commit: $(git rev-parse --short HEAD) - $(git log -1 --format="%s")"
echo "Remote: origin/$BRANCH_NAME"
echo ""
echo "NEXT STEPS:"
echo "  1. Open Merge Request: $BRANCH_NAME -> $BASE_BRANCH"
echo "  2. MR will have clean history (single commit)"
echo "  3. Branch will be auto-deleted after merge"
echo "=================================="

# Retorna ao contexto original de forma segura
git checkout "$CURRENT_BRANCH"

# Restaura stash se foi criado
if [ "$STASH_CREATED" = true ]; then
    echo "[INFO] Restoring uncommitted changes"
    if git stash pop --quiet; then
        echo "[SUCCESS] Changes restored successfully"
    else
        echo "[WARN] Check stash manually: git stash list"
    fi
fi

echo "[INFO] Returned to branch: $CURRENT_BRANCH"
echo "[SUCCESS] Process completed successfully"