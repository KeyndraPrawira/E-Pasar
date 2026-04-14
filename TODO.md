# TODO: Fix Auth/RegisterController ke View-based Flow

## Status: 📋 In Progress

### Steps:
- [x] 1. Explorasi repo (search_files + read_file)
- [x] 2. Analisis struktur & buat edit plan  
- [x] 3. Konfirmasi plan dengan user
- [x] 4. Edit RegisterController.php (fix verifyOtp + resendOtp ke view flow)
- [ ] 5. Test manual: /register → OTP → verify → success
- [ ] 6. Jalankan unit test (jika ada)
- [ ] 7. Complete task ✅

**Current focus:** Step 4 - Edit file controller

**Path:** app/Http/Controllers/Auth/RegisterController.php
**Changes:** 
• Fix bug email di verifyOtp()
• Ubah resendOtp() dari JSON ke redirect()->with('success/error')

