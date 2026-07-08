# Contributing to SIMADU-KMC

Terima kasih atas minat Anda untuk berkontribusi pada proyek SIMADU-KMC! Dokumen ini berisi panduan untuk berkontribusi pada proyek ini.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)
- [Testing](#testing)

## 🤝 Code of Conduct

Proyek ini mengikuti kode etik yang menghormati semua kontributor. Dengan berpartisipasi, Anda diharapkan untuk menjaga lingkungan yang profesional dan inklusif.

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.3
- Composer
- Node.js >= 18
- MySQL >= 8.0
- Git

### Setup Development Environment

1. Fork repository ini
2. Clone fork Anda:
   ```bash
   git clone https://github.com/YOUR_USERNAME/simadu-kmc.git
   cd simadu-kmc
   ```

3. Install dependencies:
   ```bash
   composer install
   npm install
   ```

4. Setup environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Setup database:
   ```bash
   php artisan migrate --seed
   ```

6. Run development server:
   ```bash
   composer dev
   ```

## 🔄 Development Workflow

1. **Create a branch** dari `main`:
   ```bash
   git checkout -b feature/nama-fitur
   # atau
   git checkout -b fix/nama-bug
   ```

2. **Make your changes** dengan mengikuti coding standards

3. **Test your changes**:
   ```bash
   composer test
   php artisan pint
   ```

4. **Commit your changes** dengan conventional commit message

5. **Push to your fork**:
   ```bash
   git push origin feature/nama-fitur
   ```

6. **Create a Pull Request** ke branch `main`

## 💻 Coding Standards

### PHP

- Ikuti [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
- Gunakan **Laravel Pint** untuk formatting:
  ```bash
  php artisan pint
  ```

- Gunakan **PHP Stan** untuk static analysis (jika tersedia):
  ```bash
  ./vendor/bin/phpstan analyse
  ```

### JavaScript

- Gunakan ES6+ syntax
- Ikuti consistent indentation (2 spaces)
- Gunakan meaningful variable names

### Blade Templates

- Indentation: 4 spaces
- Gunakan `{{ }}` untuk output yang di-escape
- Gunakan `{!! !!}` hanya jika benar-benar perlu (raw HTML)

### Database

- Gunakan migrations untuk semua perubahan schema
- Beri nama yang descriptive pada migrations
- Jangan pernah edit migration yang sudah di-push

## 📝 Commit Guidelines

Kami menggunakan [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- **feat**: Fitur baru
- **fix**: Bug fix
- **docs**: Perubahan dokumentasi
- **style**: Formatting, missing semi colons, dll (tidak mengubah kode)
- **refactor**: Refactoring kode
- **test**: Menambah atau memperbaiki tests
- **chore**: Maintenance tasks, dependency updates

### Examples

```bash
feat(tickets): add bulk assignment feature

- Add checkbox selection for multiple tickets
- Add bulk assign dropdown in ticket list
- Update TicketController with bulkAssign method

Closes #123
```

```bash
fix(ai): improve classification accuracy for infrastructure complaints

- Update AI prompt with more specific keywords
- Add fallback category handling
- Increase confidence threshold to 0.85

Fixes #456
```

## 🔍 Pull Request Process

1. **Update documentation** jika ada perubahan API atau fitur baru

2. **Add tests** untuk fitur baru atau bug fixes

3. **Run all tests** dan pastikan semua passing:
   ```bash
   composer test
   ```

4. **Format code** dengan Pint:
   ```bash
   php artisan pint
   ```

5. **Update README.md** jika diperlukan

6. **Fill PR template** dengan informasi lengkap:
   - Deskripsi perubahan
   - Screenshot (jika UI changes)
   - Testing steps
   - Related issues

7. **Wait for review** - minimal 1 approval dari maintainer

### PR Title Format

```
<type>(<scope>): <description>
```

Examples:
- `feat(dashboard): add real-time notification widget`
- `fix(auth): resolve login redirect loop`
- `docs(readme): update installation instructions`

## 🧪 Testing

### Running Tests

```bash
# Run all tests
composer test

# Run specific test
php artisan test --filter=TicketEditTest

# Run with coverage
composer test:coverage
```

### Writing Tests

- Gunakan **Feature Tests** untuk testing HTTP endpoints
- Gunakan **Unit Tests** untuk testing logic methods
- Minimal 80% code coverage untuk fitur baru
- Test happy path dan edge cases

Example:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;

class TicketTest extends TestCase
{
    public function test_admin_can_create_ticket()
    {
        $admin = User::factory()->admin()->create();
        
        $response = $this->actingAs($admin)
            ->post('/tickets', [
                'title' => 'Test Ticket',
                'description' => 'Test Description',
                'priority' => 'normal',
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'title' => 'Test Ticket',
        ]);
    }
}
```

## 🐛 Bug Reports

Jika menemukan bug, buat issue dengan informasi:

- **Deskripsi**: Jelaskan bug dengan jelas
- **Steps to Reproduce**: Langkah-langkah untuk reproduce bug
- **Expected Behavior**: Apa yang seharusnya terjadi
- **Actual Behavior**: Apa yang sebenarnya terjadi
- **Screenshots**: Jika memungkinkan
- **Environment**: Browser, OS, PHP version, dll

## 💡 Feature Requests

Untuk request fitur baru:

1. Cek existing issues untuk menghindari duplikat
2. Buat issue dengan label `enhancement`
3. Jelaskan use case dan benefit dari fitur tersebut
4. Diskusikan dengan maintainers sebelum implementasi

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [PHP The Right Way](https://phptherightway.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)

## 📞 Questions?

Jika ada pertanyaan, silakan:
- Buka discussion di GitHub
- Contact maintainer via email

---

**Thank you for contributing to SIMADU-KMC! 🎉**
