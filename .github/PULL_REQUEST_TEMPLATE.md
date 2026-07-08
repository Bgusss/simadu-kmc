name: Pull Request Template
description: Template for creating pull requests
labels: []

body:
  - type: markdown
    attributes:
      value: |
        ## Pull Request Checklist
        
        Terima kasih telah berkontribusi pada SIMADU-KMC! Pastikan PR Anda memenuhi checklist berikut:

  - type: checkboxes
    id: checklist
    attributes:
      label: Checklist
      options:
        - label: Code mengikuti coding standards (Laravel Pint)
          required: true
        - label: Semua tests passing
          required: true
        - label: Dokumentasi sudah diupdate (jika diperlukan)
          required: false
        - label: Commit messages mengikuti Conventional Commits
          required: true
        - label: Branch sudah up-to-date dengan main
          required: true

  - type: dropdown
    id: type
    attributes:
      label: Tipe Perubahan
      description: Pilih tipe perubahan yang paling sesuai
      options:
        - Feature (fitur baru)
        - Fix (bug fix)
        - Refactor (refactoring kode)
        - Docs (dokumentasi)
        - Test (menambah/memperbaiki tests)
        - Chore (maintenance tasks)
    validations:
      required: true

  - type: textarea
    id: description
    attributes:
      label: Deskripsi
      description: Jelaskan perubahan yang Anda buat
      placeholder: |
        Apa yang berubah? Mengapa perubahan ini diperlukan?
    validations:
      required: true

  - type: textarea
    id: related-issues
    attributes:
      label: Related Issues
      description: Link ke issue yang terkait (jika ada)
      placeholder: |
        Closes #123
        Fixes #456
    validations:
      required: false

  - type: textarea
    id: testing
    attributes:
      label: Testing Steps
      description: Jelaskan bagaimana cara testing perubahan ini
      placeholder: |
        1. Setup environment dengan...
        2. Jalankan command...
        3. Verifikasi hasil...
    validations:
      required: true

  - type: textarea
    id: screenshots
    attributes:
      label: Screenshots (jika ada perubahan UI)
      description: Tambahkan screenshot untuk perubahan visual
      placeholder: Drag and drop gambar di sini
    validations:
      required: false

  - type: textarea
    id: additional-notes
    attributes:
      label: Additional Notes
      description: Informasi tambahan yang perlu diketahui reviewer
      placeholder: Breaking changes, dependencies baru, dll
    validations:
      required: false
