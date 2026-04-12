# TODO.md - Perbaikan Error Seeding Database (Unknown column 'password')

## ✅ **Selesai**
- [x] Step 1: Buat file TODO.md dengan progress tracking

# TODO.md - Fitur Ganti Password dengan OTP (API untuk pedagang/user/driver)

## ✅ **Selesai - Database Fix**
- [x] Fix migration users table  
- [x] Buat TODO.md tracking

## ✅ **Fitur Ganti Password - IMPLEMENTASI LENGKAP**
- [x] Tambah PURPOSE_PASSWORD_CHANGE di EmailOTP model
- [x] Buat PasswordChangeController.php (sendOtp/verifyOtp/resendOtp)
- [x] Tambah routes API di routes/api.php  
- [x] Update ProfileController.updatePassword() (pakai OTP cache)

## 📋 **Testing & Complete**
- [ ] Step 4: Test API endpoints  
- [ ] Step 5: Unit tests
- [ ] Step 6: Dokumentasi Flutter integration
- [ ] Complete task


## ⏳ **Belum Dikerjakan**  
- [ ] Step 3: Jalankan `php artisan migrate:fresh --seed`
- [ ] Step 4: Test login admin (`admin@epasar.id` / `Admin123`)
- [ ] Step 5: Unit test User model & seeder
- [ ] Step 6: Complete task
