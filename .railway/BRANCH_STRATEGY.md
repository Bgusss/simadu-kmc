# 🌿 Branch Strategy Guide

Guide untuk workflow development dengan branch strategy yang proper.

## 📊 Branch Structure

```
main (production)
├─ develop (development)
│  ├─ feature/ai-classification
│  ├─ feature/whatsapp-integration
│  └─ fix/ticket-duplicate-bug
└─ hotfix/critical-security-fix
```

## 🎯 Branch Types

### 1. `main` - Production Branch
- ✅ **Always stable & deployable**
- 🚀 **Auto-deploy ke Railway**
- 🔒 **Protected** (require PR review)
- 📦 **Tagged releases** (v1.0.0, v1.1.0)

**Rules:**
- ❌ JANGAN commit langsung ke main
- ✅ HARUS via Pull Request dari develop
- ✅ HARUS passing all tests
- ✅ HARUS reviewed

### 2. `develop` - Development Branch
- 🧪 **Integration branch** untuk fitur-fitur baru
- 💻 **Default branch** untuk development
- 🔄 **Merged to main** setelah stable

**Rules:**
- ✅ Boleh commit langsung (untuk quick fixes)
- ✅ Testing di local sebelum merge ke main
- ✅ Selalu up-to-date dengan main

### 3. `feature/*` - Feature Branches
- 🎨 **Untuk fitur baru**
- 🔬 **Eksperimen bebas**
- 📝 **Naming**: `feature/nama-fitur-singkat`

**Examples:**
- `feature/telegram-bot`
- `feature/export-excel`
- `feature/dashboard-chart`

### 4. `fix/*` - Bug Fix Branches
- 🐛 **Untuk bug fixes**
- 📝 **Naming**: `fix/nama-bug-singkat`

**Examples:**
- `fix/login-redirect-loop`
- `fix/notification-duplicate`
- `fix/ai-classification-accuracy`

### 5. `hotfix/*` - Hotfix Branches
- 🚨 **Urgent fixes** untuk production
- 🔥 **Langsung dari main**
- 📝 **Naming**: `hotfix/critical-issue`

**Examples:**
- `hotfix/security-vulnerability`
- `hotfix/database-connection-error`

## 🔄 Workflow

### Setup Initial Branches

```bash
# Create develop branch
git checkout -b develop
git push -u origin develop

# Set develop as default working branch
git checkout develop
```

### Daily Development Workflow

```bash
# 1. Start new feature
git checkout develop
git pull origin develop
git checkout -b feature/new-awesome-feature

# 2. Code & commit
git add .
git commit -m "feat: add awesome feature"

# 3. Push feature branch
git push -u origin feature/new-awesome-feature

# 4. Test locally
php artisan serve
npm run dev
# Test: http://localhost:8000

# 5. Merge to develop (if stable)
git checkout develop
git merge feature/new-awesome-feature
git push origin develop

# 6. Delete feature branch (cleanup)
git branch -d feature/new-awesome-feature
git push origin --delete feature/new-awesome-feature
```

### Deploy to Production

```bash
# 1. Make sure develop is stable
git checkout develop
php artisan test
npm run build

# 2. Merge to main
git checkout main
git pull origin main
git merge develop

# 3. Tag release (optional)
git tag -a v1.1.0 -m "Release v1.1.0: Add awesome features"
git push origin v1.1.0

# 4. Push to main (triggers Railway auto-deploy)
git push origin main

# 5. Wait for Railway deployment (2-5 min)
# 6. Verify production: https://your-app.railway.app
```

### Emergency Hotfix

```bash
# 1. Branch from main
git checkout main
git checkout -b hotfix/critical-bug

# 2. Fix the bug
# ... code fix ...
git add .
git commit -m "hotfix: fix critical security issue"

# 3. Merge to main
git checkout main
git merge hotfix/critical-bug
git push origin main

# 4. Merge back to develop
git checkout develop
git merge hotfix/critical-bug
git push origin develop

# 5. Delete hotfix branch
git branch -d hotfix/critical-bug
```

## 📝 Commit Message Convention

Gunakan [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation only
- `style`: Formatting, semicolons, etc (no code change)
- `refactor`: Code refactoring
- `test`: Adding/updating tests
- `chore`: Maintenance, dependencies, etc

### Examples

```bash
# Feature
git commit -m "feat(tickets): add bulk assignment feature"

# Bug fix
git commit -m "fix(auth): resolve login redirect loop"

# Documentation
git commit -m "docs(readme): update installation guide"

# Refactor
git commit -m "refactor(ai): optimize classification performance"

# Chore
git commit -m "chore(deps): update Laravel to 13.9"
```

## 🛡️ Branch Protection (GitHub Settings)

### Protect `main` branch:

1. GitHub → Settings → Branches
2. Add rule untuk `main`:
   - ✅ Require pull request before merging
   - ✅ Require approvals (1)
   - ✅ Require status checks to pass
   - ✅ Require branches to be up to date
   - ❌ Allow force pushes (DISABLE)
   - ❌ Allow deletions (DISABLE)

## 📊 Visual Workflow

```
develop (your daily work)
   │
   ├─ feature/awesome-1 ──┐
   │                       │
   ├─ feature/awesome-2 ──┤
   │                       ├─> merge when ready
   ├─ fix/bug-123 ────────┘
   │
   └─> merge to main (triggers Railway deploy)
       │
       main (production)
       └─> 🚀 https://simadu-kmc.railway.app
```

## 🎯 Best Practices

### DO ✅
- Selalu pull terbaru sebelum mulai coding
- Commit sering dengan message yang jelas
- Test di local sebelum push
- Hapus branch setelah merged
- Keep commits atomic (1 commit = 1 logical change)

### DON'T ❌
- Jangan commit langsung ke main
- Jangan commit file `.env`
- Jangan commit `node_modules/` atau `vendor/`
- Jangan force push ke main
- Jangan merge conflict yang belum resolve

## 🔄 Sync Workflow

### Keep develop up-to-date with main

```bash
# Regular sync (setiap hari/minggu)
git checkout develop
git pull origin main
git push origin develop
```

### Resolve Merge Conflicts

```bash
# If conflict during merge
git checkout develop
git merge main

# Git will mark conflicts in files
# Edit files, resolve conflicts
# Then:
git add .
git commit -m "chore: resolve merge conflicts"
git push origin develop
```

## 📚 Quick Reference

```bash
# Create new feature
git checkout develop
git checkout -b feature/my-feature

# Work on feature
git add .
git commit -m "feat: description"
git push -u origin feature/my-feature

# Merge to develop
git checkout develop
git merge feature/my-feature
git push origin develop

# Deploy to production
git checkout main
git merge develop
git push origin main  # ← Railway auto-deploy

# Cleanup
git branch -d feature/my-feature
git push origin --delete feature/my-feature
```

## 🆘 Troubleshooting

### Accidentally committed to main

```bash
# Revert the commit
git revert HEAD
git push origin main
```

### Need to undo last commit (local only)

```bash
git reset --soft HEAD~1  # Keep changes
# or
git reset --hard HEAD~1  # Discard changes
```

### Branch diverged

```bash
git checkout develop
git pull --rebase origin develop
```

---

**Remember:** `develop` untuk coding, `main` untuk production! 🚀
