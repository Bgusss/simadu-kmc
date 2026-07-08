# Security Policy

## 🔒 Supported Versions

Versi yang saat ini didukung dengan security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## 🚨 Reporting a Vulnerability

Jika Anda menemukan security vulnerability di SIMADU-KMC, kami sangat menghargai laporan Anda. Mohon **JANGAN** membuat public issue untuk security vulnerabilities.

### Cara Melaporkan

1. **Email**: Kirim laporan ke bagusaprianto@example.com dengan subject "SECURITY: [deskripsi singkat]"
2. **Private Disclosure**: Gunakan GitHub Security Advisories jika tersedia

### Informasi yang Harus Disertakan

- Deskripsi vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (jika ada)
- Your contact information

### Response Timeline

- **24 jam**: Konfirmasi penerimaan laporan
- **7 hari**: Initial assessment dan feedback
- **30 hari**: Target untuk patch release (tergantung severity)

## 🛡️ Security Best Practices

### Untuk Users

1. **Jangan commit credentials** ke repository
2. **Gunakan environment variables** untuk sensitive data
3. **Update dependencies** secara berkala
4. **Enable 2FA** untuk GitHub account
5. **Review permissions** untuk OPD users

### Untuk Developers

1. **Input Validation**: Selalu validasi input dari user
2. **SQL Injection**: Gunakan Eloquent ORM atau prepared statements
3. **XSS Protection**: Gunakan `{{ }}` di Blade, bukan `{!! !!}`
4. **CSRF Protection**: Pastikan `@csrf` ada di semua forms
5. **Authentication**: Gunakan Laravel built-in auth
6. **Authorization**: Implementasikan proper role checks
7. **Rate Limiting**: Implement rate limiting untuk API endpoints
8. **Dependencies**: Audit dependencies dengan `composer audit`

## 🔐 Known Security Considerations

### Playwright Session Files

File session Playwright (Facebook/Instagram) berisi credentials. Pastikan:
- File ini di `.gitignore`
- Tidak pernah di-commit ke repository
- Disimpan dengan permission yang tepat

### API Keys

- Gemini API Key harus di `.env`
- Jangan hardcode API keys di kode
- Rotate keys secara berkala

### Database

- Gunakan strong passwords
- Limit database access
- Regular backups
- Encrypt sensitive fields jika perlu

## 📋 Security Checklist untuk Deployment

- [ ] `.env` tidak ter-commit
- [ ] `APP_DEBUG=false` di production
- [ ] `APP_ENV=production`
- [ ] HTTPS enabled
- [ ] Firewall configured
- [ ] Database credentials secured
- [ ] File permissions correct (755/644)
- [ ] Regular backup schedule
- [ ] Monitoring & logging enabled
- [ ] Rate limiting configured

## 🔄 Security Updates

Security patches akan dirilis secepat mungkin setelah vulnerability dikonfirmasi. Update akan diumumkan via:

- GitHub Security Advisories
- Release notes
- Email ke maintainers

## 📚 Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [PHP Security Guide](https://phptherightway.com/#security)

## 🙏 Thanks

Terima kasih kepada security researchers yang bertanggung jawab melaporkan vulnerabilities:

- (List akan diupdate setelah ada laporan)

---

**Last Updated**: 2026-07-08
